<?php
// process_cart.php
session_status() === PHP_SESSION_NONE && session_start();

$user_id = $_SESSION['user_id'];

require_once '../connect.php';
require_once '../classes/CartRepository.php'; // Подключаем класс CartRepository

header('Content-Type: application/json'); // Устанавливаем заголовок для JSON

// Проверяем, является ли user_id числом
if (!is_numeric($user_id)) {
    echo json_encode(['success' => false, 'message' => 'Некорректный ID пользователя. Войдите или зарегистрируйтесь.']);
    exit;
}

// Получаем параметры из POST-запроса
$colorId = isset($_POST['color']) ? (int)$_POST['color'] : null;
$sizeId = isset($_POST['size']) ? (int)$_POST['size'] : null;
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;

// Проверка на валидность переданных данных
if ($colorId && $sizeId && $productId && $userId) {
    $cartRepository = new CartRepository($pdo);
    
    // Вызов метода addToCart для добавления товара в корзину
    $result = $cartRepository->addToCart($userId, $productId, $colorId, $sizeId, $quantity);

    // Успешный ответ сервера
    echo json_encode($result);
    exit;
}

// Ответ в случае некорректных данных
echo json_encode(['success' => false, 'message' => 'Ошибка при добавлении товара в корзину.']);
exit;
