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
          // Вызываем хранимую процедуру add_product_to_order
          $stmt = $this->pdo->prepare("CALL add_product_to_order(:user_id, :product_id, :color_id, :size_id, :quantity)");
          $stmt->execute([
              'user_id' => $userId,
              'product_id' => $productId,
              'color_id' => $colorId,
              'size_id' => $sizeId,
              'quantity' => $quantity
          ]);
  
          // Проверяем, была ли процедура выполнена успешно
          return ['success' => true, 'message' => 'Товар добавлен в корзину'];
      } catch (Exception $e) {
          // Логируем ошибку и возвращаем сообщение
          error_log("Ошибка добавления товара в корзину: " . $e->getMessage());
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