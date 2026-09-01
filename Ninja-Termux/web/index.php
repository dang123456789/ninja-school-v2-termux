<?php
mysqli_report(MYSQLI_REPORT_OFF);

$socket = getenv('PREFIX') . '/var/run/mysqld/mysqld.sock';

$db = new mysqli(
    'localhost',
    'root',
    '',
    'nso',
    0,
    $socket
);

if ($db->connect_error) {
    die('Không kết nối được MariaDB: ' . htmlspecialchars($db->connect_error));
}

$db->set_charset('utf8mb4');

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function ident($v) {
    return '`' . str_replace('`', '``', $v) . '`';
}

$tables = [];
$r = $db->query("SHOW TABLES");

while ($r && ($row = $r->fetch_row())) {
    $tables[] = $row[0];
}

$table = $_GET['table'] ?? ($tables[0] ?? '');

if (!in_array($table, $tables, true)) {
    $table = $tables[0] ?? '';
}

$columns = [];

if ($table) {
    $r = $db->query("SHOW COLUMNS FROM " . ident($table));

    while ($r && ($row = $r->fetch_assoc())) {
        $columns[] = $row;
    }
}

$primary = null;

foreach ($columns as $c) {
    if ($c['Key'] === 'PRI') {
        $primary = $c['Field'];
        break;
    }
}

$message = '';

/* DELETE */
if (
    isset($_GET['delete']) &&
    isset($_GET['id']) &&
    $primary &&
    $table
) {
    $stmt = $db->prepare(
        "DELETE FROM " . ident($table) .
        " WHERE " . ident($primary) . " = ?"
    );

    if ($stmt) {
        $id = (string)$_GET['id'];
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->close();

        header(
            "Location: ?table=" .
            urlencode($table) .
            "&msg=deleted"
        );
        exit;
    }
}

/* SAVE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $values = $_POST['data'] ?? [];
    $editId = $_POST['edit_id'] ?? '';

    if ($editId !== '' && $primary) {

        $sets = [];
        $params = [];
        $types = '';

        foreach ($columns as $c) {

            $field = $c['Field'];

            if ($field === $primary) {
                continue;
            }

            if (!array_key_exists($field, $values)) {
                continue;
            }

            $sets[] = ident($field) . " = ?";
            $params[] = $values[$field];
            $types .= 's';
        }

        if ($sets) {

            $sql =
                "UPDATE " . ident($table) .
                " SET " . implode(',', $sets) .
                " WHERE " . ident($primary) . " = ?";

            $params[] = $editId;
            $types .= 's';

            $stmt = $db->prepare($sql);

            if ($stmt) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $stmt->close();

                header(
                    "Location: ?table=" .
                    urlencode($table) .
                    "&msg=updated"
                );
                exit;
            }
        }

    } else {

        $fields = [];
        $marks = [];
        $params = [];
        $types = '';

        foreach ($columns as $c) {

            $field = $c['Field'];

            if (!array_key_exists($field, $values)) {
                continue;
            }

            if (
                $c['Extra'] === 'auto_increment' &&
                $values[$field] === ''
            ) {
                continue;
            }

            $fields[] = ident($field);
            $marks[] = '?';
            $params[] = $values[$field];
            $types .= 's';
        }

        if ($fields) {

            $sql =
                "INSERT INTO " . ident($table) .
                " (" . implode(',', $fields) . ")" .
                " VALUES (" . implode(',', $marks) . ")";

            $stmt = $db->prepare($sql);

            if ($stmt) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $stmt->close();

                header(
                    "Location: ?table=" .
                    urlencode($table) .
                    "&msg=added"
                );
                exit;
            }
        }
    }
}

/* EDIT */
$edit = null;

if (isset($_GET['edit']) && $primary) {

    $stmt = $db->prepare(
        "SELECT * FROM " . ident($table) .
        " WHERE " . ident($primary) .
        " = ? LIMIT 1"
    );

    if ($stmt) {

        $id = (string)$_GET['edit'];

        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result) {
            $edit = $result->fetch_assoc();
        }

        $stmt->close();
    }
}

/* SEARCH */
$q = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM " . ident($table);

if ($q !== '' && $columns) {

    $parts = [];
    $safeQ = $db->real_escape_string($q);

    foreach ($columns as $c) {

        $parts[] =
            "CAST(" .
            ident($c['Field']) .
            " AS CHAR) LIKE '%" .
            $safeQ .
            "%'";
    }

    $sql .= " WHERE " . implode(" OR ", $parts);
}

$sql .= " LIMIT 100";

$data = $table ? $db->query($sql) : false;

$total = 0;

if ($table) {

    $r = $db->query(
        "SELECT COUNT(*) AS total FROM " .
        ident($table)
    );

    if ($r) {
        $total = (int)$r->fetch_assoc()['total'];
    }
}

$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>

<html lang="vi">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>NRO VIP PANEL</title>

<style>

* {
    box-sizing:border-box;
}

body {
    margin:0;
    background:
        radial-gradient(circle at top,#24104d,#090914 45%);
    color:#eee;
    font-family:Arial,sans-serif;
}

.header {
    height:70px;
    display:flex;
    align-items:center;
    padding:0 20px;
    background:rgba(10,9,22,.95);
    border-bottom:1px solid #3b2b62;
    position:sticky;
    top:0;
    z-index:20;
}

.logo {
    font-size:22px;
    font-weight:bold;
}

.logo span {
    color:#b56cff;
}

.layout {
    display:flex;
    min-height:calc(100vh - 70px);
}

.sidebar {
    width:250px;
    background:rgba(12,11,23,.96);
    border-right:1px solid #2c2343;
    padding:16px;
    overflow:auto;
}

.title {
    color:#8e83a7;
    font-size:12px;
    text-transform:uppercase;
    margin:8px;
}

.sidebar a {
    display:block;
    padding:12px;
    margin:5px 0;
    border-radius:10px;
    color:#bbb;
    text-decoration:none;
}

.sidebar a:hover,
.sidebar a.active {
    background:linear-gradient(90deg,#402075,#202454);
    color:#fff;
}

.main {
    flex:1;
    padding:20px;
    min-width:0;
}

.stats {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
    margin-bottom:18px;
}

.card {
    background:rgba(18,16,32,.92);
    border:1px solid #332750;
    border-radius:15px;
    padding:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.25);
}

.stat-name {
    color:#9e96ad;
    font-size:13px;
}

.stat-value {
    font-size:26px;
    font-weight:bold;
    margin-top:7px;
}

h2 {
    margin-top:0;
}

.search {
    display:flex;
    gap:8px;
    margin-bottom:18px;
}

input,
textarea {
    width:100%;
    background:#090913;
    border:1px solid #3a2d58;
    color:#fff;
    border-radius:9px;
    padding:11px;
    outline:none;
}

input:focus,
textarea:focus {
    border-color:#a96cff;
}

button,
.btn {
    border:0;
    padding:10px 14px;
    border-radius:9px;
    color:#fff;
    background:#5426a5;
    text-decoration:none;
    cursor:pointer;
    display:inline-block;
}

button:hover,
.btn:hover {
    filter:brightness(1.2);
}

.edit {
    background:#24558c;
}

.delete {
    background:#842e45;
}

.cancel {
    background:#44404f;
}

.notice {
    padding:12px;
    margin-bottom:15px;
    border-radius:9px;
    background:#17382f;
    border:1px solid #286650;
}

.form-grid {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
}

.field label {
    display:block;
    margin-bottom:6px;
    color:#aaa;
    font-size:13px;
}

.table-wrap {
    overflow:auto;
}

table {
    width:100%;
    min-width:750px;
    border-collapse:collapse;
}

th,
td {
    padding:11px;
    border-bottom:1px solid #2b2639;
    text-align:left;
    vertical-align:top;
}

th {
    background:#19152a;
}

td {
    max-width:350px;
    word-break:break-word;
}

.actions {
    white-space:nowrap;
}

.badge {
    display:inline-block;
    padding:5px 8px;
    background:#28184a;
    border-radius:7px;
    margin:2px;
    color:#cdb8ef;
}

hr {
    border:0;
    border-top:1px solid #2b2639;
    margin:25px 0;
}

@media(max-width:800px) {

    .layout {
        display:block;
    }

    .sidebar {
        width:100%;
        max-height:220px;
        border-right:0;
        border-bottom:1px solid #2c2343;
    }

    .main {
        padding:12px;
    }

    .stats {
        grid-template-columns:1fr;
    }

    .form-grid {
        grid-template-columns:1fr;
    }

}

</style>

</head>

<body>

<header class="header">

<div class="logo">
🐉 <span>NRO VIP</span> PANEL
</div>

</header>

<div class="layout">

<aside class="sidebar">

<div class="title">
Database • nso
</div>

<?php foreach ($tables as $t): ?>

<a
class="<?= $t === $table ? 'active' : '' ?>"
href="?table=<?=urlencode($t)?>">

🗃️ <?=e($t)?>

</a>

<?php endforeach; ?>

</aside>

<main class="main">

<div class="stats">

<div class="card">

<div class="stat-name">
DATABASE
</div>

<div class="stat-value">
nso
</div>

</div>

<div class="card">

<div class="stat-name">
SỐ BẢNG
</div>

<div class="stat-value">
<?=count($tables)?>
</div>

</div>

<div class="card">

<div class="stat-name">
SỐ DÒNG
</div>

<div class="stat-value">
<?=number_format($total)?>
</div>

</div>

</div>

<?php if ($msg): ?>

<div class="notice">

<?php if ($msg === 'added'): ?>
✅ Đã thêm dữ liệu.

<?php elseif ($msg === 'updated'): ?>
✅ Đã lưu thay đổi.

<?php elseif ($msg === 'deleted'): ?>
🗑️ Đã xóa dữ liệu.

<?php endif; ?>

</div>

<?php endif; ?>

<div class="card">

<h2>
🗃️ <?=e($table ?: 'Dashboard')?>
</h2>

<?php if ($table): ?>

<form class="search" method="get">

<input
type="hidden"
name="table"
value="<?=e($table)?>">

<input
name="q"
value="<?=e($q)?>"
placeholder="🔎 Tìm kiếm dữ liệu...">

<button>
Tìm
</button>

</form>

<h3>
<?= $edit ? '✏️ Chỉnh sửa' : '➕ Thêm dữ liệu' ?>
</h3>

<form method="post">

<input
type="hidden"
name="action"
value="save">

<?php if ($edit): ?>

<input
type="hidden"
name="edit_id"
value="<?=e($edit[$primary])?>">

<?php endif; ?>

<div class="form-grid">

<?php foreach ($columns as $c): ?>

<?php

$field = $c['Field'];

$value = $edit[$field] ?? '';

$isPrimary =
    $primary === $field;

?>

<div class="field">

<label>

<?=e($field)?>

<?php if ($isPrimary): ?>
🔑
<?php endif; ?>

</label>

<?php if (
    stripos($c['Type'],'text') !== false ||
    stripos($c['Type'],'blob') !== false
): ?>

<textarea
name="data[<?=e($field)?>]"
rows="4"
><?=e($value)?></textarea>

<?php else: ?>

<input
name="data[<?=e($field)?>]"
value="<?=e($value)?>"
<?=($edit && $isPrimary) ? 'readonly' : ''?>
>

<?php endif; ?>

</div>

<?php endforeach; ?>

</div>

<br>

<button type="submit">

<?= $edit ? '💾 LƯU THAY ĐỔI' : '➕ THÊM DỮ LIỆU' ?>

</button>

<?php if ($edit): ?>

<a
class="btn cancel"
href="?table=<?=urlencode($table)?>">

Hủy

</a>

<?php endif; ?>

</form>

<hr>

<h3>
📊 Dữ liệu
</h3>

<div class="table-wrap">

<table>

<thead>

<tr>

<?php foreach ($columns as $c): ?>

<th>
<?=e($c['Field'])?>
</th>

<?php endforeach; ?>

<th>
THAO TÁC
</th>

</tr>

</thead>

<tbody>

<?php if ($data && $data->num_rows): ?>

<?php while ($row = $data->fetch_assoc()): ?>

<tr>

<?php foreach ($columns as $c): ?>

<td>
<?=e($row[$c['Field']] ?? '')?>
</td>

<?php endforeach; ?>

<td class="actions">

<?php if ($primary): ?>

<a
class="btn edit"
href="?table=<?=urlencode($table)?>&edit=<?=urlencode($row[$primary])?>">

✏️ Sửa

</a>

<a
class="btn delete"
href="?table=<?=urlencode($table)?>&delete=1&id=<?=urlencode($row[$primary])?>"
onclick="return confirm('Bạn chắc chắn muốn xóa bản ghi này?')">

🗑️ Xóa

</a>

<?php else: ?>

Không có khóa chính

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="<?=count($columns)+1?>">

Không có dữ liệu.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<?php else: ?>

<p>
Chọn bảng ở menu bên trái.
</p>

<?php endif; ?>

</div>

</main>

</div>

</body>

</html>
