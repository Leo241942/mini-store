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
<?php  require_once "../components/header/header.php";  ?>


    <main>
        <section class="start_shopping">
            <div class="container_width">
                <div class="promo_banner">
                    <p class="title">Street Wears</p>
                    <p class="description">High quality cool tshirts for street fashion</p>
                    <button class="btn_StartShopoing">start shopping <i class="ri-arrow-right-line"></i></button>
                </div>
            </div>
        </section>

        <section class="our_advantages">
            <div class="container_width">
                <div class="container_adantages">
                    <div class="item_adantages">
                        <i class="ri-shopping-cart-2-line"></i>
                        <p class="title">Free Delivery</p>
                        <p class="description">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                    </div>
                    <div class="item_adantages">
                        <i class="ri-shield-check-line"></i>
                        <p class="title">100% secure payment</p>
                        <p class="description">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                    </div>
                    <div class="item_adantages">
                        <i class="ri-medal-line"></i>
                        <p class="title">Quality guarantee</p>
                        <p class="description">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                    </div>
                    <div class="item_adantages">
                        <i class="ri-money-dollar-circle-line"></i>
                        <p class="title">Daily Offer</p>
                        <p class="description">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="new_arrivals">
            <div class="container_width">
                <p class="title">New Arrivals</p>
                <div class="listing_container">
                    <div class="listing">

                        <!-- item template -->
                        <?php  
                            for ($i = 0; $i != 8; $i++) {
                                require "../components/item_arrivals/item_arrivals.php" ;  
                            }
                        ?>
                    </div>
                    <button class="btn_allProduct">View All Products</button>
                </div>
            </div>
        </section>

        <section class="category">
            <a href="#" class="category_item">
               <img src="../image/category1.jpg" alt="">
               <p class="title">Printed T-Shirts</p>
            </a>
            <a href="#" class="category_item">
                <img src="../image/category2.jpg" alt="">
                <p class="title">Mono T-Shirts</p>
             </a>
             <a href="#" class="category_item">
                <img src="../image/category3.jpg" alt="">
                <p class="title">Graphic Hoodies</p>
             </a>
        </section>

    </main>

    <?php  require_once "../components/footer/footer.html";  ?>

    <script src="../js/index/index.js"></script>
</body>

</html>