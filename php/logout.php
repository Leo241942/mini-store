<?php
// Запускаем сессию
session_start();
session_unset();
session_destroy();

header("Location: ../page/account.php"); 
exit();
?>
