<?php

session_start(); // Стартуем сессию
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../connect.php';
    require_once '../classes/UserRepository.php';

    try {
        $userRepository = new UserRepository($pdo);

        $nickname = $_POST['nickname'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Хешируем пароль перед сохранением
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Регистрация пользователя
        $userId = $userRepository->registerUser($nickname, $email, $passwordHash);

        if ($userId > 0) {
            $_SESSION['user_id'] = $userId; // Сохраняем userId в сессии
            echo json_encode([
                'success' => true,
                'message' => 'Пользователь успешно зарегистрирован',
                'userId' => $userId
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ошибка регистрации']);
        }
    } catch (Exception $e) {
        error_log('Ошибка регистрации: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Ошибка сервера.']);
    }
}
?>
