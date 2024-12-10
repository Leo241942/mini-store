<?php
$host = 'localhost';
$dbname = 'mini_store';
$username = 'root';
$password = '';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Сообщение об успешном подключении
    echo "<script>console.log('Соедение с бд успешно!');</script>";
} catch (PDOException $e) {
    // Сообщение об ошибке в консоль
    $errorMessage = $e->getMessage();
    echo "<script>console.error('Ошибка подключения к бд: " . addslashes($errorMessage) . "');</script>";
}
