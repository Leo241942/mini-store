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
                                <div class="slide" data-index="0"><img src="../image/item1.jpg" alt="Image 1"></div>
                                <div class="slide" data-index="1"><img src="../image/item2.jpg" alt="Image 2"></div>
                                <div class="slide" data-index="2"><img src="../image/item3.jpg" alt="Image 3"></div>
                                <div class="slide" data-index="3"><img src="../image/item4.jpg" alt="Image 4"></div>
                                <div class="slide" data-index="4"><img src="../image/item5.jpg" alt="Image 5"></div>
                            </div>
                            <div class="current_slide"></div>
                        </div>

                        <!-- Product Details -->
                        <div class="characteristics">
                            <h1 class="title">Printed T-Shirt</h1>

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
                                <p class="rating-count">5.0</p>
                            </div>

                            <!-- Description -->
                            <p class="description">
                                Justo, cum feugiat imperdiet nulla molestie ac vulputate scelerisque amet.
                                Bibendum adipiscing platea blandit sit sed quam semper rhoncus.
                            </p>

                            <!-- Controls: Color, Size, and Add to Cart -->
                            <div class="controls">
                                <!-- Color Selector -->
                                <div class="control-element color">
                                    <p class="title">Color</p>
                                    <div class="controls-container">
                                        <button class="color-btn active">Gray</button>
                                        <button class="color-btn">Black</button>
                                        <button class="color-btn">Blue</button>
                                        <button class="color-btn disenable">Red</button>
                                    </div>
                                </div>

                                <!-- Size Selector -->
                                <div class="control-element size">
                                    <p class="title">Size</p>
                                    <div class="controls-container">
                                        <button class="size-btn active">XL</button>
                                        <button class="size-btn">L</button>
                                        <button class="size-btn">M</button>
                                        <button class="size-btn disenable">S</button>
                                    </div>
                                </div>

                                <!-- Add to Cart Section -->
                                <div class="control-element cart">
                                    <p class="title">2 in stock</p>
                                    <div class="controls-container">
                                        <div class="count-container">
                                            <button class="increase">+</button>
                                            <input type="number" value="1" min="1" max="2">
                                            <button class="decrease">−</button>
                                        </div>
                                        <button>Add to Cart</button>
                                        <button><i class="ri-heart-add-line"></i></button>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Properties -->
                            <ul class="properties">
                                <li><strong>Art:</strong> 241</li>
                                <li><strong>Category:</strong> T-shirt, Hoodies</li>
                                <li><strong>Tags:</strong> Clothes, Cotton</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tabs Section -->
                    <div class="tabs_container">
                        <div class="tabs_controls">
                            <button data-tab="description" class="active">Description</button>
                            <button data-tab="information">Additional Information</button>
                            <button data-tab="reviews">Customer Reviews</button>
                        </div>
                        <div class="tabs">
                            <div class="tab_content tab_description active">
                                <p class="title">Product Description</p>
                                <div class="product_description">
                                    <p>Lorem ipsum... Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident blanditiis optio beatae! Adipisci aperiam debitis ullam consequatur amet similique sit. Similique corporis asperiores tempora hic maxime inventore saepe numquam voluptas neque, dolor porro beatae natus! Nesciunt minima veritatis excepturi sequi fuga incidunt? In, quas nam? Hic itaque porro nisi. Placeat incidunt dolorem temporibus molestias, soluta, non accusamus deserunt fuga perspiciatis aperiam eligendi beatae dicta eum corrupti ipsum tenetur quas assumenda nesciunt ea veritatis maxime suscipit quo totam! Nihil maiores laborum ab molestias possimus, amet inventore iusto illum aliquam dolores iste voluptatum. Recusandae enim explicabo laboriosam voluptatibus maxime doloribus repellendus tempore, inventore nihil vero dolore aspernatur eaque iusto assumenda nostrum quam sint iure sed molestias! Dolor deleniti esse ullam alias quaerat vitae cum? Doloribus iste, provident tempora corrupti officia velit ratione dolore explicabo eligendi cupiditate nam fugit beatae voluptates iusto dolorum sapiente error vel! Omnis sint illum incidunt animi iste ratione recusandae sit impedit minima, cumque necessitatibus ipsam sapiente maiores quas? Cupiditate facere provident animi iste illo, eius atque quod aliquam sapiente laudantium veritatis earum, ipsum eveniet aliquid rerum consequuntur distinctio sint enim a? Neque culpa laudantium facilis quo quibusdam libero, saepe necessitatibus rerum non officiis aspernatur voluptas ipsa mollitia sapiente qui eaque, delectus eveniet, quas rem repudiandae. Doloremque earum quasi aspernatur consequuntur. Accusantium blanditiis nesciunt laboriosam voluptatibus, libero suscipit, ad architecto sed vitae doloremque ullam voluptate quaerat. Quasi, ut vel sapiente blanditiis totam accusamus exercitationem! Amet dolorum quibusdam eligendi enim inventore excepturi sequi minus, doloribus, veritatis atque ad quidem nulla recusandae ipsam? Aliquam rem, debitis natus optio earum soluta doloremque beatae aliquid similique obcaecati odio fuga quia neque necessitatibus illo dolorum maxime aut sapiente doloribus quidem nisi nesciunt voluptatem. Dolorem quasi mollitia veritatis aspernatur autem. Voluptatem amet nihil, numquam inventore unde reiciendis. Cum quam sint eveniet. Perspiciatis expedita ut quisquam?</p>
                                </div>
                            </div>
                            <div class="tab_content tab_information">
                                <p class="title">Additional Information</p>
                                <div class="product_description">
                                    <p>Lorem ipsum...</p>
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
