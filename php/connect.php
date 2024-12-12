<?php
$host = 'localhost';
$dbname = 'mini_store';
$username = 'root';
$password = '';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log('Ошибка подключения к БД: ' . $e->getMessage());
    die(json_encode(['success' => false, 'message' => 'Ошибка подключения к базе данных.']));
}
?>
