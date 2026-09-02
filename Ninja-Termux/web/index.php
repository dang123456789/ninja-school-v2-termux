<?php
mysqli_report(MYSQLI_REPORT_OFF);

$socket = '/data/data/com.termux/files/usr/var/run/mysqld/mysqld.sock';
$db = new mysqli('localhost', 'root', '', 'nso', 0, $socket);

if ($db->connect_errno) {
    die("Lỗi MariaDB: " . htmlspecialchars($db->connect_error));
}
$db->set_charset('utf8mb4');

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$tables = [];
$q = $db->query("SHOW TABLES");
while ($r = $q->fetch_row()) $tables[] = $r[0];

$table = $_GET['table'] ?? '';
if ($table === '' || !in_array($table, $tables, true))
    $table = $tables[0] ?? '';

$safe = $db->real_escape_string($table);
$columns = [];

if ($table) {
    $q = $db->query("SHOW COLUMNS FROM `$safe`");
    while ($r = $q->fetch_assoc()) $columns[] = $r;
}

$rows = [];
if ($table) {
    $q = $db->query("SELECT * FROM `$safe` LIMIT 200");
    if ($q) {
        while ($r = $q->fetch_assoc()) $rows[] = $r;
    }
}

$totalRows = 0;
foreach ($tables as $t) {
    $s = $db->real_escape_string($t);
    $q = $db->query("SELECT COUNT(*) c FROM `$s`");
    if ($q) $totalRows += (int)$q->fetch_assoc()['c'];
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>NSO VIP PANEL</title>
<style>
*{box-sizing:border-box}
body{margin:0;background:#070b12;color:#eaf2ff;font-family:Arial,sans-serif}
header{padding:18px 22px;background:#0d1422;border-bottom:1px solid #1d3048;position:sticky;top:0;z-index:5}
.logo{font-size:25px;font-weight:bold;color:#00eaff}
.sub{color:#8291a8;margin-top:5px;font-size:13px}
.layout{display:flex;min-height:calc(100vh - 76px)}
aside{width:240px;background:#0a101a;border-right:1px solid #1b2a3d;padding:15px;overflow:auto}
aside h3{color:#00eaff;font-size:13px}
aside a{display:block;padding:10px 12px;margin:4px 0;border-radius:8px;color:#b9c6d8;text-decoration:none}
aside a:hover,aside a.active{background:#10253b;color:#00eaff}
main{flex:1;padding:20px;overflow:auto}
.cards{display:flex;gap:12px;flex-wrap:wrap}
.card{background:#0e1725;border:1px solid #20334b;border-radius:12px;padding:16px;min-width:150px}
.num{font-size:23px;font-weight:bold;color:#00eaff;margin-top:5px}
.ok{color:#46ff9b}
h2{margin-top:25px}
.tablebox{overflow:auto;border:1px solid #20334b;border-radius:10px}
table{border-collapse:collapse;width:100%;min-width:650px;background:#0b121d}
th,td{padding:9px 11px;border-bottom:1px solid #1c2a3d;text-align:left;white-space:nowrap}
th{background:#111e30;color:#00eaff;position:sticky;top:0}
tr:hover td{background:#0f1b2b}
.search{margin:18px 0}
.search input{width:100%;max-width:500px;padding:11px;border-radius:8px;border:1px solid #263b55;background:#0b121d;color:white}
.badge{display:inline-block;padding:5px 9px;border-radius:20px;background:#102c25;color:#46ff9b;font-size:12px}
@media(max-width:700px){
 aside{width:170px}
 main{padding:12px}
 .card{min-width:120px;padding:12px}
}
</style>
</head>
<body>

<header>
<div class="logo">⚡ NSO VIP PANEL</div>
<div class="sub">MariaDB · nso · <span class="badge">ONLINE</span></div>
</header>

<div class="layout">
<aside>
<h3>DATABASE</h3>
<?php foreach($tables as $t): ?>
<a class="<?= $t===$table?'active':'' ?>"
href="?table=<?=urlencode($t)?>"><?=e($t)?></a>
<?php endforeach; ?>
</aside>

<main>
<div class="cards">
<div class="card">📦 Bảng<div class="num"><?=count($tables)?></div></div>
<div class="card">🗄️ Database<div class="num">nso</div></div>
<div class="card">📊 Tổng dòng<div class="num"><?=$totalRows?></div></div>
<div class="card">🟢 Trạng thái<div class="num ok">ONLINE</div></div>
</div>

<?php if($table): ?>
<h2>📋 <?=e($table)?></h2>

<div class="search">
<input id="search" placeholder="🔎 Tìm trong bảng...">
</div>

<div class="tablebox">
<table id="data">
<thead>
<tr>
<?php foreach($columns as $c): ?>
<th><?=e($c['Field'])?></th>
<?php endforeach; ?>
</tr>
</thead>
<tbody>
<?php foreach($rows as $row): ?>
<tr>
<?php foreach($columns as $c): ?>
<td><?=e($row[$c['Field']] ?? '')?></td>
<?php endforeach; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<?php if(!$rows): ?>
<p class="sub">Bảng này chưa có dữ liệu.</p>
<?php endif; ?>

<?php endif; ?>
</main>
</div>

<script>
document.getElementById('search')?.addEventListener('input',function(){
 let v=this.value.toLowerCase();
 document.querySelectorAll('#data tbody tr').forEach(r=>{
   r.style.display=r.innerText.toLowerCase().includes(v)?'':'none';
 });
});
</script>
</body>
</html>
