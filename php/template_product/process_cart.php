<?php
// process_cart.php
session_start();


// Заменяем $_SESSION['user_id'] на фиксированное значение (например, 2)
$user_id = 2; // Для тестирования с ID 2

require_once '../connect.php';
require_once '../classes/CartRepository.php'; // Подключаем класс CartRepository

// Получаем параметры из POST запроса
$colorId = isset($_POST['color']) ? (int)$_POST['color'] : null;
$sizeId = isset($_POST['size']) ? (int)$_POST['size'] : null;
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;

// Проверка на валидность переданных данных
if ($colorId && $sizeId && $productId && $userId) {
    // Создаем экземпляр CartRepository
    $cartRepository = new CartRepository($pdo);
    
    // Вызов метода addToCart для добавления товара в корзину
    $result = $cartRepository->addToCart($userId, $productId, $colorId, $sizeId, $quantity);
    
    // Возвращаем результат на фронтенд
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при добавлении товара в корзину.']);
}


