
<header>
    <div class="container_width">
        <div class="header_content">
            <div class="logo_container">
                <a class="logo">uniclub</a>
                <nav>
                    <ul>
                        <li><a href="#">home</a></li>
                        <li><a href="#">shop</a></li>
                        <li><a href="#">about</a></li>
                        <li><a href="#">sale</a></li>
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
            <li class="item_product">
                <div class="info">
                    <p class="name">Grey Hoodie</p>
                    <p class="description">Brief description</p>
                </div>
                <p class="price">$50</p>
            </li>
            <!-- copies of elements -->
            <li class="item_product">
                <div class="info">
                    <p class="name">Grey Hoodie</p>
                    <p class="description">Brief description</p>
                </div>
                <p class="price">$100</p>
            </li>
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
                <p class="price">$250</p>
            </li>
        </ul>

    </div>
    <form action="#">
        <button class="btn_continue">Continue to checkout</button>
    </form>
</div>

<div class="blackout" id="blackout"></div>