<?php
mysqli_report(MYSQLI_REPORT_OFF);

$socket = getenv('PREFIX') . '/var/run/mysqld/mysqld.sock';
$db = 'nso';
$user = 'root';
$pass = '';

$conn = new mysqli('localhost', $user, $pass, $db, 0, $socket);

if ($conn->connect_error) {
    die('Không kết nối được MariaDB: ' . htmlspecialchars($conn->connect_error));
}

$conn->set_charset('utf8mb4');

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function qi($v) {
    return '`' . str_replace('`', '``', $v) . '`';
}

/* =========================
   LẤY DANH SÁCH TABLE
========================= */

$tables = [];
$result = $conn->query("SHOW TABLES");

if ($result) {
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
}

$table = $_GET['table'] ?? '';

if (!$table || !in_array($table, $tables, true)) {
    $table = $tables[0] ?? '';
}

/* =========================
   CỘT + PRIMARY KEY
========================= */

$columns = [];
$primary = null;

if ($table) {
    $result = $conn->query("SHOW COLUMNS FROM " . qi($table));

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row;

            if ($row['Key'] === 'PRI') {
                $primary = $row['Field'];
            }
        }
    }
}

/* =========================
   THÔNG BÁO
========================= */

$message = '';
$error = '';

/* =========================
   XÓA
========================= */

if (
    isset($_GET['delete']) &&
    $table &&
    $primary
) {
    $value = $_GET['delete'];

    $stmt = $conn->prepare(
        "DELETE FROM " . qi($table) .
        " WHERE " . qi($primary) . " = ? LIMIT 1"
    );

    if ($stmt) {
        $stmt->bind_param('s', $value);

        if ($stmt->execute()) {
            $message = 'Đã xóa dữ liệu thành công.';
        } else {
            $error = 'Không thể xóa dữ liệu.';
        }

        $stmt->close();
    }

    header(
        "Location: ?table=" .
        urlencode($table) .
        "&msg=" .
        urlencode($message ?: $error)
    );

    exit;
}

/* =========================
   THÊM
========================= */

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'add' &&
    $table
) {
    $fields = [];
    $values = [];

    foreach ($columns as $column) {

        $name = $column['Field'];

        if (
            stripos($column['Extra'], 'auto_increment') !== false
        ) {
            continue;
        }

        if (array_key_exists($name, $_POST)) {
            $fields[] = qi($name);
            $values[] = $_POST[$name];
        }
    }

    if ($fields) {

        $marks = implode(
            ',',
            array_fill(0, count($fields), '?')
        );

        $sql =
            "INSERT INTO " .
            qi($table) .
            " (" .
            implode(',', $fields) .
            ") VALUES (" .
            $marks .
            ")";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $types = str_repeat(
                's',
                count($values)
            );

            $stmt->bind_param(
                $types,
                ...$values
            );

            if ($stmt->execute()) {
                $message = 'Đã thêm dữ liệu.';
            } else {
                $error = $stmt->error;
            }

            $stmt->close();

        } else {
            $error = $conn->error;
        }
    }

    header(
        "Location: ?table=" .
        urlencode($table) .
        "&msg=" .
        urlencode($message ?: $error)
    );

    exit;
}

/* =========================
   SỬA
========================= */

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'edit' &&
    $table &&
    $primary
) {

    $oldPrimary = $_POST['_old_primary'] ?? '';

    $sets = [];
    $values = [];

    foreach ($columns as $column) {

        $name = $column['Field'];

        if (
            $name === $primary ||
            !array_key_exists($name, $_POST)
        ) {
            continue;
        }

        $sets[] = qi($name) . " = ?";
        $values[] = $_POST[$name];
    }

    if ($sets) {

        $values[] = $oldPrimary;

        $sql =
            "UPDATE " .
            qi($table) .
            " SET " .
            implode(',', $sets) .
            " WHERE " .
            qi($primary) .
            " = ? LIMIT 1";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $types = str_repeat(
                's',
                count($values)
            );

            $stmt->bind_param(
                $types,
                ...$values
            );

            if ($stmt->execute()) {
                $message = 'Đã cập nhật dữ liệu.';
            } else {
                $error = $stmt->error;
            }

            $stmt->close();

        } else {
            $error = $conn->error;
        }
    }

    header(
        "Location: ?table=" .
        urlencode($table) .
        "&msg=" .
        urlencode($message ?: $error)
    );

    exit;
}

/* =========================
   DỮ LIỆU ĐANG SỬA
========================= */

$editRow = null;

if (
    isset($_GET['edit']) &&
    $table &&
    $primary
) {

    $value = $_GET['edit'];

    $stmt = $conn->prepare(
        "SELECT * FROM " .
        qi($table) .
        " WHERE " .
        qi($primary) .
        " = ? LIMIT 1"
    );

    if ($stmt) {

        $stmt->bind_param(
            's',
            $value
        );

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result) {
            $editRow = $result->fetch_assoc();
        }

        $stmt->close();
    }
}

/* =========================
   TÌM KIẾM
========================= */

$search = $_GET['search'] ?? '';

$rows = [];

if ($table) {

    $sql =
        "SELECT * FROM " .
        qi($table);

    if (
        $search !== '' &&
        $columns
    ) {

        $safeSearch =
            $conn->real_escape_string(
                $search
            );

        $where = [];

        foreach ($columns as $column) {

            $where[] =
                qi($column['Field']) .
                " LIKE '%" .
                $safeSearch .
                "%'";
        }

        $sql .=
            " WHERE " .
            implode(
                " OR ",
                $where
            );
    }

    $sql .= " LIMIT 100";

    $result = $conn->query($sql);

    if ($result) {

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
}

/* =========================
   THỐNG KÊ
========================= */

$totalRows = 0;

foreach ($tables as $t) {

    $result = $conn->query(
        "SELECT COUNT(*) AS total FROM " .
        qi($t)
    );

    if ($result) {

        $data =
            $result->fetch_assoc();

        $totalRows +=
            (int)$data['total'];
    }
}

$totalTables = count($tables);

$msg = $_GET['msg'] ?? '';

?>
<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1"
>

<title>NRO VIP PANEL</title>

<style>

* {
    box-sizing:border-box;
}

body {

    margin:0;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background:
        radial-gradient(
            circle at top right,
            #10263b 0,
            #070a10 40%,
            #05070b 100%
        );

    color:#eaf7ff;

    min-height:100vh;
}

/* HEADER */

.header {

    height:70px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 25px;

    background:
        rgba(8,13,22,.94);

    border-bottom:
        1px solid #16364b;

    box-shadow:
        0 0 25px
        rgba(0,220,255,.08);

    position:sticky;

    top:0;

    z-index:20;
}

.logo {

    font-size:22px;

    font-weight:bold;

    color:#00eaff;

    text-shadow:
        0 0 12px
        rgba(0,234,255,.55);
}

.logo span {

    color:#fff;

    font-size:12px;

    margin-left:8px;

    opacity:.55;
}

.status {

    color:#52ff9a;

    font-size:13px;
}

/* LAYOUT */

.layout {

    display:flex;

    min-height:
        calc(100vh - 70px);
}

/* SIDEBAR */

.sidebar {

    width:240px;

    background:
        rgba(8,13,21,.92);

    border-right:
        1px solid #143044;

    padding:20px 12px;

    position:sticky;

    top:70px;

    height:
        calc(100vh - 70px);

    overflow-y:auto;
}

.side-title {

    font-size:11px;

    color:#5d7180;

    margin:
        8px 10px 12px;

    letter-spacing:2px;
}

.side-link {

    display:flex;

    align-items:center;

    gap:10px;

    text-decoration:none;

    color:#91a4b2;

    padding:11px 13px;

    margin-bottom:5px;

    border-radius:9px;

    transition:.2s;

    font-size:14px;
}

.side-link:hover {

    background:#0e2635;

    color:#00eaff;
}

.side-link.active {

    background:
        linear-gradient(
            90deg,
            #0c3549,
            #0b1d2a
        );

    color:#00eaff;

    box-shadow:
        inset 3px 0 0 #00eaff;
}

.icon {

    width:20px;

    text-align:center;
}

/* MAIN */

.main {

    flex:1;

    padding:25px;

    min-width:0;
}

/* DASHBOARD */

.welcome {

    margin-bottom:20px;
}

.welcome h1 {

    margin:0 0 5px;

    font-size:27px;
}

.welcome p {

    margin:0;

    color:#738897;
}

/* CARDS */

.cards {

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:15px;

    margin-bottom:22px;
}

.card {

    padding:20px;

    border:
        1px solid #17364a;

    border-radius:14px;

    background:
        linear-gradient(
            145deg,
            rgba(16,28,40,.96),
            rgba(8,13,21,.96)
        );

    box-shadow:
        0 10px 30px
        rgba(0,0,0,.18);

    position:relative;

    overflow:hidden;
}

.card:after {

    content:"";

    position:absolute;

    width:80px;

    height:80px;

    right:-30px;

    top:-30px;

    border-radius:50%;

    background:
        rgba(0,234,255,.08);
}

.card-title {

    color:#718895;

    font-size:12px;

    text-transform:uppercase;

    letter-spacing:1px;
}

.card-value {

    font-size:27px;

    font-weight:bold;

    margin-top:8px;

    color:#00eaff;
}

/* PANEL */

.panel {

    background:
        rgba(9,15,24,.94);

    border:
        1px solid #163448;

    border-radius:15px;

    overflow:hidden;

    box-shadow:
        0 12px 35px
        rgba(0,0,0,.2);
}

.panel-head {

    padding:18px 20px;

    border-bottom:
        1px solid #163448;

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:15px;

    flex-wrap:wrap;
}

.panel-title {

    font-size:19px;

    font-weight:bold;
}

.panel-title span {

    color:#00eaff;
}

.toolbar {

    display:flex;

    gap:8px;

    flex-wrap:wrap;
}

input,
select,
button {

    font-family:inherit;
}

.search {

    width:250px;

    padding:10px 13px;

    color:#fff;

    background:#080e16;

    border:
        1px solid #21465d;

    border-radius:8px;

    outline:none;
}

.search:focus {

    border-color:#00eaff;

    box-shadow:
        0 0 12px
        rgba(0,234,255,.12);
}

.btn {

    border:0;

    border-radius:8px;

    padding:10px 14px;

    cursor:pointer;

    color:#fff;

    font-weight:bold;

    text-decoration:none;

    display:inline-flex;

    align-items:center;

    gap:6px;
}

.btn-blue {

    background:#087f9c;
}

.btn-blue:hover {

    background:#00a9ce;
}

.btn-green {

    background:#087a4d;
}

.btn-red {

    background:#8b2635;
}

.btn-yellow {

    background:#876c13;
}

/* FORM */

.form-box {

    padding:20px;

    border-bottom:
        1px solid #163448;

    background:
        rgba(12,22,33,.7);
}

.form-title {

    margin:
        0 0 15px;

    color:#00eaff;

    font-size:16px;
}

.form-grid {

    display:grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:10px;
}

.field {

    display:flex;

    flex-direction:column;

    gap:5px;
}

.field label {

    color:#718895;

    font-size:11px;
}

.field input {

    width:100%;

    padding:10px;

    border-radius:7px;

    border:
        1px solid #1d3b4e;

    background:#080e16;

    color:#fff;

    outline:none;
}

.field input:focus {

    border-color:#00eaff;
}

.form-actions {

    margin-top:15px;

    display:flex;

    gap:8px;
}

/* TABLE */

.table-wrap {

    width:100%;

    overflow:auto;
}

table {

    width:100%;

    border-collapse:collapse;

    min-width:700px;
}

th {

    padding:13px 12px;

    background:#0d1925;

    color:#00eaff;

    font-size:12px;

    text-align:left;

    border-bottom:
        1px solid #1b3d52;

    position:sticky;

    top:0;
}

td {

    padding:12px;

    border-bottom:
        1px solid #122735;

    color:#b9c9d2;

    font-size:13px;

    max-width:300px;

    overflow:hidden;

    text-overflow:ellipsis;

    white-space:nowrap;
}

tr:hover td {

    background:#0b1722;
}

.actions {

    white-space:nowrap;
}

.action {

    display:inline-block;

    padding:6px 9px;

    border-radius:6px;

    text-decoration:none;

    font-size:12px;

    margin-right:4px;
}

.edit {

    color:#ffd75a;

    background:
        rgba(255,215,90,.08);
}

.delete {

    color:#ff6878;

    background:
        rgba(255,80,100,.08);
}

/* ALERT */

.alert {

    margin-bottom:15px;

    padding:12px 15px;

    border-radius:9px;

    background:#0b2b20;

    border:
        1px solid #176a4c;

    color:#65ffae;
}

.empty {

    padding:40px;

    text-align:center;

    color:#637784;
}

/* MOBILE */

@media(max-width:1000px) {

    .cards {

        grid-template-columns:
            repeat(2,1fr);
    }

    .form-grid {

        grid-template-columns:
            repeat(2,1fr);
    }

}

@media(max-width:700px) {

    .header {

        padding:0 15px;
    }

    .logo {

        font-size:18px;
    }

    .status {

        display:none;
    }

    .layout {

        display:block;
    }

    .sidebar {

        width:100%;

        height:auto;

        position:relative;

        top:0;

        border-right:0;

        border-bottom:
            1px solid #143044;

        padding:10px;

        display:flex;

        overflow-x:auto;
    }

    .side-title {

        display:none;
    }

    .side-link {

        flex:0 0 auto;

        margin:0 4px;

        padding:9px 12px;
    }

    .main {

        padding:12px;
    }

    .cards {

        grid-template-columns:
            repeat(2,1fr);

        gap:9px;
    }

    .card {

        padding:15px;
    }

    .card-value {

        font-size:21px;
    }

    .form-grid {

        grid-template-columns:1fr;
    }

    .search {

        width:100%;
    }

    .toolbar {

        width:100%;
    }

}

</style>

</head>

<body>

<header class="header">

    <div class="logo">
        ⚡ NRO VIP
        <span>ADMIN PANEL</span>
    </div>

    <div class="status">
        ● MariaDB ONLINE
    </div>

</header>

<div class="layout">

<!-- SIDEBAR -->

<aside class="sidebar">

<div class="side-title">
    DATABASE TABLES
</div>

<?php foreach ($tables as $t): ?>

<a
class="side-link <?= $t === $table ? 'active' : '' ?>"
href="?table=<?= urlencode($t) ?>"
>

<span class="icon">▣</span>

<?= e($t) ?>

</a>

<?php endforeach; ?>

</aside>

<!-- MAIN -->

<main class="main">

<div class="welcome">

<h1>Dashboard</h1>

<p>
Quản lý máy chủ Ninja School V2
</p>

</div>

<?php if ($msg): ?>

<div class="alert">
    ✓ <?= e($msg) ?>
</div>

<?php endif; ?>

<!-- CARDS -->

<div class="cards">

<div class="card">

<div class="card-title">
Database
</div>

<div class="card-value">
<?= e($db) ?>
</div>

</div>

<div class="card">

<div class="card-title">
Tables
</div>

<div class="card-value">
<?= $totalTables ?>
</div>

</div>

<div class="card">

<div class="card-title">
Total Records
</div>

<div class="card-value">
<?= number_format($totalRows) ?>
</div>

</div>

<div class="card">

<div class="card-title">
Server
</div>

<div class="card-value">
ONLINE
</div>

</div>

</div>

<?php if ($table): ?>

<div class="panel">

<div class="panel-head">

<div class="panel-title">

Bảng:
<span><?= e($table) ?></span>

</div>

<div class="toolbar">

<form method="get">

<input
type="hidden"
name="table"
value="<?= e($table) ?>"
>

<input
class="search"
name="search"
value="<?= e($search) ?>"
placeholder="🔎 Tìm kiếm dữ liệu..."
>

<button
class="btn btn-blue"
type="submit"
>
Tìm
</button>

</form>

</div>

</div>

<!-- EDIT -->

<?php if ($editRow): ?>

<div class="form-box">

<h3 class="form-title">
✏️ Chỉnh sửa dữ liệu
</h3>

<form method="post">

<input
type="hidden"
name="action"
value="edit"
>

<input
type="hidden"
name="_old_primary"
value="<?= e($editRow[$primary]) ?>"
>

<div class="form-grid">

<?php foreach ($columns as $column): ?>

<?php
$name = $column['Field'];
?>

<div class="field">

<label>
<?= e($name) ?>
</label>

<input
name="<?= e($name) ?>"
value="<?= e($editRow[$name] ?? '') ?>"
<?= $name === $primary ? 'readonly' : '' ?>
>

</div>

<?php endforeach; ?>

</div>

<div class="form-actions">

<button
class="btn btn-green"
type="submit"
>
💾 Lưu thay đổi
</button>

<a
class="btn btn-red"
href="?table=<?= urlencode($table) ?>"
>
Hủy
</a>

</div>

</form>

</div>

<!-- ADD -->

<?php else: ?>

<div class="form-box">

<h3 class="form-title">
➕ Thêm dữ liệu
</h3>

<form method="post">

<input
type="hidden"
name="action"
value="add"
>

<div class="form-grid">

<?php foreach ($columns as $column): ?>

<?php

$name = $column['Field'];

if (
stripos(
$column['Extra'],
'auto_increment'
) !== false
) {
continue;
}

?>

<div class="field">

<label>
<?= e($name) ?>
</label>

<input
name="<?= e($name) ?>"
placeholder="<?= e($name) ?>"
>

</div>

<?php endforeach; ?>

</div>

<div class="form-actions">

<button
class="btn btn-green"
type="submit"
>
＋ Thêm dữ liệu
</button>

</div>

</form>

</div>

<?php endif; ?>

<!-- DATA -->

<div class="table-wrap">

<?php if ($rows): ?>

<table>

<thead>

<tr>

<?php foreach ($columns as $column): ?>

<th>
<?= e($column['Field']) ?>
</th>

<?php endforeach; ?>

<?php if ($primary): ?>

<th>
THAO TÁC
</th>

<?php endif; ?>

</tr>

</thead>

<tbody>

<?php foreach ($rows as $row): ?>

<tr>

<?php foreach ($columns as $column): ?>

<td title="<?= e($row[$column['Field']] ?? '') ?>">

<?= e(
$row[$column['Field']] ?? ''
) ?>

</td>

<?php endforeach; ?>

<?php if ($primary): ?>

<td class="actions">

<a
class="action edit"
href="?table=<?= urlencode($table) ?>&edit=<?= urlencode($row[$primary]) ?>"
>
✏️ Sửa
</a>

<a
class="action delete"
href="?table=<?= urlencode($table) ?>&delete=<?= urlencode($row[$primary]) ?>"
onclick="return confirm('Bạn chắc chắn muốn xóa dữ liệu này?')"
>
🗑️ Xóa
</a>

</td>

<?php endif; ?>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<?php else: ?>

<div class="empty">
    Không có dữ liệu.
</div>

<?php endif; ?>

</div>

</div>

<?php else: ?>

<div class="panel">

<div class="empty">
    Chọn một bảng ở menu để quản lý dữ liệu.
</div>

</div>

<?php endif; ?>

</main>

</div>

</body>
</html>
