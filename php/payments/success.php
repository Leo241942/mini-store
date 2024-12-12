<?php
require_once '../../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_yourSecretKey');  // Ваш Secret Key

if (isset($_GET['session_id'])) {
    $sessionId = $_GET['session_id'];

    try {
        // Получаем информацию о сессии
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        $customer = \Stripe\Customer::retrieve($session->customer);

        // Обработка успешного платежа
        echo "<h1>Спасибо за покупку!</h1>";
        echo "<p>Оплата успешно прошла. Ваши данные:</p>";
        echo "<pre>" . print_r($customer, true) . "</pre>";

        // Здесь можно добавить логику для сохранения данных о заказе в базе данных
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "Ошибка при получении данных сессии: " . $e->getMessage();
    }
}
?>
