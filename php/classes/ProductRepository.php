<?php
class ProductRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Функция для получения карточек товаров
    public function getProductCards(int $limit = null): array
    {
    // Формируем запрос для вызова процедуры с параметром LIMIT
    // Если лимит не задан, используем процедуру без ограничения
    $sql = "CALL GetProductCards(" . ($limit ? (int)$limit : 'NULL') . ")"; 

    // Подготавливаем запрос
    $stmt = $this->pdo->prepare($sql);

    // Выполняем запрос
    $stmt->execute();

    // Забираем все данные
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Закрываем курсор для освобождения ресурсов
    $stmt->closeCursor();

    return $products;
    }


    public function GetProductDetails(int $productId): ?array
    {
        $sql = "CALL GetProductDetails(:productId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT); // Убедитесь, что имя параметра совпадает
        $stmt->execute();
        
        // Извлекаем одну строку
        $productInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
    
        // Проверяем результат
        return $productInfo ?: null; // Если данных нет, возвращаем null
    }
    

    public function GetProductSizes(int $productId): ?array
    {
        $sql = "CALL GetProductSizes(:productId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Извлекаем все строки
        $productSizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
    
        // Проверяем результат
        return !empty($productSizes) ? $productSizes : null; // Если данных нет, возвращаем null
    }
    public function GetProductImages(int $productId): array
    {
        $sql = "CALL GetProductImages(:productId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Извлекаем все строки
        $productImgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        
        // Возвращаем массив изображений
        return $productImgs;
    }
    

    public function GetProductColors(int $productId): ?array
    {
        $sql = "CALL GetProductColors(:productId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Извлекаем все строки
        $productColors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
    
        // Проверяем результат
        return !empty($productColors) ? $productColors : null; // Если данных нет, возвращаем null
    }

   

    public function getTotalProductCount() {
        $sql = "SELECT COUNT(*) FROM products";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }

    
    


    
}

?>