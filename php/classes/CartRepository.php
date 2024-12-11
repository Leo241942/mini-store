<?php
class CartRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addProductToOrder(int $userId, int $orderId, int $productId, int $quantity, int $colorId, int $sizeId): bool
    {
        // Формируем SQL-запрос для вызова хранимой процедуры add_product_to_order
        $sql = "CALL add_product_to_order(:user_id, :order_id, :product_id, :quantity, :color_id, :size_id)";

        // Подготавливаем запрос
        $stmt = $this->pdo->prepare($sql);

        // Привязываем параметры
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':color_id', $colorId, PDO::PARAM_INT);
        $stmt->bindParam(':size_id', $sizeId, PDO::PARAM_INT);

        // Выполняем запрос
        return $stmt->execute();
    }
    
     // Функция для получения информации о заказе
     public function getOrderDetails(int $userId): array
     {
         // Формируем SQL-запрос для вызова хранимой процедуры GetOrderDetails
         $sql = "CALL GetOrderDetails(:user_id)";
         
         try {
             // Подготавливаем запрос
             $stmt = $this->pdo->prepare($sql);
             
             // Привязываем параметр
             $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);  // Здесь нужно передать userId, а не orderId
             
             // Выполняем запрос
             $stmt->execute();
             
             // Получаем результат
             $orderInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
             
             // Если результат пуст, возвращаем пустой массив
             if (empty($orderInfo)) {
                 return [];
             }
             
             // Закрываем курсор для освобождения ресурсов
             $stmt->closeCursor();
             
             return $orderInfo;
             
         } catch (PDOException $e) {
             // Логируем ошибку или выводим сообщение
             error_log("Error in getOrderDetails: " . $e->getMessage());
             
             // Возвращаем пустой массив или можно вернуть сообщение об ошибке
             return [];
         }
     }
     
     

  // Метод для добавления товара в корзину
  public function addToCart($userId, $productId, $colorId, $sizeId, $quantity)
  {
      try {
          // Сначала проверим, есть ли уже заказ для пользователя (корзина)
          $stmt = $this->pdo->prepare("SELECT order_id FROM orders WHERE user_id = :user_id AND status = 'pending'");
          $stmt->execute(['user_id' => $userId]);
          $order = $stmt->fetch(PDO::FETCH_ASSOC);

          if (!$order) {
              // Если заказа нет, создаем новый
              echo "Заказ не найден. Создаем новый заказ.<br>";
              $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (:user_id, 0, 'pending')");
              $stmt->execute(['user_id' => $userId]);
              $orderId = $this->pdo->lastInsertId();
              echo "Новый заказ создан. ID заказа: $orderId<br>";
          } else {
              $orderId = $order['order_id'];
              echo "Найден существующий заказ с ID: $orderId<br>";
          }

          // Получаем цену товара для добавления в заказ
          $stmt = $this->pdo->prepare("SELECT price, discount_price FROM products WHERE product_id = :product_id");
          $stmt->execute(['product_id' => $productId]);
          $product = $stmt->fetch(PDO::FETCH_ASSOC);

          if (!$product) {
              echo "Товар с ID $productId не найден!<br>";
              return ['success' => false, 'message' => 'Товар не найден'];
          }

          $price = $product['discount_price'] ? $product['discount_price'] : $product['price'];
          $totalPrice = $price * $quantity;

          // Добавляем товар в таблицу order_items
          $stmt = $this->pdo->prepare("
              INSERT INTO order_items (order_id, product_id, color_id, size_id, quantity, price)
              VALUES (:order_id, :product_id, :color_id, :size_id, :quantity, :price)
          ");
          $stmt->execute([
              'order_id' => $orderId,
              'product_id' => $productId,
              'color_id' => $colorId,
              'size_id' => $sizeId,
              'quantity' => $quantity,
              'price' => $price
          ]);
          echo "Товар добавлен в корзину.<br>";

          // Обновляем общую цену заказа
          $this->updateOrderTotal($orderId);

          return ['success' => true, 'message' => 'Товар добавлен в корзину'];
      } catch (Exception $e) {
          echo "Ошибка: " . $e->getMessage() . "<br>";
          return ['success' => false, 'message' => 'Произошла ошибка при добавлении товара в корзину'];
      }
  }

  // Метод для обновления общей цены заказа
  private function updateOrderTotal($orderId)
  {
      try {
          $stmt = $this->pdo->prepare("
              SELECT SUM(quantity * price) AS total_price FROM order_items WHERE order_id = :order_id
          ");
          $stmt->execute(['order_id' => $orderId]);
          $total = $stmt->fetch(PDO::FETCH_ASSOC)['total_price'];

          $stmt = $this->pdo->prepare("UPDATE orders SET total_price = :total_price WHERE order_id = :order_id");
          $stmt->execute(['total_price' => $total, 'order_id' => $orderId]);
          echo "Общая стоимость заказа обновлена: $total<br>";
      } catch (Exception $e) {
          echo "Ошибка при обновлении общей стоимости: " . $e->getMessage() . "<br>";
      }
  }
    
}

?>