<div class="item_arrivals" data-aos="fade-up" data-aos-delay="100"  data-aos-duration="400">
    <!-- Блок изображения и управления -->
    <div class="img_container">
        <!-- Выводим изображение товара -->
        <img src="../image/<?= htmlspecialchars($cover) ?>" alt="<?= htmlspecialchars($name) ?>">

        <!-- Выводим тег, если он есть -->
        <?php if ($tags): ?>
            <div class="tag"><?= htmlspecialchars($tags) ?></div>
        <?php endif; ?>

        <div class="controls">
            <!-- Ссылка на страницу товара -->
            <a href="template_product.php?product_id=<?= htmlspecialchars($productId) ?>">
                <i class="ri-expand-diagonal-line"></i>
            </a>

            <!-- Кнопка для добавления в избранное -->
            <form class="control">
                <button type="submit">
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

        <!-- Цена товара с учетом скидки, если есть -->
        <p class="price">
            <?php if ($discountPrice): ?>
                <del><?= htmlspecialchars($price) ?>$</del> <span><?= htmlspecialchars($discountPrice) ?>$</span>
            <?php else: ?>
                <?= htmlspecialchars($price) ?>$
            <?php endif; ?>
        </p>
    </div>
</div>
