<?php
require_once '../php/classes/ProductRepository.php';
require_once '../php/classes/CartRepository.php';
require_once '../php/connect.php';

// Создаем экземпляр класса ProductRepository
$ProductRepository = new ProductRepository($pdo);

// Получаем 8 карточек товаров для отображения на главной странице
$products = $ProductRepository->getProductCards(8);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/index/index.css">
    <link rel="stylesheet" href="../styles/root/root.css">
    <!-- styles components -->
    <link rel="stylesheet" href="../components/header/header.css">
    <link rel="stylesheet" href="../components/footer/footer.css">
    <link rel="stylesheet" href="../components/item_arrivals/item_arrivals.css">
</head>

<body>
    <?php require_once "../components/header/header.php"; ?>

    <main>
        <section class="start_shopping">
            <div class="container_width">
                <div class="promo_banner">
                    <p class="title">Street Wears</p>
                    <p class="description">High quality cool t-shirts for street fashion</p>
                    <button class="btn_StartShopoing">start shopping <i class="ri-arrow-right-line"></i></button>
                </div>
            </div>
        </section>

        <section class="our_advantages">
            <div class="container_width">
                <div class="container_adantages">
                    <?php 
                    $advantages = [
                        ['icon' => 'ri-shopping-cart-2-line', 'title' => 'Free Delivery', 'desc' => 'Lorem ipsum dolor sit amet.'],
                        ['icon' => 'ri-shield-check-line', 'title' => '100% Secure Payment', 'desc' => 'Lorem ipsum dolor sit amet.'],
                        ['icon' => 'ri-medal-line', 'title' => 'Quality Guarantee', 'desc' => 'Lorem ipsum dolor sit amet.'],
                        ['icon' => 'ri-money-dollar-circle-line', 'title' => 'Daily Offer', 'desc' => 'Lorem ipsum dolor sit amet.']
                    ];
                    foreach ($advantages as $adv) {
                        echo "<div class='item_adantages'>
                                <i class='{$adv['icon']}'></i>
                                <p class='title'>{$adv['title']}</p>
                                <p class='description'>{$adv['desc']}</p>
                              </div>";
                    }
                    ?>
                </div>
            </div>
        </section>

        <section class="new_arrivals">
            <div class="container_width">
                <p class="title">New Arrivals</p>
                <div class="listing_container">
                    <div class="listing">
                        <?php
                        // Отображаем товары
                        foreach ($products as $product) {
                            $cover = $product['cover']; 
                            $tags = $product['tags']; 
                            $name = $product['product_name'];
                            $price = $product['price'];
                            $discountPrice = $product['discount_price']; 
                            $productId = $product['id']; 
                            require "../components/item_arrivals/item_arrivals.php";
                        }
                        ?>
                    </div>
                    <a class="btn_allProduct" href="catalog.php">View All Products</a>
                </div>
            </div>
        </section>

        <section class="category">
            <?php 
            $categories = [
                ['img' => 'category1.jpg', 'title' => 'Printed T-Shirts'],
                ['img' => 'category2.jpg', 'title' => 'Mono T-Shirts'],
                ['img' => 'category3.jpg', 'title' => 'Graphic Hoodies']
            ];
            foreach ($categories as $category) {
                echo "<a href='#' class='category_item'>
                        <img src='../image/{$category['img']}' alt=''>
                        <p class='title'>{$category['title']}</p>
                      </a>";
            }
            ?>
        </section>
    </main>

    <?php require_once "../components/footer/footer.php"; ?>

    <script src="../js/index/index.js"></script>
</body>

</html>
