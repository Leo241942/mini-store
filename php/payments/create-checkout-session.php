<?php
require_once '../../vendor/autoload.php';  // Подключаем библиотеку Stripe

\Stripe\Stripe::setApiKey('sk_test_51QPlBlDsJ33hFGiMOEPSeXBvi2QsVhkvwoeTePZSKbOx3E5b34RL8bOUZ2MZ4aPah4KHnG2FUOJofGG2b9HYRexe00nMOxrYrR');  // Ваш секретный ключ Stripe

session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Перенаправление на страницу авторизации, если пользователь не авторизован
    exit;
}

// Получаем данные из POST-запроса
$data = json_decode(file_get_contents('php://input'), true);

// Проверка, что заказная сумма передана
if (!isset($data['orderTotalPrice'])) {
    http_response_code(400);  // Ошибка 400: Неверный запрос
    echo json_encode(['error' => 'Missing orderTotalPrice']);
    exit;
}

$orderTotalPrice = $data['orderTotalPrice'] * 100;  // Преобразуем сумму в центы

try {
    // Создаем сессию для Stripe Checkout
    $checkoutSession = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Total Payment',  // Название платежа
                    ],
                    'unit_amount' => $orderTotalPrice,  // Итоговая сумма в центах
                ],
                'quantity' => 1,
            ]
        ],
        'mode' => 'payment',  // Для одноразовой оплаты
        'success_url' => 'http://yourdomain.com/success.php?session_id={CHECKOUT_SESSION_ID}',  // URL успешного завершения
        'cancel_url' => 'http://yourdomain.com/cancel.php',  // URL отмены
    ]);

    // Отправляем ID сессии на клиентскую сторону
    echo json_encode(['sessionId' => $checkoutSession->id]);

} catch (\Stripe\Exception\ApiErrorException $e) {
    // Обработка ошибок
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
