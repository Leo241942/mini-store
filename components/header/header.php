<?php
    require_once '../php/classes/CartRepository.php';
    require_once '../php/connect.php';
    $CartRepository = new CartRepository($pdo);

?>
<header>
    <div class="container_width">
        <div class="header_content">
            <div class="logo_container">
                <a class="logo">uniclub</a>
                <nav>
                    <ul>
                        <li><a href="index.php">home</a></li>
                        <li><a href="catalog.php">shop</a></li>
                        <li><a href="#">about</a></li>
                        <li><a href="template_product.php">template</a></li>
                    </ul>
                </nav>
            </div>
            <div class="controls">
                <a href="#"><i class="ri-user-fill"></i></a>
                <a href="#"><i class="ri-heart-fill"></i></a>
                <a href="#" id="cart-icon"><i class="ri-shopping-cart-2-fill"></i></a>
            </div>
        </div>
    </div>
</header>
<div class="cart_open" id="cart_open">
    <div class="header_cart">
        <p class="title">Your cart</p>
        <p class="count">3</p>
    </div>

    <div class="product_list">
        <ul>
            <!-- item template -->
             
             <?php
 $orders = $CartRepository->GetOrderDetails(6);

 foreach ($orders as $order)
 {
    $product_id = $order['product_id'];
    $product_name = $order['product_name'];
    $discount_price = $order['discount_price'];
    $color_name = $order['color_name'];
    $size_name = $order['size_name'];
    $quantity = $order['quantity'];
    $cover_image_url = $order['cover_image_url'];
    $total_price = $order['total_price'];
    $order_total_price = $order['order_total_price'];

    ?>
    <li сlass = "item_product">
    <p>Product ID: <?php echo $product_id; ?></p>
    <p>Product Name: <?php echo $product_name; ?></p>
    <p>Discount Price: <?php echo $discount_price; ?></p>
    <p>Color: <?php echo $color_name; ?></p>
    <p>Size: <?php echo $size_name; ?></p>
    <p>Quantity: <?php echo $quantity; ?></p>
    <p>Cover Image URL: <img src="<?php echo $cover_image_url; ?>" alt="Product Image"></p>
    <p>Total Price: <?php echo $total_price; ?></p>
</li>

    <?php
 }

// Создаем экземпляр CartRepository
$CartRepository = new CartRepository($pdo);

$_SESSION['order_id']= 2;
// Определяем ID заказа (например, из сессии пользователя или из параметров)


?>

            <!-- copies of elements -->
            <li class="item_product">
                <div class="info">
                    <p class="name">Grey Hoodie</p>
                    <p class="description">Brief description</p>
                </div>
                <p class="price">$100</p>
            </li>
           
            <!-- copies of elements -->
            <li class="total_price">
                <p class="title">Total (USD)</p>
                <p class="price"> <?php echo $order_total_price; ?>$</p>
            </li>
        </ul>

    </div>
    <form action="#">
        <button class="btn_continue">Continue to checkout</button>
    </form>
</div>

<div class="blackout" id="blackout"></div>