<?php
// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $nickname = $_POST['nickname'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Выводим текстовые данные
    echo "Nickname: $nickname<br>";
    echo "Email: $email<br>";
    echo "Password: $password<br>";

    // Проверяем, был ли загружен файл
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Путь для сохранения файла
        $uploadDir = '../../image/';
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);
        
        // Перемещаем загруженный файл в папку на сервере
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            echo "Файл был успешно загружен: <a href='$uploadFile'>Посмотреть файл</a><br>";
        } else {
            echo "Ошибка при загрузке файла.<br>";
        }
    } else {
        echo "Файл не был загружен или произошла ошибка при загрузке.<br>";
    }
}
?>
