<?php
class ShopRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Функция для получения товаров с пагинацией
    public function getPaginatedProducts(int $page = 1, int $perPage = 9): array
    {
        // Вычисляем смещение для пагинации
        $offset = ($page - 1) * $perPage;

        // Формируем запрос для вызова процедуры с параметрами LIMIT и OFFSET
        $sql = "CALL getPaginatedProducts(:limit, :offset)"; // Используем новую процедуру

        // Подготавливаем запрос
        $stmt = $this->pdo->prepare($sql);

        // Привязываем параметры
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        // Выполняем запрос
        $stmt->execute();

        // Забираем все данные
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Закрываем курсор для освобождения ресурсов
        $stmt->closeCursor();

        return $products;
    }

    // Получаем общее количество товаров
    public function getTotalProductCount(): int
    {
        $sql = "SELECT COUNT(*) FROM products";
        $stmt = $this->pdo->query($sql);
        return (int) $stmt->fetchColumn();
    }

    // Функция для генерации пагинации
    public function generatePagination(int $currentPage, int $totalPages): string
    {
        $pagination = '<div class="pagination">';

        // Кнопка "Предыдущая"
        if ($currentPage > 1) {
            $pagination .= '<a href="?page=' . ($currentPage - 1) . '" class="pagination_element">«</a>';
        }

        // Ссылки на страницы
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                $pagination .= '<a href="?page=' . $i . '" class="pagination_element active">' . $i . '</a>';
            } else {
                $pagination .= '<a href="?page=' . $i . '" class="pagination_element">' . $i . '</a>';
            }
        }

        // Кнопка "Следующая"
        if ($currentPage < $totalPages) {
            $pagination .= '<a href="?page=' . ($currentPage + 1) . '" class="pagination_element">»</a>';
        }

        $pagination .= '</div>';

        return $pagination;
    }
}
?>
