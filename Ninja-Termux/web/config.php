<?php
$socket = getenv('PREFIX') . '/var/run/mysqld/mysqld.sock';

$conn = new mysqli(
    'localhost',
    'root',
    '',
    'nso',
    0,
    $socket
);

if ($conn->connect_error) {
    die('Lỗi kết nối MariaDB: ' . htmlspecialchars($conn->connect_error));
}

$conn->set_charset('utf8mb4');
?>
