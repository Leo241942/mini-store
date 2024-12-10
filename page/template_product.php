<?php
require_once '../php/classes/ProductRepository.php';
require_once '../php/connect.php';


$product_id = isset($_GET['product_id']) && is_numeric($_GET['product_id']) ? (int)$_GET['product_id'] : null;

if (!$product_id) {
    echo "Вы ошиблись. Такого продукта нет.";
    return; // Останавливаем выполнение дальнейшего кода
}
$ProductRepository = new ProductRepository($pdo);
$productInfo = $ProductRepository->GetProductDetails($product_id);


// Проверяем, есть ли результат
if ($productInfo) {
    $product_name = $productInfo['product_name'] ?? 'No name';
    $rating = $productInfo['rating'] ?? 'No rating';
    $short_description = $productInfo['short_description'] ?? 'No rating';
    $full_description = $productInfo['full_description'] ?? 'No rating';
    $article_number = $productInfo['article_number'] ?? 'No rating';
    $stock_quantity = $productInfo['stock_quantity'] ?? 'No rating';
    $category_name = $productInfo['category_name'] ?? 'No ';
    $tags = $productInfo['tags'] ?? 'No tag';
} 
else {
    echo "Product not found.";
}


$productImages = $ProductRepository->GetProductImages($product_id);
$productSizes = $ProductRepository->GetProductSizes($product_id);
$productColors = $ProductRepository->GetProductColors($product_id);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="../styles/template_product/template_product.css">
    <link rel="stylesheet" href="../styles/root/root.css">

    <!-- Component Styles -->
    <link rel="stylesheet" href="../components/header/header.css">
    <link rel="stylesheet" href="../components/footer/footer.css">
    <link rel="stylesheet" href="../components/item_arrivals/item_arrivals.css">
</head>

<body>
    <!-- Header Component -->
    <?php require_once "../components/header/header.php"; ?>

    <main>
        <section class="product">
            <div class="container_width">
                <!-- Container for fixed width -->
                <div class="product-info">
                    <div class="info-container">

                        <!-- Product Image Slider -->
                        <div class="slider">
                            <div class="sliders">
                                <!-- Individual slides -->

                                <?php

                                        if (!empty($productImages)) {
                                            $countImg = 0;  // Инициализация переменной до цикла
                                            foreach ($productImages as $image) {
                                                if (isset($image['image_url'])) {
                                                    $imageUrl = $image['image_url'];
                                                    echo "<div class='slide' data-index='" . htmlspecialchars($countImg) . "'><img src='../image/" . htmlspecialchars($imageUrl) . "' alt='Image'></div>";
                                                    $countImg++;  // Увеличиваем счетчик после вывода изображения
                                                }
                                            }
                                        }

                                ?>
                         
                            </div>
                            <div class="current_slide"></div>
                        </div>

                        <!-- Product Details -->
                        <div class="characteristics">
                        <h1 class="title"><?= htmlspecialchars($product_name) ?></h1>

                            <!-- Rating -->
                            <div class="rating">
                                <div class="stars">
                                    <ul>
                                        <li><i class="ri-star-fill"></i></li>
                                        <li><i class="ri-star-fill"></i></li>
                                        <li><i class="ri-star-fill"></i></li>
                                        <li><i class="ri-star-fill"></i></li>
                                        <li><i class="ri-star-fill"></i></li>
                                    </ul>
                                </div>
                                <p class="rating-count"><?= htmlspecialchars($rating) ?></p>
                            </div>

                            <!-- Description -->
                            <p class="description">
                            <?= htmlspecialchars($short_description) ?>
                            </p>

                            <!-- Controls: Color, Size, and Add to Cart -->
                            <div class="controls">
                                <!-- Color Selector -->
                                <!-- Color Selector -->
                                    <div class="control-element color">
                                        <p class="title">Color</p>
                                        <div class="controls-container">
                                            <?php
                                            // Проверяем, если получены цвета для продукта
                                            if (!empty($productColors)) {
                                                foreach ($productColors as $color) {
                                                    if (isset($color['color_name'])) {
                                                        // Выводим каждую кнопку для цвета
                                                        echo "<button class='color-btn'>" . htmlspecialchars($color['color_name']) . "</button>";
                                                    }
                                                }
                                            } else {
                                                echo "<p>No color information available</p>";
                                            }
                                            ?>
                                        </div>
                                    </div>


                                <!-- Size Selector -->
                                <div class="control-element size">
                                    <p class="title">Size</p>
                                    <div class="controls-container">
                                        <?php
                                            foreach ($productSizes as $size) {
                                                if (isset($size['size_name'])) {
                                                    echo "<button class = "."size-btn>" . htmlspecialchars($size['size_name']) . "</button>";
                                                } else {
                                                    echo "<li>Size information not available</li>";
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>

                                <!-- Add to Cart Section -->
                                <div class="control-element cart">
                                    <p class="title"><?= htmlspecialchars($stock_quantity) ?> in stock</p>
                                    <div class="controls-container">
                                        <div class="count-container">
                                            <button class="increase">+</button>
                                            <input type="number" value="1" min="1" max="<?= (int) htmlspecialchars($stock_quantity) ?>" id = "quantity_product">
                                            <button class="decrease">−</button>
                                        </div>
                                        <button id="add_to_cart" <?= ($stock_quantity < 1) ? 'disabled' : '' ?>>Add to Cart</button>
                                        <button><i class="ri-heart-add-line"></i></button>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Properties -->
                            <ul class="properties">
                                <li><strong>Art:</strong> <?= htmlspecialchars($article_number) ?></li>
                                <li><strong>Category:</strong> <?= htmlspecialchars($category_name) ?></li>
                                <li><strong>Tags:</strong><?= htmlspecialchars($tags) ?></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tabs Section -->
                    <div class="tabs_container">
                        <div class="tabs_controls">
                            <button data-tab="description" class="active">Description</button>
                            <button data-tab="reviews">Customer Reviews</button>
                        </div>
                        <div class="tabs">
                            <div class="tab_content tab_description active">
                                <p class="title">Product Description</p>
                                <div class="product_description">
                                    <p><?= htmlspecialchars($full_description) ?></p>
                                </div>
                            </div>
                            <div class="tab_content tab_reviews">
                                <p class="title">Customer Reviews</p>
                                <form action="#">
                                    <label><input type="text"></label>
                                    <label><input type="text"></label>
                                    <label><input type="text"></label>
                                    <button>Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End of Product -->
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Component -->
    <?php require_once "../components/footer/footer.html"; ?>

    <script src="../js/template_product/template_product.js"></script>
    <script src="../js/index/index.js"></script>
</body>

</html>
