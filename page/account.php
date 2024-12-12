<?php

require_once '../php/classes/ShopRepository.php';
require_once '../php/connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link rel="stylesheet" href="../styles/account/account.css">
    <link rel="stylesheet" href="../styles/root/root.css">
    <link rel="stylesheet" href="../components/header/header.css">
    <link rel="stylesheet" href="../components/footer/footer.css">
</head>

<body>
    <?php require_once "../components/header/header.php"; ?>

    <main>
        <section class="account">
            <div class="container_width">
                <div class="header_account">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <button id="loginTab" class="active">Log In</button>
                        <button id="signupTab">Sign Up</button>
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- User profile section if the user is logged in -->
                    <div class="user_profile">
                        <img src="../image/users/i.webp" alt="">
                        <p>Welcome, User #<?php echo $_SESSION['user_id']; ?></p>
                        <p>Вы уже авторизованы, выйдите чтобы войти снова или зарегистрироваться.</p>
                        <a href = "../php/logout.php" id="logoutButton">Выйти</a>
                    </div>
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

    <script src="../js/header/header.js"></script>
    <script src="../js/account/account.js"></script>
</body>
</html>
