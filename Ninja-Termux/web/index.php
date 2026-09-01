<?php
mysqli_report(MYSQLI_REPORT_OFF);

$socket = getenv('PREFIX') . '/var/run/mysqld/mysqld.sock';
$db = new mysqli('localhost', 'root', '', 'nso', 0, $socket);

if ($db->connect_error) {
    die("❌ Không kết nối được MariaDB: " . htmlspecialchars($db->connect_error));
}
$db->set_charset('utf8mb4');

function esc($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function ident($s) {
    return '`' . str_replace('`', '``', $s) . '`';
}

/* Lấy danh sách bảng */
$tables = [];
$r = $db->query("SHOW TABLES");
while ($r && ($row = $r->fetch_row())) {
    $tables[] = $row[0];
}

$table = $_GET['table'] ?? ($tables[0] ?? '');

if (!in_array($table, $tables, true)) {
    $table = $tables[0] ?? '';
}

$cols = [];
if ($table) {
    $r = $db->query("SHOW COLUMNS FROM " . ident($table));
    while ($r && ($row = $r->fetch_assoc())) {
        $cols[] = $row;
    }
}

/* Xác định khóa chính */
$pk = null;
foreach ($cols as $c) {
    if ($c['Key'] === 'PRI') {
        $pk = $c['Field'];
        break;
    }
}

/* Xóa */
if (isset($_GET['delete']) && $pk && isset($_GET['id'])) {
    $stmt = $db->prepare(
        "DELETE FROM " . ident($table) .
        " WHERE " . ident($pk) . " = ?"
    );

    if ($stmt) {
        $id = $_GET['id'];
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ?table=" . urlencode($table));
    exit;
}

/* Thêm / sửa */
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $values = $_POST['data'] ?? [];
        $editId = $_POST['edit_id'] ?? '';

        if ($editId !== '' && $pk) {
            $sets = [];
            $params = [];
            $types = '';

            foreach ($cols as $c) {
                $field = $c['Field'];

                if ($field === $pk) continue;
                if (!array_key_exists($field, $values)) continue;

                $sets[] = ident($field) . " = ?";
                $params[] = $values[$field];
                $types .= 's';
            }

            if ($sets) {
                $sql = "UPDATE " . ident($table) .
                       " SET " . implode(',', $sets) .
                       " WHERE " . ident($pk) . " = ?";

                $params[] = $editId;
                $types .= 's';

                $stmt = $db->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $stmt->close();
                    $message = 'Đã cập nhật dữ liệu';
                }
            }
        } else {
            $fields = [];
            $marks = [];
            $params = [];
            $types = '';

            foreach ($cols as $c) {
                $field = $c['Field'];

                if (!array_key_exists($field, $values)) continue;

                $value = $values[$field];

                if ($c['Extra'] === 'auto_increment' && $value === '') {
                    continue;
                }

                $fields[] = ident($field);
                $marks[] = '?';
                $params[] = $value;
                $types .= 's';
            }

            if ($fields) {
                $sql = "INSERT INTO " . ident($table) .
                       " (" . implode(',', $fields) . ")" .
                       " VALUES (" . implode(',', $marks) . ")";

                $stmt = $db->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $stmt->close();
                    $message = 'Đã thêm dữ liệu';
                }
            }
        }
    }
}

/* Dữ liệu cần sửa */
$edit = null;

if (isset($_GET['edit']) && $pk) {
    $stmt = $db->prepare(
        "SELECT * FROM " . ident($table) .
        " WHERE " . ident($pk) . " = ? LIMIT 1"
    );

    if ($stmt) {
        $id = $_GET['edit'];
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $edit = $result->fetch_assoc();
        $stmt->close();
    }
}

/* Tìm kiếm */
$search = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM " . ident($table);

if ($search !== '' && $cols) {
    $parts = [];

    foreach ($cols as $c) {
        $parts[] = "CAST(" . ident($c['Field']) . " AS CHAR) LIKE '%" .
                   $db->real_escape_string($search) . "%'";
    }

    $sql .= " WHERE " . implode(" OR ", $parts);
}

$sql .= " LIMIT 100";

$data = $table ? $db->query($sql) : false;

$total = 0;
if ($table) {
    $count = $db->query("SELECT COUNT(*) AS c FROM " . ident($table));
    if ($count) {
        $total = (int)$count->fetch_assoc()['c'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>NRO VIP Panel</title>

<style>
*{box-sizing:border-box}

body{
    margin:0;
    background:#080812;
    color:#eee;
    font-family:Arial,sans-serif;
}

.top{
    height:65px;
    display:flex;
    align-items:center;
    padding:0 20px;
    background:linear-gradient(90deg,#16102c,#11172d);
    border-bottom:1px solid #30264d;
    position:sticky;
    top:0;
    z-index:10;
}

.logo{
    font-size:21px;
    font-weight:bold;
}

.layout{
    display:flex;
    min-height:calc(100vh - 65px);
}

.side{
    width:245px;
    background:#0d0c17;
    border-right:1px solid #28223d;
    padding:15px;
    overflow:auto;
}

.side-title{
    color:#999;
    font-size:12px;
    margin:10px 8px;
    text-transform:uppercase;
}

.side a{
    display:block;
    padding:11px 12px;
    margin:4px 0;
    color:#bbb;
    text-decoration:none;
    border-radius:9px;
}

.side a:hover,
.side a.active{
    background:linear-gradient(90deg,#24174b,#181d3d);
    color:#fff;
}

.main{
    flex:1;
    padding:20px;
    min-width:0;
}

.stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
    margin-bottom:18px;
}

.stat,.box{
    background:#11101e;
    border:1px solid #29233f;
    border-radius:13px;
    padding:18px;
}

.stat-title{
    color:#999;
    font-size:13px;
}

.stat-value{
    font-size:25px;
    font-weight:bold;
    margin-top:7px;
}

h2{
    margin-top:0;
}

.search{
    display:flex;
    gap:8px;
    margin-bottom:15px;
}

input,textarea{
    width:100%;
    background:#090914;
    color:#fff;
    border:1px solid #373050;
    border-radius:8px;
    padding:10px;
    outline:none;
}

button,.btn{
    border:0;
    border-radius:8px;
    padding:10px 14px;
    color:white;
    background:#39217c;
    text-decoration:none;
    cursor:pointer;
    display:inline-block;
}

.btn.edit{background:#284f82}
.btn.delete{background:#752d3e}

.table-wrap{
    overflow:auto;
}

table{
    width:100%;
    min-width:700px;
    border-collapse:collapse;
}

th,td{
    padding:10px;
    border-bottom:1px solid #29243a;
    text-align:left;
    vertical-align:top;
}

th{
    background:#181628;
    position:sticky;
    top:0;
}

td{
    max-width:300px;
    word-break:break-word;
}

.actions{
    white-space:nowrap;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:13px;
}

.field label{
    display:block;
    color:#aaa;
    font-size:13px;
    margin-bottom:6px;
}

.notice{
    padding:11px;
    margin-bottom:15px;
    background:#162c27;
    border:1px solid #27584d;
    border-radius:8px;
}

@media(max-width:800px){
    .layout{display:block}
    .side{
        width:100%;
        border-right:0;
        border-bottom:1px solid #28223d;
        max-height:220px;
    }

    .main{padding:12px}

    .stats{
        grid-template-columns:1fr;
    }

    .form-grid{
        grid-template-columns:1fr;
    }
}
</style>
</head>

<body>

<div class="top">
    <div class="logo">🐉 NRO VIP</div>
</div>

<div class="layout">

<aside class="side">

<div class="side-title">Database • nso</div>

<?php foreach ($tables as $t): ?>
<a class="<?= $t === $table ? 'active' : '' ?>"
   href="?table=<?=urlencode($t)?>">
   🗃️ <?=esc($t)?>
</a>
<?php endforeach; ?>

</aside>

<main class="main">

<div class="stats">

<div class="stat">
<div class="stat-title">Database</div>
<div class="stat-value">nso</div>
</div>

<div class="stat">
<div class="stat-title">Số bảng</div>
<div class="stat-value"><?=count($tables)?></div>
</div>

<div class="stat">
<div class="stat-title">Số dòng bảng</div>
<div class="stat-value"><?=number_format($total)?></div>
</div>

</div>

<?php if ($message): ?>
<div class="notice">✅ <?=esc($message)?></div>
<?php endif; ?>

<div class="box">

<h2>🗃️ <?=esc($table)?></h2>

<form class="search" method="get">
<input type="hidden" name="table" value="<?=esc($table)?>">
<input name="q" value="<?=esc($search)?>" placeholder="🔍 Tìm kiếm trong bảng...">
<button>🔎 Tìm</button>
</form>

<?php if ($table): ?>

<h3><?= $edit ? '✏️ Sửa dữ liệu' : '➕ Thêm dữ liệu' ?></h3>

<form method="post">
<input type="hidden" name="action" value="save">

<?php if ($edit): ?>
<input type="hidden" name="edit_id" value="<?=esc($edit[$pk])?>">
<?php endif; ?>

<div class="form-grid">

<?php foreach ($cols as $c): ?>

<?php
$field = $c['Field'];
$value = $edit[$field] ?? '';
$readonly = ($edit && $field === $pk);
?>

<div class="field">

<label>
<?=esc($field)?>
<?php if ($c['Key'] === 'PRI'): ?> 🔑<?php endif; ?>
</label>

<?php if (stripos($c['Type'],'text') !== false ||
          stripos($c['Type'],'blob') !== false): ?>

<textarea
name="data[<?=esc($field)?>]"
rows="3"
<?= $readonly ? 'readonly' : '' ?>
><?=esc($value)?></textarea>

<?php else: ?>

<input
name="data[<?=esc($field)?>]"
value="<?=esc($value)?>"
<?= $readonly ? 'readonly' : '' ?>
>

<?php endif; ?>

</div>

<?php endforeach; ?>

</div>

<br>

<button type="submit">
<?= $edit ? '💾 Lưu thay đổi' : '➕ Thêm dữ liệu' ?>
</button>

<?php if ($edit): ?>
<a class="btn" href="?table=<?=urlencode($table)?>">Hủy</a>
<?php endif; ?>

</form>

<hr style="border-color:#29243a;margin:25px 0">

<h3>📊 Dữ liệu</h3>

<div class="table-wrap">

<table>

<thead>
<tr>

<?php foreach ($cols as $c): ?>
<th><?=esc($c['Field'])?></th>
<?php endforeach; ?>

<th>Thao tác</th>

</tr>
</thead>

<tbody>

<?php if ($data && $data->num_rows): ?>

<?php while ($row = $data->fetch_assoc()): ?>

<tr>

<?php foreach ($cols as $c): ?>
<td><?=esc($row[$c['Field']] ?? '')?></td>
<?php endforeach; ?>

<td class="actions">

<?php if ($pk): ?>

<a class="btn edit"
href="?table=<?=urlencode($table)?>&edit=<?=urlencode($row[$pk])?>">
✏️ Sửa
</a>

<a class="btn delete"
href="?table=<?=urlencode($table)?>&delete=1&id=<?=urlencode($row[$pk])?>"
onclick="return confirm('Bạn chắc chắn muốn xóa bản ghi này?')">
🗑️ Xóa
</a>

<?php else: ?>

<span style="color:#888">Không có khóa chính</span>

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="<?=count($cols)+1?>">
Không có dữ liệu.
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</div>

</main>
</div>

</body>
</html>
