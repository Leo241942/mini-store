<?php
require_once '../php/classes/ProductRepository.php';
require_once '../php/connect.php';

// Запускаем сессию
session_start();

// Проверяем авторизацию пользователя
$user_id = $_SESSION['user_id'] ?? null;

// Получаем и проверяем product_id из GET-параметра
$product_id = isset($_GET['product_id']) && is_numeric($_GET['product_id']) ? (int)$_GET['product_id'] : null;
if (!$product_id) {
    echo "Invalid product ID.";
    return;
}

// Создаем репозиторий продукта
$ProductRepository = new ProductRepository($pdo);
$productInfo = $ProductRepository->GetProductDetails($product_id);

if (!$productInfo) {
    echo "Product not found.";
    return;
}

// Присваиваем значения с дефолтами
$fields = [
    'product_name' => 'No name',
    'rating' => 'No rating',
    'short_description' => 'No description',
    'full_description' => 'No description',
    'article_number' => 'No article number',
    'stock_quantity' => 'No quantity',
    'category_name' => 'No category',
    'tags' => 'No tags'
];
foreach ($fields as $key => $default) {
    $$key = $productInfo[$key] ?? $default;
}

// Получаем дополнительные данные о продукте
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
    <link rel="stylesheet" href="../styles/template_product/template_product.css">
    <link rel="stylesheet" href="../styles/root/root.css">
    <!-- styles components -->
    <link rel="stylesheet" href="../components/header/header.css">
    <link rel="stylesheet" href="../components/footer/footer.css">
    <link rel="stylesheet" href="../components/item_arrivals/item_arrivals.css">
    <!-- animation -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.css">

</head>

<body>
    <?php require_once "../components/header/header.php"; ?>

    <main>
        <section class="product">
            <div class="container_width">
                <div class="product-info">
                    <div class="info-container">
                        <!-- Слайдер изображений -->
                        <div class="slider"  data-aos="fade-right" data-aos-delay="200"  data-aos-duration="900">
                            <div class="sliders">
                                <?php if ($productImages): ?>
                                    <?php foreach ($productImages as $index => $image): ?>
                                        <div class="slide" data-index="<?= $index ?>">
                                            <img src="../image/<?= htmlspecialchars($image['image_url']) ?>" alt="Image">
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="current_slide"></div>
                        </div>

                        <!-- Характеристики продукта -->
                        <div class="characteristics" data-aos="fade-left" data-aos-delay="300"  data-aos-duration="900">
                            <h1 class="title"><?= htmlspecialchars($product_name) ?></h1>
                            <div class="rating">
                                <div class="stars">
                                    <ul>
                                        <?= str_repeat("<li><i class='ri-star-fill'></i></li>", 5) ?>
                                    </ul>
                                </div>
                                <p class="rating-count"><?= htmlspecialchars($rating) ?></p>
                            </div>

                            <p class="description"><?= htmlspecialchars($short_description) ?></p>

                            <!-- Управляющие элементы -->
                            <div class="controls">
                                <!-- Цвета -->
                                <div class="control-element color">
                                    <p class="title">Color</p>
                                    <div class="controls-container">
                                        <?php if (empty($productColors)): ?>
                                            <p class="no-data">No colors available at the moment.</p>
                                        <?php else: ?>
                                            <?php foreach ($productColors as $color): ?>
                                                <button class="color-btn" data-color-id="<?= htmlspecialchars($color['color_id']) ?>">
                                                    <?= htmlspecialchars($color['color_name']) ?>
                                                </button>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Размеры -->
                                <div class="control-element size">
                                    <p class="title">Size</p>
                                    <div class="controls-container">
                                        <?php if (empty($productSizes)): ?>
                                            <p class="no-data">No sizes available at the moment.</p>
                                        <?php else: ?>
                                            <?php foreach ($productSizes as $size): ?>
                                                <button class="size-btn" data-size-id="<?= htmlspecialchars($size['size_id']) ?>">
                                                    <?= htmlspecialchars($size['size_name']) ?>
                                                </button>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Количество и кнопки -->
                                <div class="control-element cart">
                                    <?php if (empty($productColors) || empty($productSizes)): ?>
                                        <p class="no-data">Sorry, this product is currently unavailable for order due to lack of sizes or colors.</p>
                                    <?php else: ?>
                                        <p class="title"><?= htmlspecialchars($stock_quantity) ?> in stock</p>
                                        <div class="controls-container">
                                            <div class="count-container">
                                                <button class="increase">+</button>
                                                <input type="number" value="1" min="1" max="<?= htmlspecialchars($stock_quantity) ?>" id="quantity_product">
                                                <button class="decrease">−</button>
                                            </div>
                                            <!-- Если пользователь авторизован -->
                                            <?php if ($user_id): ?>
                                                <button id="add_to_cart">Add to Cart</button>
                                                <button><i class="ri-heart-add-line"></i></button>
                                            <?php else: ?>
                                                <p>Please <a href="login.php">log in</a> to add to cart or wishlist.</p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <ul class="properties">
                                <li><strong>Art:</strong> <?= htmlspecialchars($article_number) ?></li>
                                <li><strong>Category:</strong> <?= htmlspecialchars($category_name) ?></li>
                                <li><strong>Tags:</strong> <?= htmlspecialchars($tags) ?></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Форма для добавления в корзину (для авторизованных) -->
                    <?php if ($user_id): ?>
                        <form id="add_to_cart_form" method="POST" action="process_cart.php" style="display: none;">
                            <input type="hidden" name="product_id" value="<?= $product_id ?>">
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            <input type="hidden" name="color" id="selected_color" value="">
                            <input type="hidden" name="size" id="selected_size" value="">
                            <input type="hidden" name="quantity" id="selected_quantity" value="">
                        </form>
                    <?php endif; ?>

                    <div class="full_description">
                        <p class="title" data-aos="fade-up" data-aos-delay="400"  data-aos-duration="900">Full description</p>
                        <p class="text_description" data-aos="fade-up" data-aos-delay="700"  data-aos-duration="900"><?= htmlspecialchars($full_description) ?> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo, sunt porro quae est modi voluptas molestias deleniti quas maxime dolore! Laborum odio porro sint quibusdam, illum non consectetur eveniet neque harum cumque nesciunt labore praesentium dolor in ipsa sed animi rem excepturi itaque, cupiditate explicabo natus iste ab fugiat. Saepe omnis dignissimos incidunt ducimus laudantium natus fugit sunt eaque reprehenderit dolor atque explicabo, veniam perferendis architecto, impedit similique, exercitationem numquam assumenda eos ipsa nemo alias repellat laboriosam ipsam. Cupiditate, illo! Earum eaque possimus repellat libero, facere quo et inventore sint adipisci, voluptas amet beatae maxime recusandae nesciunt nam non temporibus illum vitae laudantium est vel! Quos, dolorem numquam. Nostrum numquam accusamus in enim porro nobis voluptates vero alias amet impedit harum labore quasi at illum placeat obcaecati sit, sequi dignissimos quo laudantium. Odit reiciendis sint doloremque, laudantium necessitatibus quibusdam quidem repudiandae nostrum possimus sed in ipsum, consequuntur quasi soluta voluptas aperiam, magni inventore molestiae repellendus amet reprehenderit tempore! Ad neque voluptatibus tempore eaque sed maxime! Repellat, doloribus sunt quos facilis mollitia architecto velit dicta atque nam amet rerum? Hic inventore officia ipsum aliquam culpa magni repellat corporis facere voluptatum, velit asperiores quia aliquid rerum consectetur, repellendus maxime veniam. Eligendi, voluptatibus.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once "../components/footer/footer.php"; ?>

    <script src="../js/template_product/template_product.js"></script>
    <script src="../js/header/header.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
