<?php
require_once '../php/classes/ShopRepository.php';
require_once '../php/connect.php';

// Создаем экземпляр класса ShopRepository
$shopRepository = new ShopRepository($pdo);

// Получаем текущую страницу из параметров запроса (по умолчанию страница 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Количество товаров на странице
$perPage = 8;

// Получаем товары для текущей страницы
$products = $shopRepository->getPaginatedProducts($page, $perPage);

// Получаем общее количество товаров
$totalProducts = $shopRepository->getTotalProductCount();

// Вычисляем общее количество страниц
$totalPages = ceil($totalProducts / $perPage);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog</title>
    <link rel="stylesheet" href="../styles/catalog/catalog.css">
    <link rel="stylesheet" href="../styles/root/root.css">
    <link rel="stylesheet" href="../components/header/header.css">
    <link rel="stylesheet" href="../components/footer/footer.css">
    <link rel="stylesheet" href="../components/item_arrivals/item_arrivals.css">
</head>

<body>
    <?php require_once "../components/header/header.php"; ?>

    <main>
        <section class="catalog">
            <div class="container_width">
                <div class="listing_container">
                    <div class="header_listing">
                        <p class="showing_result">Showing <?= (($page - 1) * $perPage) + 1 ?>–<?= min($page * $perPage, $totalProducts) ?> of <?= $totalProducts ?> results</p>

                        <div class="sort">
                            <p>Sort by</p>
                            <select class="product_sort">
                                <option value="default">Default</option>
                                <option value="Price(Low-High)">Price(Low-High)</option>
                                <option value="Price(High-Low)">Price(High-Low)</option>
                            </select>
                        </div>
                    </div>

                    <div class="listing">
                        <?php foreach ($products as $product): ?>
                            <?php
                                      $cover = $product['cover']; 
                                      $tags = $product['tags']; 
                                      $name = $product['product_name'];
                                      $price = $product['price'];
                                      $discountPrice = $product['discount_price']; 
                                      $productId = $product['id']; 
                                require "../components/item_arrivals/item_arrivals.php";
                            ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Пагинация -->
                    <?php echo $shopRepository->generatePagination($page, $totalPages); ?>
                </div>
            </div>
        </section>
    </main>

    <?php require_once "../components/footer/footer.php"; ?>

    <script src="../js/header/header.js"></script>
</body>
</html>
