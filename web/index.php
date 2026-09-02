<?php
mysqli_report(MYSQLI_REPORT_OFF);

$socket = '/data/data/com.termux/files/usr/var/run/mysqld/mysqld.sock';
$db = new mysqli('localhost','root','','nso',0,$socket);

if ($db->connect_errno) die('MariaDB lỗi: '.htmlspecialchars($db->connect_error));
$db->set_charset('utf8mb4');

function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function qi($v){global $db;return '`'.$db->real_escape_string($v).'`';}

$tables=[];
$r=$db->query("SHOW TABLES");
while($x=$r->fetch_row())$tables[]=$x[0];

$table=$_GET['table']??($tables[0]??'');
if(!in_array($table,$tables,true))$table=$tables[0]??'';

$cols=[];
$pk=[];
if($table){
 $r=$db->query("SHOW COLUMNS FROM ".qi($table));
 while($x=$r->fetch_assoc()){
  $cols[]=$x;
  if($x['Key']==='PRI')$pk[]=$x['Field'];
 }
}

$msg='';
$error='';

if($_SERVER['REQUEST_METHOD']==='POST' && $table){
 $action=$_POST['action']??'';

 if($action==='insert'){
  $fields=[];$values=[];
  foreach($cols as $c){
   $f=$c['Field'];
   if(isset($_POST['data'][$f]) && !($c['Extra']??'')){
    $fields[]=qi($f);
    $values[]="'".$db->real_escape_string($_POST['data'][$f])."'";
   }
  }
  if($fields){
   $sql="INSERT INTO ".qi($table)." (".implode(',',$fields).") VALUES (".implode(',',$values).")";
   if($db->query($sql))$msg='Đã thêm dữ liệu';
   else $error=$db->error;
  }
 }

 if($action==='update'){
  $where=[];$set=[];
  foreach($pk as $f){
   $where[]=qi($f)."='".$db->real_escape_string($_POST['pk'][$f]??'')."'";
  }
  foreach($cols as $c){
   $f=$c['Field'];
   if(isset($_POST['data'][$f]) && !($c['Extra']??'')){
    if(!in_array($f,$pk,true))
     $set[]=qi($f)."='".$db->real_escape_string($_POST['data'][$f])."'";
   }
  }
  if($set && $where){
   $sql="UPDATE ".qi($table)." SET ".implode(',',$set)." WHERE ".implode(' AND ',$where);
   if($db->query($sql))$msg='Đã cập nhật dữ liệu';
   else $error=$db->error;
  }
 }

 if($action==='delete'){
  $where=[];
  foreach($pk as $f)
   $where[]=qi($f)."='".$db->real_escape_string($_POST['pk'][$f]??'')."'";

  if($where){
   $sql="DELETE FROM ".qi($table)." WHERE ".implode(' AND ',$where)." LIMIT 1";
   if($db->query($sql))$msg='Đã xóa dữ liệu';
   else $error=$db->error;
  }
 }
}

$edit=null;
if(isset($_GET['edit']) && $pk){
 $where=[];
 foreach($pk as $f)
  $where[]=qi($f)."='".$db->real_escape_string($_GET['pk'][$f]??'')."'";
 $r=$db->query("SELECT * FROM ".qi($table)." WHERE ".implode(' AND ',$where)." LIMIT 1");
 if($r)$edit=$r->fetch_assoc();
}

$rows=[];
if($table){
 $search=trim($_GET['q']??'');
 $sql="SELECT * FROM ".qi($table);
 if($search!==''){
  $w=[];
  foreach($cols as $c)
   $w[]=qi($c['Field'])." LIKE '%".$db->real_escape_string($search)."%'";
  $sql.=" WHERE ".implode(' OR ',$w);
 }
 $sql.=" LIMIT 200";
 $r=$db->query($sql);
 if($r)while($x=$r->fetch_assoc())$rows[]=$x;
}

$total=0;
foreach($tables as $t){
 $r=$db->query("SELECT COUNT(*) c FROM ".qi($t));
 if($r)$total+=(int)$r->fetch_assoc()['c'];
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>NRO VIP PANEL</title>
<style>
*{box-sizing:border-box}
body{margin:0;background:#050810;color:#edf6ff;font:14px Arial}
header{padding:18px 22px;background:#0b1220;border-bottom:1px solid #20334e;position:sticky;top:0;z-index:10}
.logo{font-size:25px;font-weight:bold;color:#00eaff}
.sub{color:#8493aa;margin-top:5px}
.layout{display:flex;min-height:calc(100vh - 78px)}
aside{width:230px;background:#080e18;border-right:1px solid #1b2a40;padding:14px;overflow:auto}
aside b{color:#00eaff}
aside a{display:block;padding:10px;margin:4px 0;color:#b9c8db;text-decoration:none;border-radius:8px}
aside a:hover,aside a.active{background:#10263c;color:#00eaff}
main{flex:1;padding:20px;min-width:0}
.cards{display:flex;gap:12px;flex-wrap:wrap}
.card{background:#0c1523;border:1px solid #1d334d;border-radius:12px;padding:15px;min-width:145px}
.num{font-size:22px;color:#00eaff;font-weight:bold;margin-top:5px}
.ok{color:#42ff91}
.toolbar{display:flex;gap:8px;flex-wrap:wrap;margin:18px 0}
input,button{border-radius:8px;border:1px solid #29415e;padding:10px;background:#09111d;color:#fff}
input{width:100%}
button{cursor:pointer}
.add{background:#07352b;border-color:#16c98b;color:#5dffbc}
.edit{color:#58d8ff}
.del{color:#ff6b7a}
.box{background:#0a111d;border:1px solid #1d3048;border-radius:12px;padding:15px;margin-top:15px}
.tablewrap{overflow:auto}
table{border-collapse:collapse;width:100%;min-width:700px}
th,td{padding:9px;border-bottom:1px solid #1a2b40;text-align:left;white-space:nowrap}
th{color:#00eaff;background:#101c2c}
tr:hover td{background:#0e1b2a}
.msg{padding:11px;border-radius:8px;margin:12px 0;background:#093728;color:#54ffc0}
.err{background:#3a1017;color:#ff8290}
.formgrid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:10px}
label{color:#92a5bd;font-size:12px}
@media(max-width:650px){aside{width:165px}main{padding:12px}.card{min-width:110px}}
</style>
</head>
<body>

<header>
<div class="logo">⚡ NRO VIP PANEL</div>
<div class="sub">Database: nso · <span class="ok">● ONLINE</span></div>
</header>

<div class="layout">
<aside>
<b>🗄 DATABASE</b>
<?php foreach($tables as $t): ?>
<a class="<?= $t===$table?'active':'' ?>" href="?table=<?=urlencode($t)?>">
📋 <?=e($t)?>
</a>
<?php endforeach;?>
</aside>

<main>

<div class="cards">
<div class="card">📦 Tables<div class="num"><?=count($tables)?></div></div>
<div class="card">📊 Rows<div class="num"><?=$total?></div></div>
<div class="card">🟢 Status<div class="num ok">ONLINE</div></div>
</div>

<?php if($msg):?><div class="msg">✓ <?=e($msg)?></div><?php endif;?>
<?php if($error):?><div class="msg err">✕ <?=e($error)?></div><?php endif;?>

<?php if($table):?>

<div class="toolbar">
<form method="get" style="flex:1">
<input type="hidden" name="table" value="<?=e($table)?>">
<input name="q" value="<?=e($_GET['q']??'')?>" placeholder="🔎 Tìm dữ liệu...">
</form>
<a href="?table=<?=urlencode($table)?>" style="padding:10px">🔄</a>
</div>

<h2>📋 <?=e($table)?></h2>

<div class="box">
<h3>➕ Thêm dữ liệu</h3>
<form method="post">
<input type="hidden" name="action" value="insert">
<div class="formgrid">
<?php foreach($cols as $c): if($c['Extra'])continue;?>
<div>
<label><?=e($c['Field'])?> · <?=e($c['Type'])?></label>
<input name="data[<?=e($c['Field'])?>]" value="">
</div>
<?php endforeach;?>
</div>
<br><button class="add">➕ Thêm</button>
</form>
</div>

<?php if($edit):?>
<div class="box">
<h3>✏️ Sửa dữ liệu</h3>
<form method="post">
<input type="hidden" name="action" value="update">
<?php foreach($pk as $f):?>
<input type="hidden" name="pk[<?=e($f)?>]" value="<?=e($edit[$f])?>">
<?php endforeach;?>
<div class="formgrid">
<?php foreach($cols as $c): if($c['Extra'])continue;?>
<div>
<label><?=e($c['Field'])?></label>
<input name="data[<?=e($c['Field'])?>]" value="<?=e($edit[$c['Field']]??'')?>">
</div>
<?php endforeach;?>
</div>
<br><button class="add">💾 Lưu thay đổi</button>
</form>
</div>
<?php endif;?>

<div class="box">
<div class="tablewrap">
<table>
<thead><tr>
<?php foreach($cols as $c):?><th><?=e($c['Field'])?></th><?php endforeach;?>
<th>THAO TÁC</th>
</tr></thead>
<tbody>
<?php foreach($rows as $row):?>
<tr>
<?php foreach($cols as $c):?><td><?=e($row[$c['Field']]??'')?></td><?php endforeach;?>
<td>
<?php if($pk):
$params=['table'=>$table,'edit'=>1];
foreach($pk as $f)$params['pk'][$f]=$row[$f];
?>
<a class="edit" href="?<?=http_build_query($params)?>">✏️ Sửa</a>

<form method="post" style="display:inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa dòng này?')">
<input type="hidden" name="action" value="delete">
<?php foreach($pk as $f):?>
<input type="hidden" name="pk[<?=e($f)?>]" value="<?=e($row[$f])?>">
<?php endforeach;?>
<button class="del">🗑️</button>
</form>
<?php else:?>
<span class="sub">Không có khóa chính</span>
<?php endif;?>
</td>
</tr>
<?php endforeach;?>
</tbody>
</table>
</div>
</div>

<?php endif;?>
</main>
</div>
</body>
</html>
