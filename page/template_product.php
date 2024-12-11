<?php
require_once '../php/classes/ProductRepository.php';
require_once '../php/connect.php';
session_start();

$user_id = 2; // Для тестирования с ID 2
$product_id = isset($_GET['product_id']) && is_numeric($_GET['product_id']) ? (int)$_GET['product_id'] : null;

if (!$product_id) {
    echo "Вы ошиблись. Такого продукта нет.";
    return; // Останавливаем выполнение дальнейшего кода
}

$ProductRepository = new ProductRepository($pdo);
$productInfo = $ProductRepository->GetProductDetails($product_id);

if ($productInfo) {
    // Упрощаем присваивание значений с дефолтными значениями
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

    // Присваиваем значения или дефолт
    foreach ($fields as $key => $default) {
        $$key = $productInfo[$key] ?? $default;
    }
}

// Получаем изображения, размеры и цвета
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
    <link rel="stylesheet" href="../components/header/header.css">
    <link rel="stylesheet" href="../components/footer/footer.css">
    <link rel="stylesheet" href="../components/item_arrivals/item_arrivals.css">
</head>

<body>
    <?php require_once "../components/header/header.php"; ?>

    <main>
        <section class="product">
            <div class="container_width">
                <div class="product-info">
                    <div class="info-container">
                        <div class="slider">
                            <div class="sliders">
                                <?php if ($productImages): ?>
                                    <?php foreach ($productImages as $index => $image): ?>
                                        <div class="slide" data-index="<?= $index ?>">
                                            <img src='../image/<?= htmlspecialchars($image['image_url']) ?>' alt='Image'>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="current_slide"></div>
                        </div>

                        <div class="characteristics">
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

                            <!-- Controls -->
                            <div class="controls">
                            <div class="control-element color">
                                <p class="title">Color</p>
                                <div class="controls-container">
                                    <?php if (empty($productColors)): ?>
                                        <p class="no-data">No colors available at the moment.</p>
                                    <?php else: ?>
                                        <?php foreach ($productColors as $color): ?>
                                            <button class='color-btn' data-color-id="<?= htmlspecialchars($color['color_id']) ?>">
                                                <?= htmlspecialchars($color['color_name']) ?>
                                            </button>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="control-element size">
                                <p class="title">Size</p>
                                <div class="controls-container">
                                    <?php if (empty($productSizes)): ?>
                                        <p class="no-data">No sizes available at the moment.</p>
                                    <?php else: ?>
                                        <?php foreach ($productSizes as $size): ?>
                                            <button class='size-btn' data-size-id="<?= htmlspecialchars($size['size_id']) ?>">
                                                <?= htmlspecialchars($size['size_name']) ?>
                                            </button>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                                <div class="control-element cart">
                                    <p class="title"><?= htmlspecialchars($stock_quantity) ?> in stock</p>
                                    <div class="controls-container">
                                        <div class="count-container">
                                            <button class="increase">+</button>
                                            <input type="number" value="1" min="1" max="<?= htmlspecialchars($stock_quantity) ?>" id="quantity_product">
                                            <button class="decrease">−</button>
                                        </div>
                                        <button id="add_to_cart" <?= $stock_quantity < 1 ? 'disabled' : '' ?>>Add to Cart</button>
                                        <button><i class="ri-heart-add-line"></i></button>
                                    </div>
                                </div>
                            </div>

                            <ul class="properties">
                                <li><strong>Art:</strong> <?= htmlspecialchars($article_number) ?></li>
                                <li><strong>Category:</strong> <?= htmlspecialchars($category_name) ?></li>
                                <li><strong>Tags:</strong> <?= htmlspecialchars($tags) ?></li>
                            </ul>
                        </div>
                    </div>

                    <form id="add_to_cart_form" method="POST" action="process_cart.php" style="display: none;">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <input type="hidden" name="user_id" value="<?= $user_id ?>">
                        <input type="hidden" name="color" id="selected_color" value="">
                        <input type="hidden" name="size" id="selected_size" value="">
                        <input type="hidden" name="quantity" id="selected_quantity" value="">
                    </form>

                    <div class="full_description">
                        <p class="title">Full description</p>
                        <p class="text_description"><?= htmlspecialchars($full_description) ?> 
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nemo, obcaecati rerum, debitis illo quis ex quae sed quaerat nesciunt explicabo maxime enim inventore esse sapiente, reiciendis pariatur excepturi! Sapiente, ipsum consectetur sunt neque dicta eveniet tempora explicabo voluptatem voluptates modi vel facilis quas odit commodi provident unde rerum ullam? Veritatis cumque, eos amet consequatur voluptatibus voluptates nisi nulla hic perferendis maxime corrupti porro dignissimos itaque placeat minima dolor esse iste deleniti consectetur alias consequuntur voluptatem veniam? At consequatur quos eaque impedit, id suscipit eius, et soluta ratione, sapiente ipsam. Praesentium ullam, amet saepe sint officiis nihil nostrum minima recusandae quo nesciunt similique fugit vero consequuntur? Cupiditate molestias, eius laboriosam perspiciatis, soluta vero sunt cum quas veniam aliquam, amet ducimus eaque corporis doloremque mollitia culpa tenetur optio hic qui porro dolorum. Aperiam tempore corrupti ea ducimus, repellat assumenda blanditiis ab voluptatibus magni dolorem sint similique error earum quasi voluptas voluptate natus atque ratione eius temporibus voluptatum, odit impedit placeat modi. Id, repellat cupiditate doloribus atque provident dignissimos exercitationem nemo dolore amet molestias, delectus mollitia error expedita! Corporis quos recusandae facere eius eveniet eaque impedit asperiores quasi sapiente, quae aut, suscipit deserunt commodi quo cupiditate ab rem. Velit rerum ex maiores laboriosam, obcaecati, dignissimos esse architecto provident, fuga explicabo omnis modi iusto. Consectetur quas architecto nobis. Vitae quaerat consequuntur molestias error voluptatum quis, eaque nam tenetur, doloribus rerum nostrum animi quisquam similique dolor, porro libero nesciunt nihil. Laborum voluptatum numquam sequi corporis neque perferendis cum, adipisci officiis voluptates, quos a eos asperiores magnam laboriosam est architecto suscipit! Impedit mollitia minima ex possimus velit ab reprehenderit? Necessitatibus, velit! Quasi, id provident, nobis a minus sunt quos illum reiciendis sit ea eligendi excepturi possimus, non hic incidunt at in optio inventore corrupti sed distinctio odio voluptatum dolor vero? Hic neque velit dolores omnis quia!</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once "../components/footer/footer.php"; ?>

    <script src="../js/template_product/template_product.js"></script>
    <script src="../js/index/index.js"></script>
</body>

</html>
