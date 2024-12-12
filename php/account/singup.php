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
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $newUserId = $userRepository->registerUser($nickname, $email, $password);

        if ($newUserId > 0) {
            $_SESSION['user_id'] = $newUserId; // Сохраняем userId в сессии
            echo json_encode([
                'success' => true,
                'message' => 'Пользователь успешно зарегистрирован',
                'userId' => $newUserId
            ]);
        } elseif ($newUserId === -1) {
            echo json_encode(['success' => false, 'message' => 'Никнейм уже используется']);
        } elseif ($newUserId === -2) {
            echo json_encode(['success' => false, 'message' => 'Email уже используется']);
        }
    } catch (Exception $e) {
        error_log('Ошибка регистрации: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Ошибка сервера.']);
    }
}
?>
