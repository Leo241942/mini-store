<?php
require_once '../php/classes/ShopRepository.php';
require_once '../php/connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>account</title>
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
                    <button id="loginTab" class="active">Log In</button>
                    <button id="signupTab">Sign Up</button>
                </div>

                <form id="loginForm" action="../php/account/login.php" method="post">
                    <input type="text" placeholder="Your Nickname" name  = "nickname" required >
                    <input type="password" placeholder="Your Password" name  = "password" required >
                    <input type="email" placeholder="Your Email" name  = "email" required >
                    <button>Log In</button>
                </form>

                <form id="signupForm" action="../php/account/singup.php" method="post" enctype="multipart/form-data">
    <input type="text" placeholder="Your Nickname" name="nickname" required>
    
    <!-- Область для перетаскивания файлов -->
    <div class="drop-area" id="dropArea">
        <input type="file" name="file" id="fileInput" style="display: none;">
        <!-- Статус загрузки файла -->
        <div id="fileStatus" class="file-status">
            <span id="fileText">Перетащите сюда файл или нажмите для выбора</span>
            <!-- Галочка -->
            <svg id="fileIcon" class="file-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 16.2L4.8 12.8l-1.4 1.4L9 19 20.6 7.4l-1.4-1.4z"/>
            </svg>
        </div>
    </div>

    <input type="password" placeholder="Your Password" name="password" required>
    <input type="email" placeholder="Your Email" name="email" required>
    <button>Sign Up</button>
</form>





            </div>
        </section>
    </main>

    <?php require_once "../components/footer/footer.php"; ?>

    <script src="../js/index/index.js"></script>
    <script src="../js/account/account.js"></script>
</body>
</html>
