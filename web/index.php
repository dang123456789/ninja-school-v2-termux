<?php
/* =========================================================
   NRO VIP PANEL
   MariaDB + PHP | Termux
   Database: nso
   ========================================================= */

mysqli_report(MYSQLI_REPORT_OFF);

$socket = getenv('PREFIX') . '/var/run/mysqld/mysqld.sock';
if (!is_dir(dirname($socket))) {
    $socket = '/data/data/com.termux/files/usr/var/run/mysqld/mysqld.sock';
}

$db = @new mysqli('localhost', 'root', '', 'nso', 3306, $socket);

if ($db->connect_error) {
    die("
    <div style='font-family:Arial;background:#090b12;color:#fff;padding:30px'>
        <h2 style='color:#ff4d6d'>❌ Không thể kết nối MariaDB</h2>
        <p>{$db->connect_error}</p>
    </div>");
}

$db->set_charset('utf8mb4');

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$tables = [];
$r = $db->query("SHOW TABLES");
while ($r && $row = $r->fetch_row()) {
    $tables[] = $row[0];
}

$table = $_GET['table'] ?? ($tables[0] ?? '');
$search = trim($_GET['search'] ?? '');

if (!in_array($table, $tables, true)) {
    $table = $tables[0] ?? '';
}

$columns = [];
$primary = '';

if ($table !== '') {
    $safeTable = str_replace('`', '``', $table);
    $r = $db->query("SHOW COLUMNS FROM `$safeTable`");

    while ($r && $row = $r->fetch_assoc()) {
        $columns[] = $row;
        if (($row['Key'] ?? '') === 'PRI') {
            $primary = $row['Field'];
        }
    }
}

/* =========================
   ADD
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $tablePost = $_POST['table'] ?? '';

    if (in_array($tablePost, $tables, true)) {
        $safe = str_replace('`', '``', $tablePost);
        $fields = [];
        $values = [];
        $types = '';
        $params = [];

        foreach ($columns as $c) {
            $field = $c['Field'];

            if (!array_key_exists($field, $_POST)) {
                continue;
            }

            if ($c['Extra'] === 'auto_increment' && $_POST[$field] === '') {
                continue;
            }

            $fields[] = "`" . str_replace('`', '``', $field) . "`";
            $values[] = '?';
            $types .= 's';
            $params[] = $_POST[$field];
        }

        if ($fields) {
            $sql = "INSERT INTO `$safe` (" . implode(',', $fields) .
                   ") VALUES (" . implode(',', $values) . ")";

            $stmt = $db->prepare($sql);

            if ($stmt) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $stmt->close();
            }
        }

        header("Location: ?table=" . urlencode($tablePost) . "&ok=added");
        exit;
    }
}

/* =========================
   DELETE
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if ($primary !== '' && isset($_POST['id'])) {
        $safeTable = str_replace('`', '``', $table);
        $safePrimary = str_replace('`', '``', $primary);

        $stmt = $db->prepare(
            "DELETE FROM `$safeTable` WHERE `$safePrimary` = ? LIMIT 1"
        );

        if ($stmt) {
            $id = $_POST['id'];
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: ?table=" . urlencode($table) . "&ok=deleted");
    exit;
}

/* =========================
   UPDATE
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    if ($primary !== '' && isset($_POST['_primary_value'])) {

        $safeTable = str_replace('`', '``', $table);
        $safePrimary = str_replace('`', '``', $primary);

        $set = [];
        $types = '';
        $params = [];

        foreach ($columns as $c) {
            $field = $c['Field'];

            if ($field === $primary || !array_key_exists($field, $_POST)) {
                continue;
            }

            $set[] = "`" . str_replace('`', '``', $field) . "` = ?";
            $types .= 's';
            $params[] = $_POST[$field];
        }

        if ($set) {
            $types .= 's';
            $params[] = $_POST['_primary_value'];

            $sql = "UPDATE `$safeTable` SET " .
                   implode(',', $set) .
                   " WHERE `$safePrimary` = ? LIMIT 1";

            $stmt = $db->prepare($sql);

            if ($stmt) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    header("Location: ?table=" . urlencode($table) . "&ok=updated");
    exit;
}

/* =========================
   DATA
   ========================= */
$rows = [];
$totalRows = 0;

if ($table !== '') {
    $safeTable = str_replace('`', '``', $table);

    $count = $db->query("SELECT COUNT(*) AS c FROM `$safeTable`");
    if ($count) {
        $totalRows = (int)$count->fetch_assoc()['c'];
    }

    $sql = "SELECT * FROM `$safeTable`";

    if ($search !== '' && $columns) {
        $parts = [];

        foreach ($columns as $c) {
            $f = str_replace('`', '``', $c['Field']);
            $parts[] = "CAST(`$f` AS CHAR) LIKE '%" .
                       $db->real_escape_string($search) . "%'";
        }

        $sql .= " WHERE " . implode(' OR ', $parts);
    }

    $sql .= " LIMIT 200";

    $r = $db->query($sql);

    while ($r && $row = $r->fetch_assoc()) {
        $rows[] = $row;
    }
}

$totalDatabaseRows = 0;

foreach ($tables as $t) {
    $safe = str_replace('`', '``', $t);
    $r = $db->query("SELECT COUNT(*) c FROM `$safe`");

    if ($r) {
        $totalDatabaseRows += (int)$r->fetch_assoc()['c'];
    }
}

$ok = $_GET['ok'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width,initial-scale=1,maximum-scale=1">

<title>NRO VIP PANEL</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
      rel="stylesheet">

<style>
* {
    box-sizing:border-box;
}

body {
    margin:0;
    font-family:'Be Vietnam Pro',Arial,sans-serif;
    background:
        radial-gradient(circle at top left,#25104b 0,#0a0b12 35%),
        #08090f;
    color:#f5f7ff;
}

body:before {
    content:"";
    position:fixed;
    inset:0;
    pointer-events:none;
    background:
        radial-gradient(circle at 80% 10%,rgba(0,200,255,.12),transparent 25%),
        radial-gradient(circle at 20% 80%,rgba(160,0,255,.10),transparent 30%);
}

.sidebar {
    position:fixed;
    left:0;
    top:0;
    bottom:0;
    width:255px;
    padding:22px 14px;
    background:rgba(12,13,22,.82);
    backdrop-filter:blur(20px);
    border-right:1px solid rgba(255,255,255,.08);
    overflow-y:auto;
    z-index:5;
}

.logo {
    padding:10px 12px 22px;
    font-size:23px;
    font-weight:800;
    letter-spacing:.5px;
}

.logo span {
    color:#b55cff;
}

.status {
    padding:10px 12px;
    margin-bottom:15px;
    border-radius:12px;
    background:rgba(0,255,170,.07);
    border:1px solid rgba(0,255,170,.15);
    color:#62ffc6;
    font-size:12px;
}

.table-link {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:11px 12px;
    margin:4px 0;
    border-radius:11px;
    color:#aeb3c5;
    text-decoration:none;
    font-size:13px;
    transition:.2s;
}

.table-link:hover,
.table-link.active {
    color:white;
    background:linear-gradient(
        90deg,
        rgba(157,78,255,.25),
        rgba(0,210,255,.10)
    );
}

.badge {
    font-size:10px;
    padding:3px 7px;
    border-radius:20px;
    background:rgba(255,255,255,.07);
    color:#8e94a8;
}

.main {
    margin-left:255px;
    padding:25px;
}

.topbar {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    margin-bottom:25px;
}

.title h1 {
    margin:0;
    font-size:26px;
}

.title p {
    margin:5px 0 0;
    color:#858ba0;
    font-size:13px;
}

.cards {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
    margin-bottom:20px;
}

.card {
    padding:20px;
    border-radius:18px;
    background:rgba(255,255,255,.055);
    border:1px solid rgba(255,255,255,.08);
    backdrop-filter:blur(16px);
}

.card small {
    color:#858ba0;
}

.card strong {
    display:block;
    margin-top:8px;
    font-size:25px;
}

.online {
    color:#55ffc2;
}

.panel {
    border:1px solid rgba(255,255,255,.08);
    background:rgba(15,16,27,.72);
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 20px 60px rgba(0,0,0,.25);
}

.panel-head {
    padding:17px;
    display:flex;
    justify-content:space-between;
    gap:10px;
    flex-wrap:wrap;
    border-bottom:1px solid rgba(255,255,255,.07);
}

.search {
    display:flex;
    gap:8px;
}

input,
button {
    font:inherit;
}

input {
    color:white;
    background:#11131d;
    border:1px solid #282b3a;
    border-radius:10px;
    padding:10px 12px;
    outline:none;
}

input:focus {
    border-color:#9b5cff;
}

button,
.btn {
    border:0;
    border-radius:10px;
    padding:10px 14px;
    cursor:pointer;
    color:white;
    background:linear-gradient(135deg,#8e42ff,#4e8cff);
    text-decoration:none;
}

.btn-danger {
    background:linear-gradient(135deg,#ff3d68,#b51f45);
}

.btn-edit {
    background:linear-gradient(135deg,#00a8ff,#006eff);
}

.table-wrap {
    width:100%;
    overflow:auto;
}

table {
    width:100%;
    border-collapse:collapse;
    min-width:750px;
}

th,
td {
    padding:12px 14px;
    text-align:left;
    border-bottom:1px solid rgba(255,255,255,.06);
    font-size:12px;
    white-space:nowrap;
}

th {
    color:#aeb5cc;
    background:rgba(255,255,255,.025);
}

td {
    color:#dce0ed;
}

.actions {
    display:flex;
    gap:6px;
}

.empty {
    padding:50px;
    text-align:center;
    color:#777d91;
}

.toast {
    position:fixed;
    right:20px;
    top:20px;
    padding:13px 17px;
    border-radius:12px;
    background:#151823;
    border:1px solid rgba(255,255,255,.1);
    box-shadow:0 15px 40px rgba(0,0,0,.4);
    z-index:99;
}

.modal {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.7);
    backdrop-filter:blur(8px);
    z-index:50;
    padding:20px;
    overflow:auto;
}

.modal-box {
    max-width:650px;
    margin:40px auto;
    padding:22px;
    border-radius:18px;
    background:#10121c;
    border:1px solid rgba(255,255,255,.1);
}

.modal-box h2 {
    margin-top:0;
}

.form-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.form-group label {
    display:block;
    color:#999fb1;
    font-size:11px;
    margin-bottom:6px;
}

.form-group input {
    width:100%;
}

.form-actions {
    display:flex;
    justify-content:flex-end;
    gap:8px;
    margin-top:18px;
}

.mobile-menu {
    display:none;
}

@media(max-width:800px) {
    .sidebar {
        transform:translateX(-100%);
        transition:.25s;
    }

    .sidebar.open {
        transform:translateX(0);
    }

    .main {
        margin-left:0;
        padding:15px;
    }

    .mobile-menu {
        display:block;
    }

    .cards {
        grid-template-columns:1fr;
    }

    .topbar {
        align-items:flex-start;
    }

    .form-grid {
        grid-template-columns:1fr;
    }

    .title h1 {
        font-size:21px;
    }
}
</style>
</head>

<body>

<aside class="sidebar" id="sidebar">

    <div class="logo">
        ⚡ <span>NRO</span> VIP
    </div>

    <div class="status">
        ● MariaDB Connected
    </div>

    <?php foreach ($tables as $t): ?>
        <?php
            $safe = str_replace('`','``',$t);
            $cnt = 0;
            $cr = $db->query("SELECT COUNT(*) c FROM `$safe`");
            if ($cr) $cnt = (int)$cr->fetch_assoc()['c'];
        ?>
        <a class="table-link <?= $t === $table ? 'active' : '' ?>"
           href="?table=<?= urlencode($t) ?>">
            <span><?= e($t) ?></span>
            <span class="badge"><?= $cnt ?></span>
        </a>
    <?php endforeach; ?>

</aside>

<main class="main">

    <div class="topbar">
        <div class="title">
            <button class="mobile-menu" onclick="toggleMenu()">☰</button>
            <h1>🎮 NRO VIP PANEL</h1>
            <p>Quản lý máy chủ Ninja School</p>
        </div>

        <button onclick="openModal('addModal')">
            ＋ Thêm dữ liệu
        </button>
    </div>

    <section class="cards">

        <div class="card">
            <small>🗂️ Tổng bảng</small>
            <strong><?= count($tables) ?></strong>
        </div>

        <div class="card">
            <small>📊 Tổng dòng</small>
            <strong><?= number_format($totalDatabaseRows) ?></strong>
        </div>

        <div class="card">
            <small>🟢 Trạng thái</small>
            <strong class="online">ONLINE</strong>
        </div>

    </section>

    <section class="panel">

        <div class="panel-head">

            <div>
                <b>📁 <?= e($table ?: 'Chưa chọn bảng') ?></b>
                <span style="color:#777;margin-left:8px">
                    <?= number_format($totalRows) ?> dòng
                </span>
            </div>

            <form class="search" method="get">
                <input type="hidden"
                       name="table"
                       value="<?= e($table) ?>">

                <input name="search"
                       value="<?= e($search) ?>"
                       placeholder="🔍 Tìm kiếm...">

                <button>Search</button>
            </form>

        </div>

        <?php if ($rows && $columns): ?>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <?php foreach ($columns as $c): ?>
                            <th><?= e($c['Field']) ?></th>
                        <?php endforeach; ?>

                        <?php if ($primary): ?>
                            <th>THAO TÁC</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($rows as $row): ?>

                    <tr>

                        <?php foreach ($columns as $c): ?>

                            <?php
                                $field = $c['Field'];
                                $value = $row[$field] ?? '';
                            ?>

                            <td><?= e($value) ?></td>

                        <?php endforeach; ?>

                        <?php if ($primary): ?>

                        <td>

                            <div class="actions">

                                <button class="btn-edit"
                                    onclick='editRow(<?= json_encode($row, JSON_UNESCAPED_UNICODE) ?>)'>
                                    ✏️
                                </button>

                                <form method="post"
                                      onsubmit="return confirm('Xóa dữ liệu này?')">

                                    <input type="hidden"
                                           name="action"
                                           value="delete">

                                    <input type="hidden"
                                           name="id"
                                           value="<?= e($row[$primary]) ?>">

                                    <button class="btn-danger">
                                        🗑️
                                    </button>

                                </form>

                            </div>

                        </td>

                        <?php endif; ?>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <?php else: ?>

            <div class="empty">
                Không có dữ liệu để hiển thị.
            </div>

        <?php endif; ?>

    </section>

</main>


<!-- ADD MODAL -->
<div class="modal" id="addModal">

    <div class="modal-box">

        <h2>➕ Thêm dữ liệu</h2>

        <form method="post">

            <input type="hidden"
                   name="action"
                   value="add">

            <input type="hidden"
                   name="table"
                   value="<?= e($table) ?>">

            <div class="form-grid">

            <?php foreach ($columns as $c): ?>

                <?php
                    if ($c['Extra'] === 'auto_increment') continue;
                ?>

                <div class="form-group">

                    <label><?= e($c['Field']) ?></label>

                    <input name="<?= e($c['Field']) ?>"
                           placeholder="<?= e($c['Field']) ?>">

                </div>

            <?php endforeach; ?>

            </div>

            <div class="form-actions">

                <button type="button"
                        onclick="closeModal('addModal')">
                    Hủy
                </button>

                <button type="submit">
                    💾 Lưu
                </button>

            </div>

        </form>

    </div>

</div>


<!-- EDIT MODAL -->
<div class="modal" id="editModal">

    <div class="modal-box">

        <h2>✏️ Chỉnh sửa</h2>

        <form method="post">

            <input type="hidden"
                   name="action"
                   value="update">

            <input type="hidden"
                   name="_primary_value"
                   id="_primary_value">

            <div class="form-grid">

            <?php foreach ($columns as $c): ?>

                <div class="form-group">

                    <label><?= e($c['Field']) ?></label>

                    <input name="<?= e($c['Field']) ?>"
                           id="edit_<?= e($c['Field']) ?>">

                </div>

            <?php endforeach; ?>

            </div>

            <div class="form-actions">

                <button type="button"
                        onclick="closeModal('editModal')">
                    Hủy
                </button>

                <button type="submit">
                    💾 Cập nhật
                </button>

            </div>

        </form>

    </div>

</div>


<?php if ($ok): ?>
<div class="toast" id="toast">
    <?php
        echo match($ok) {
            'added'   => '✅ Thêm dữ liệu thành công',
            'updated' => '✅ Cập nhật thành công',
            'deleted' => '🗑️ Xóa dữ liệu thành công',
            default   => '✅ Thành công'
        };
    ?>
</div>
<?php endif; ?>


<script>
function openModal(id) {
    document.getElementById(id).style.display = 'block';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function toggleMenu() {
    document.getElementById('sidebar').classList.toggle('open');
}

function editRow(row) {

    <?php foreach ($columns as $c): ?>

    document.getElementById(
        'edit_<?= e($c['Field']) ?>'
    ).value = row['<?= e($c['Field']) ?>'] ?? '';

    <?php endforeach; ?>

    document.getElementById('_primary_value').value =
        row['<?= e($primary) ?>'] ?? '';

    openModal('editModal');
}

setTimeout(function() {
    const t = document.getElementById('toast');
    if (t) t.remove();
}, 3000);
</script>

</body>
</html>
