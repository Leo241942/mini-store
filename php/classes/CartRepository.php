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
     public function getOrderDetails(int $orderId): array
     {
         // Формируем SQL-запрос для вызова хранимой процедуры GetOrderInfo
         $sql = "CALL GetOrderDetails(:order_id)";
     
         // Подготавливаем запрос
         $stmt = $this->pdo->prepare($sql);
     
         // Привязываем параметр
         $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
     
         // Выполняем запрос
         $stmt->execute();
     
         // Получаем результат
         $orderInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
         // Закрываем курсор для освобождения ресурсов
         $stmt->closeCursor();
     
         return $orderInfo;
     }
     

    
    
    
}

?>