<?php

require_once '../php/classes/ProductRepository.php';
require_once '../php/classes/CartRepository.php';
require_once '../php/connect.php';


// Создаем экземпляр класса ProductRepository
$ProductRepository = new ProductRepository($pdo);

// Получаем все карточки товаров




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>catalog</title>
    <link rel="stylesheet" href="../styles/catalog/catalog.css">
    <link rel="stylesheet" href="../styles/root/root.css">

    <!-- styles components -->
    <link rel="stylesheet" href="../components/header/header.css">
    <link rel="stylesheet" href="../components/footer/footer.css">
    <link rel="stylesheet" href="../components/item_arrivals/item_arrivals.css">

</head>

<body>
<?php  require_once "../components/header/header.php";  ?>


    <main>
        <section class="catalog">
            <div class="container_width">
                <!-- панель фильтрации-->
                <div class = "filter_panel">
                    <input type="search" class = "search" placeholder = "Search for products">

                    <div class = "filters_container">

                        <div class = "filter_component">
                            <p class = "title_filter">Lorem, ipsum.</p>
                            <ul class = "filter">
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                            </ul>
                        </div>

                        <div class = "filter_component">
                            <p class = "title_filter">Lorem, ipsum.</p>
                            <ul class = "filter">
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                            </ul>
                        </div>
                        <div class = "filter_component">
                            <p class = "title_filter">Lorem, ipsum.</p>
                            <ul class = "filter">
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                                <li><a href="#">Lorem, ipsum.</a></li>
                            </ul>
                        </div>



                    </div>
                </div>                <!-- панель фильтрации-->

                   <div class = "listing_container">
                        <div class = "header_listing">
                            <p class = "showing_result">Showing 1–9 of 55 results</p>
                            <select class = "product_sort">
                                <option value="default">default</option>
                            </select>
                        </div>

                        <div class = "listing">
                        <?php  

                                $products = $ProductRepository->getProductCards();

                                    foreach ($products as $product) {
                                        $cover = $product['cover']; 
                                        $tags = $product['tags']; 
                                        $name = $product['product_name'];
                                        $price = $product['price'];
                                        $discountPrice = $product['discount_price']; 
                                        $productId = $product['id']; 
                                        require "../components/item_arrivals/item_arrivals.php" ;  
                                    }
                                ?>
                        </div>

                        <div class = "pagination">
                            <button>arrow</button>
                            <button>arrow</button>
                            <div class = "pages"> 
                                <a class = "pagination_element">1</a>
                               <a class = "pagination_element">2</a>
                                <a class = "pagination_element">3</a>
                            </div>

                   </div>
            </div>
        </section>

            
    </main>

    <?php  require_once "../components/footer/footer.html";  ?>

    <script src="../js/index/index.js"></script>
</body>

</html>