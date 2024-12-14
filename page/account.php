<?php
session_status() === PHP_SESSION_NONE && session_start();

require_once '../php/classes/UserRepository.php';
require_once '../php/connect.php';

$UserRepository = new UserRepository($pdo);

if (isset($_SESSION['user_id'])) {
    // Получаем ID пользователя из сессии
    $userId = $_SESSION['user_id'];
    
    // Получаем информацию о пользователе с помощью метода из репозитория
    $userInfo = $UserRepository->getUserInfo($userId);

    if ($userInfo) {
        // Выводим информацию о пользователе
        $nickname = htmlspecialchars($userInfo['nickname']);
        $email = htmlspecialchars($userInfo['email']);
        $avatar_url = htmlspecialchars($userInfo['avatar_url']);
        $created_at = date("Y-m-d", strtotime($userInfo['created_at']));  
    } 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link rel="stylesheet" href="../styles/account/account.css">
    <link rel="stylesheet" href="../styles/root/root.css">
    <!-- styles components -->
    <link rel="stylesheet" href="../components/header/header.css">
    <link rel="stylesheet" href="../components/footer/footer.css">
    <link rel="stylesheet" href="../components/item_arrivals/item_arrivals.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.css">

</head>

<body>
    <?php require_once "../components/header/header.php"; ?>

    <main>
        <section class="account">
            <div class="container_width">
                <div class="header_account">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <button id="loginTab" class="active" >Log In</button>
                        <button id="signupTab">Sign Up</button>
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- User profile section if the user is logged in -->
                    <div class="user_profile">
                        <img src="../image/users/<?php echo $avatar_url; ?>" alt="User Avatar">
                        <p>Welcome, <?php echo $nickname; ?> (User #<?php echo $_SESSION['user_id']; ?>)</p>
                        <p>Вы уже авторизованы, выйдите чтобы войти снова или зарегистрироваться.</p>
                        <a href="../php/logout.php" id="logoutButton">Выйти</a>
                    </div>

                    <!-- Orders section -->
                     <!-- заглушка будет добавлено в будушем -->
                    <!-- <section class="orders">
                        <h2>Your Orders</h2>
                        <div class="orders_list">
                            <div class="order_item">
                                <span class="order_number">Order #12345</span>
                                <span class="order_status">Status: Pending</span>
                                <div class="order_positions">
                                    <ul>
                                        <li>Item 1</li>
                                        <li>Item 2</li>
                                        <li>Item 3</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="order_item">
                                <span class="order_number">Order #12346</span>
                                <span class="order_status">Status: Shipped</span>
                                <div class="order_positions">
                                    <ul>
                                        <li>Item A</li>
                                        <li>Item B</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section> -->

                <?php else: ?>
                    <!-- Forms for login and signup if the user is not logged in -->
                    <form id="loginForm" action="../php/account/login.php" method="post">
                        <input type="email" placeholder="Your Email" name="email" required>
                        <input type="password" placeholder="Your Password" name="password" required>
                        <button type="submit">Log In</button>
                    </form>

                    <form id="signupForm" action="../php/account/singup.php" method="post" enctype="multipart/form-data" style="display: none;">
                        <input type="text" placeholder="Your Nickname" name="nickname" required>
                        <div class="drop-area" id="dropArea">
                            <input type="file" name="file" id="fileInput" style="display: none;">
                            <div id="fileStatus" class="file-status">
                                <span id="fileText">Drag a file here or click to select</span>
                                <svg id="fileIcon" class="file-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 16.2L4.8 12.8l-1.4 1.4L9 19 20.6 7.4l-1.4-1.4z"/>
                                </svg>
                            </div>
                        </div>
                        <input type="password" placeholder="Your Password" name="password" required>
                        <input type="email" placeholder="Your Email" name="email" required>
                        <button type="submit">Sign Up</button>
                    </form>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <?php require_once "../components/footer/footer.php"; ?>
    <script src="../js/account/account.js"></script>

</body>
</html>
