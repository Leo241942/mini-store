<?php

session_start(); // Стартуем сессию
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../connect.php';
    require_once '../classes/UserRepository.php';

    try {
        $userRepository = new UserRepository($pdo);

        $email = $_POST['email'];
        $password = $_POST['password'];

        $userId = $userRepository->loginUser($email, $password);

        if ($userId > 0) {
            $_SESSION['user_id'] = $userId; // Сохраняем userId в сессии
            echo json_encode([
                'success' => true,
                'message' => 'Пользователь успешно авторизован',
                'userId' => $userId
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Неверный email или пароль']);
        }
    } catch (Exception $e) {
        error_log('Ошибка авторизации: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Ошибка сервера.']);
    }
}
?>
