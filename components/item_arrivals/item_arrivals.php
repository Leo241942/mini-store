<div class="item_arrivals">
    <!-- Блок изображения и управления -->
    <div class="img_container">
        <!-- Выводим изображение товара -->
        <img src="../image/<?= htmlspecialchars($cover) ?>" alt="<?= htmlspecialchars($name) ?>">

        <!-- Выводим тег, если он есть -->
        <?php if (!empty($tags)): ?>
            <div class="tag"><?= htmlspecialchars($tags) ?></div>
        <?php endif; ?>

        <!-- Управление товарами (добавление в корзину, расширение, добавление в избранное) -->
        <div class="controls">
            <form  class = "control">
                <button>
                  <i class="ri-expand-diagonal-line"></i>
                </button>
            </form>
            <form  class = "control">
                <button>
                    <i class="ri-heart-add-line"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Блок информации о товаре -->
    <div class="info">
        <!-- Название товара -->
        <a class="name" href="template_product.php?product_id=<?= htmlspecialchars($productId) ?>">
            <?= htmlspecialchars($name) ?>
        </a>

        <!-- Цена товара, с учетом скидки, если есть -->
        <p class="price">
            <?= !empty($discountPrice) 
                ? "<del>\${$price}</del> <span>\${$discountPrice}</span>" 
                : "\${$price}" 
            ?>
        </p>
    </div>
</div>
