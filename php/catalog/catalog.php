<?php
require_once '../classes/ProductRepository.php';
require_once '../connectphp';

$productRepository = new ProductRepository($pdo);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$brand = isset($_GET['brand']) ? $_GET['brand'] : null;
$category = isset($_GET['category']) ? $_GET['category'] : null;
$minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'ASC'; // По умолчанию сортировка по возрастанию

// Параметры пагинации
$limit = 10;
$offset = ($page - 1) * $limit;

// Получаем фильтрованные товары
$products = $productRepository->getFilteredProducts($limit, $offset, $sort, $brand, $category, $minPrice, $maxPrice);

// Подсчитываем общее количество товаров для пагинации
$totalProducts = count($productRepository->getFilteredProducts(1000, 0, $sort, $brand, $category, $minPrice, $maxPrice));
$totalPages = ceil($totalProducts / $limit);

// Отправляем данные в формате JSON
header('Content-Type: application/json');
echo json_encode([
    'products' => $products,
    'totalPages' => $totalPages
]);
?>
