<?php
// Запускаем сессию
session_start();
session_unset();
session_destroy();

// Отправка уведомления
echo json_encode([
    'success' => true,
    'message' => 'Вы успешно вышли из системы.'
]);

header("Location: ../page/account.php"); 
exit();
?>
