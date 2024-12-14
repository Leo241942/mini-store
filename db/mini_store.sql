-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 14 2024 г., 01:58
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mini_store`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_product_to_order` (IN `p_user_id` INT, IN `p_product_id` INT, IN `p_quantity` INT, IN `p_color_id` INT, IN `p_size_id` INT)   BEGIN
    DECLARE v_order_id INT;
    DECLARE v_price DECIMAL(10,2);  -- Обычная цена
    DECLARE v_discount_price DECIMAL(10,2);  -- Скидочная цена (если есть)
    DECLARE v_final_price DECIMAL(10,2);  -- Финальная цена для товара (с учетом скидки)

    -- Шаг 1: Получаем ID активного заказа пользователя (если он есть)
    SELECT order_id
    INTO v_order_id
    FROM orders
    WHERE user_id = p_user_id AND status = 'pending'
    LIMIT 1;

    -- Если активный заказ не найден, создаем новый
    IF v_order_id IS NULL THEN
        INSERT INTO orders (user_id, total_price, status)
        VALUES (p_user_id, 0, 'pending');
        
        SET v_order_id = LAST_INSERT_ID();
    END IF;

    -- Шаг 2: Получаем цену товара и скидочную цену (если она существует)
    SELECT price, discount_price
    INTO v_price, v_discount_price
    FROM products
    WHERE product_id = p_product_id;

    -- Шаг 3: Если скидочная цена существует, применяем её. Если нет, используем обычную цену
    IF v_discount_price IS NOT NULL THEN
        SET v_final_price = v_discount_price;  -- Применяем скидочную цену
    ELSE
        SET v_final_price = v_price;  -- Используем обычную цену
    END IF;

    -- Шаг 4: Проверяем, существует ли уже такая позиция в заказе (товар с данным цветом и размером)
    IF EXISTS (SELECT 1
               FROM order_items
               WHERE order_id = v_order_id
               AND product_id = p_product_id
               AND color_id = p_color_id
               AND size_id = p_size_id) THEN
        -- Если такая позиция есть, обновляем количество и пересчитываем цену для данной позиции
        UPDATE order_items
        SET quantity = quantity + p_quantity,
            price = v_final_price  -- Обновляем цену товара, если она изменилась
        WHERE order_id = v_order_id
          AND product_id = p_product_id
          AND color_id = p_color_id
          AND size_id = p_size_id;
    ELSE
        -- Если позиции нет, добавляем новый товар
        INSERT INTO order_items (order_id, product_id, color_id, size_id, quantity, price, discount)
        VALUES (v_order_id, p_product_id, p_color_id, p_size_id, p_quantity, v_final_price, v_discount_price);
    END IF;

    -- Шаг 5: Обновляем общую цену заказа
    UPDATE orders
    SET total_price = (SELECT SUM(quantity * price)
                       FROM order_items
                       WHERE order_id = v_order_id)
    WHERE order_id = v_order_id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetOrderDetails` (IN `p_user_id` INT)   BEGIN
    -- Шаг 1: Объявление переменных
    DECLARE v_order_id INT;

    -- Временная таблица для хранения информации о заказе
    CREATE TEMPORARY TABLE order_details (
        product_id INT,
        product_name VARCHAR(255),
        price DECIMAL(10, 2),
        discount_price DECIMAL(10, 2),
        color_name VARCHAR(50),
        size_name VARCHAR(50),
        quantity INT,
        cover_image_url VARCHAR(255),
        total_price DECIMAL(10, 2)
    );

    -- Шаг 2: Получаем ID активного заказа пользователя
    SELECT order_id
    INTO v_order_id
    FROM orders
    WHERE user_id = p_user_id AND status = 'pending'
    LIMIT 1;

    -- Если активный заказ не найден, выбрасываем исключение с сообщением
    IF v_order_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No active order found for the user';
    END IF;

    -- Шаг 3: Вставляем данные о позициях в заказе, сгруппировав одинаковые товары
    INSERT INTO order_details (product_id, product_name, price, discount_price, color_name, size_name, quantity, cover_image_url, total_price)
    SELECT 
        p.product_id,
        p.name,
        oi.price,
        oi.discount,
        pc.color_name,
        ps.size_name,
        SUM(oi.quantity) AS total_quantity, -- Суммируем количество одинаковых товаров
        p.cover_image_url,
        SUM(IFNULL(oi.discount, oi.price) * oi.quantity) AS total_price -- Суммируем стоимость с учетом скидки
    FROM 
        order_items oi
        JOIN products p ON oi.product_id = p.product_id
        LEFT JOIN product_colors pc ON oi.color_id = pc.color_id
        LEFT JOIN product_sizes ps ON oi.size_id = ps.size_id
    WHERE 
        oi.order_id = v_order_id
    GROUP BY 
        p.product_id, p.name, oi.price, oi.discount, pc.color_name, ps.size_name, p.cover_image_url;

    -- Шаг 4: Создаем итоговую выборку, объединяя детали заказа и общую сумму
    SELECT 
        od.*,
        (SELECT 
            SUM(total_price) 
         FROM order_details) AS order_total_price
    FROM 
        order_details od;

    -- Шаг 5: Удаляем временную таблицу после использования
    DROP TEMPORARY TABLE IF EXISTS order_details;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getPaginatedProducts` (IN `limit_count` INT, IN `offset_count` INT)   BEGIN
    -- Если limit_count не равен NULL, применяем LIMIT и OFFSET
    SELECT 
        p.product_id AS id,
        p.name AS product_name,
        p.price,
        p.discount_price,
        p.cover_image_url AS cover,
        GROUP_CONCAT(t.name) AS tags,
        COALESCE(SUM(oi.quantity), 0) AS total_quantity_in_order  -- Количество товара в заказе
    FROM 
        products p
    LEFT JOIN 
        product_tags pt ON p.product_id = pt.product_id
    LEFT JOIN 
        tags t ON pt.tag_id = t.tag_id
    LEFT JOIN 
        order_items oi ON p.product_id = oi.product_id  -- Соединяем с заказами
    LEFT JOIN 
        orders o ON oi.order_id = o.order_id  -- Соединяем с заказами (если нужно, можно добавить фильтрацию по заказу)
    GROUP BY 
        p.product_id
    LIMIT limit_count OFFSET offset_count;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProductCards` (IN `limit_count` INT)   BEGIN
    -- Если limit_count равен NULL, то ограничение не применяется
    IF limit_count IS NOT NULL THEN
        SELECT 
            p.product_id AS id,
            p.name AS product_name,
            p.price,
            p.discount_price,
            p.cover_image_url AS cover,
            GROUP_CONCAT(t.name) AS tags,
            COALESCE(SUM(oi.quantity), 0) AS total_quantity_in_order  -- Количество товара в заказе
        FROM 
            products p
        LEFT JOIN 
            product_tags pt ON p.product_id = pt.product_id
        LEFT JOIN 
            tags t ON pt.tag_id = t.tag_id
        LEFT JOIN 
            order_items oi ON p.product_id = oi.product_id  -- Соединяем с заказами
        LEFT JOIN 
            orders o ON oi.order_id = o.order_id  -- Соединяем с заказами (если нужно, можно добавить фильтрацию по заказу)
        GROUP BY 
            p.product_id
        LIMIT limit_count;
    ELSE
        SELECT 
            p.product_id AS id,
            p.name AS product_name,
            p.price,
            p.discount_price,
            p.cover_image_url AS cover,
            GROUP_CONCAT(t.name) AS tags,
            COALESCE(SUM(oi.quantity), 0) AS total_quantity_in_order  -- Количество товара в заказе
        FROM 
            products p
        LEFT JOIN 
            product_tags pt ON p.product_id = pt.product_id
        LEFT JOIN 
            tags t ON pt.tag_id = t.tag_id
        LEFT JOIN 
            order_items oi ON p.product_id = oi.product_id  -- Соединяем с заказами
        LEFT JOIN 
            orders o ON oi.order_id = o.order_id  -- Соединяем с заказами (если нужно, можно добавить фильтрацию по заказу)
        GROUP BY 
            p.product_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProductColors` (IN `product_id` INT)   BEGIN
    SELECT 
        pc.color_name,
         pc.color_id
    FROM 
        product_colors pc
    WHERE pc.product_id = product_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProductDetails` (IN `product_id` INT)   BEGIN
    SELECT 
        p.name AS product_name,
        p.rating,
        p.description AS short_description,
        p.full_description,
        p.article_number,
        p.stock_quantity,
        c.name AS category_name,
        GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
    FROM 
        products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    LEFT JOIN product_tags pt ON p.product_id = pt.product_id
    LEFT JOIN tags t ON pt.tag_id = t.tag_id
    WHERE p.product_id = product_id
    GROUP BY p.product_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProductImages` (IN `product_id` INT)   BEGIN
   SELECT 
    p.cover_image_url AS image_url
FROM 
    products p
WHERE 
    p.product_id = product_id

UNION ALL

SELECT 
    pi.image_url AS image_url
FROM 
    product_images pi
WHERE 
    pi.product_id = product_id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProductSizes` (IN `product_id` INT)   BEGIN
    SELECT 
        ps.size_name,
        ps.size_id
    FROM 
        product_sizes ps
    WHERE ps.product_id = product_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserInfo` (IN `user_id` INT)   BEGIN
    SELECT user_id, nickname, email, avatar_url, created_at
    FROM users
    WHERE user_id = user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `LoginUser` (IN `nickname` VARCHAR(255), IN `password` VARCHAR(500), OUT `user_id` INT)   BEGIN
    -- Проверка существования пользователя
    SELECT user_id INTO user_id
    FROM users
    WHERE nickname = nickname AND password = password;
    
    IF user_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Неверные никнейм или пароль';
    ELSE
        -- Возвращение данных пользователя
        SELECT user_id, nickname, email, avatar_url, created_at
        FROM users
        WHERE user_id = user_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `login_user` (IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(500), OUT `p_user_id` INT)   BEGIN
    -- Проверяем существование пользователя с указанным email и паролем
    SELECT `user_id` 
    INTO `p_user_id`
    FROM `users`
    WHERE `email` = p_email AND `password` = p_password
    LIMIT 1;

    -- Если пользователь не найден, возвращаем -1
    IF p_user_id IS NULL THEN
        SET p_user_id = -1;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegisterUser` (IN `nickname` VARCHAR(255), IN `email` VARCHAR(255), IN `password` VARCHAR(500), OUT `user_id` INT)   BEGIN
    DECLARE userCount INT;

    -- Проверка уникальности никнейма
    SELECT COUNT(*) INTO userCount
    FROM users
    WHERE nickname = nickname;

    IF userCount > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Никнейм уже существует';
    ELSE
        -- Вставка нового пользователя
        INSERT INTO users (nickname, email, password)
        VALUES (nickname, email, password);

        -- Получение ID нового пользователя
        SELECT LAST_INSERT_ID() INTO user_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `register_user` (IN `p_nickname` VARCHAR(255), IN `p_email` VARCHAR(255), IN `p_password` VARCHAR(500), OUT `p_user_id` INT)   BEGIN
    -- Проверяем уникальность никнейма
    IF EXISTS (SELECT 1 FROM `users` WHERE `nickname` = p_nickname) THEN
        SET p_user_id = -1; -- -1 означает, что никнейм уже используется
    ELSEIF EXISTS (SELECT 1 FROM `users` WHERE `email` = p_email) THEN
        SET p_user_id = -2; -- -2 означает, что email уже используется
    ELSE
        -- Добавляем нового пользователя
        INSERT INTO `users` (`nickname`, `email`, `password`)
        VALUES (p_nickname, p_email, p_password);

        -- Возвращаем ID нового пользователя
        SET p_user_id = LAST_INSERT_ID();
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `brands`
--

INSERT INTO `brands` (`brand_id`, `name`) VALUES
(1, 'Nike'),
(2, 'Adidas'),
(3, 'Puma'),
(4, 'Nike'),
(5, 'Adidas'),
(6, 'Puma'),
(7, 'Nike'),
(8, 'Adidas'),
(9, 'Puma');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Hoodie'),
(2, 'T-shirt'),
(3, 'Shoes'),
(4, 'Hoodie'),
(5, 'T-shirt'),
(6, 'Shoes'),
(7, 'Hoodie'),
(8, 'T-shirt'),
(9, 'Shoes');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','shipped','delivered','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `status`, `created_at`) VALUES
(12, 22, 49.99, 'pending', '2024-12-14 00:34:59');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `full_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL,
  `article_number` varchar(100) NOT NULL,
  `cover_image_url` varchar(255) DEFAULT 'no_photo_product.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `full_description`, `price`, `discount_price`, `brand_id`, `category_id`, `rating`, `stock_quantity`, `article_number`, `cover_image_url`, `created_at`) VALUES
(1, 'Nike Hoodie', 'Comfortable Nike hoodie', 'Full description of Nike hoodie', 59.99, 49.99, 1, 1, 4.50, 100, 'NH001', 'item1.jpg', '2024-12-08 14:53:58'),
(2, 'Adidas T-shirt', 'Adidas cotton T-shirt', 'Full description of Adidas T-shirt', 29.99, NULL, 2, 2, 4.00, 200, 'AT001', 'item2.jpg', '2024-12-08 14:53:58'),
(3, 'Puma Sneakers', 'Stylish Puma sneakers', 'Full description of Puma sneakers', 89.99, 79.99, 3, 3, 4.70, 150, 'PS001', 'item3.jpg', '2024-12-08 14:53:58'),
(114, 'Nike Hoodie', 'Comfortable Nike hoodie', 'Full description of Nike hoodie', 59.99, 49.99, 1, 1, 4.50, 100, '100001', 'item1.jpg', '2024-12-08 11:53:58'),
(115, 'Adidas T-shirt', 'Adidas cotton T-shirt', 'Full description of Adidas T-shirt', 29.99, NULL, 2, 2, 4.00, 200, '100002', 'item2.jpg', '2024-12-08 11:53:58'),
(116, 'Puma Sneakers', 'Stylish Puma sneakers', 'Full description of Puma sneakers', 89.99, 79.99, 3, 3, 4.70, 150, '100003', 'item3.jpg', '2024-12-08 11:53:58'),
(117, 'Reebok Running Shoes', 'Comfortable Reebok running shoes', 'Full description of Reebok running shoes', 75.00, NULL, 4, 3, 4.30, 120, '100004', 'item1.jpg', '2024-12-08 12:00:00'),
(118, 'Under Armour Shorts', 'Under Armour athletic shorts', 'Full description of Under Armour shorts', 25.50, NULL, 5, 2, 4.20, 200, '100005', 'item2.jpg', '2024-12-08 12:05:00'),
(119, 'Puma Jacket', 'Warm Puma jacket', 'Full description of Puma jacket', 95.99, 85.99, 3, 1, 4.60, 50, '100006', 'item3.jpg', '2024-12-08 12:10:00'),
(120, 'Nike Running Shoes', 'Lightweight Nike running shoes', 'Full description of Nike running shoes', 120.00, 100.00, 1, 3, 4.80, 80, '100007', 'item1.jpg', '2024-12-08 12:15:00'),
(121, 'Adidas Hoodie', 'Cozy Adidas hoodie', 'Full description of Adidas hoodie', 65.00, NULL, 2, 1, 4.40, 75, '100008', 'item2.jpg', '2024-12-08 12:20:00'),
(122, 'New Balance Sneakers', 'New Balance casual sneakers', 'Full description of New Balance sneakers', 110.00, NULL, 6, 3, 4.50, 60, '100009', 'item3.jpg', '2024-12-08 12:25:00'),
(123, 'Reebok T-shirt', 'Soft cotton Reebok T-shirt', 'Full description of Reebok T-shirt', 35.99, 30.00, 4, 2, 4.00, 180, '100010', 'item1.jpg', '2024-12-08 12:30:00'),
(124, 'Nike Sweatpants', 'Nike comfortable sweatpants', 'Full description of Nike sweatpants', 49.99, NULL, 1, 2, 4.30, 150, '100011', 'item2.jpg', '2024-12-08 12:35:00'),
(125, 'Adidas Sneakers', 'Adidas stylish sneakers', 'Full description of Adidas sneakers', 85.00, 75.00, 2, 3, 4.50, 120, '100012', 'item3.jpg', '2024-12-08 12:40:00'),
(126, 'Under Armour Hoodie', 'Under Armour comfortable hoodie', 'Full description of Under Armour hoodie', 70.00, NULL, 5, 1, 4.40, 60, '100013', 'item1.jpg', '2024-12-08 12:45:00'),
(127, 'Puma Backpack', 'Puma stylish backpack', 'Full description of Puma backpack', 45.00, NULL, 3, 4, 4.60, 150, '100014', 'item2.jpg', '2024-12-08 12:50:00'),
(128, 'Reebok Hoodie', 'Cozy Reebok hoodie', 'Full description of Reebok hoodie', 60.00, 50.00, 4, 1, 4.10, 100, '100015', 'item3.jpg', '2024-12-08 12:55:00'),
(129, 'Adidas Sweatpants', 'Comfortable Adidas sweatpants', 'Full description of Adidas sweatpants', 55.00, NULL, 2, 2, 4.40, 200, '100016', 'item1.jpg', '2024-12-08 13:00:00'),
(130, 'Puma T-shirt', 'Stylish Puma T-shirt', 'Full description of Puma T-shirt', 30.00, NULL, 3, 2, 4.30, 180, '100017', 'item2.jpg', '2024-12-08 13:05:00'),
(131, 'New Balance Shorts', 'New Balance athletic shorts', 'Full description of New Balance shorts', 40.00, NULL, 6, 2, 4.50, 90, '100018', 'item3.jpg', '2024-12-08 13:10:00'),
(132, 'Nike Cap', 'Comfortable Nike cap', 'Full description of Nike cap', 20.00, NULL, 1, 4, 4.60, 150, '100019', 'item1.jpg', '2024-12-08 13:15:00'),
(133, 'Reebok Sneakers', 'Stylish Reebok sneakers', 'Full description of Reebok sneakers', 85.00, 75.00, 4, 3, 4.70, 110, '100020', 'item2.jpg', '2024-12-08 13:20:00'),
(134, 'Under Armour Cap', 'Sporty Under Armour cap', 'Full description of Under Armour cap', 22.00, NULL, 5, 4, 4.20, 200, '100021', 'item3.jpg', '2024-12-08 13:25:00'),
(135, 'Puma Socks', 'Comfortable Puma socks', 'Full description of Puma socks', 10.00, NULL, 3, 4, 4.30, 500, '100022', 'item1.jpg', '2024-12-08 13:30:00'),
(136, 'Adidas Cap', 'Stylish Adidas cap', 'Full description of Adidas cap', 18.00, NULL, 2, 4, 4.00, 300, '100023', 'item2.jpg', '2024-12-08 13:35:00'),
(137, 'Nike Backpack', 'Durable Nike backpack', 'Full description of Nike backpack', 50.00, NULL, 1, 4, 4.40, 100, '100024', 'item3.jpg', '2024-12-08 13:40:00'),
(138, 'Reebok Sweatshirt', 'Reebok warm sweatshirt', 'Full description of Reebok sweatshirt', 65.00, NULL, 4, 1, 4.20, 120, '100025', 'item1.jpg', '2024-12-08 13:45:00'),
(139, 'Adidas Running Shoes', 'Comfortable Adidas running shoes', 'Full description of Adidas running shoes', 95.00, 85.00, 2, 3, 4.50, 140, '100026', 'item2.jpg', '2024-12-08 13:50:00'),
(140, 'Puma Tracksuit', 'Puma tracksuit for athletes', 'Full description of Puma tracksuit', 70.00, NULL, 3, 2, 4.40, 100, '100027', 'item3.jpg', '2024-12-08 13:55:00'),
(141, 'Nike T-shirt', 'Breathable Nike T-shirt', 'Full description of Nike T-shirt', 25.00, NULL, 1, 2, 4.60, 250, '100028', 'item1.jpg', '2024-12-08 14:00:00'),
(142, 'Adidas Jacket', 'Warm Adidas jacket', 'Full description of Adidas jacket', 90.00, NULL, 2, 1, 4.50, 150, '100029', 'item2.jpg', '2024-12-08 14:05:00'),
(143, 'Puma Gloves', 'Winter Puma gloves', 'Full description of Puma gloves', 15.00, NULL, 3, 4, 4.20, 200, '100030', 'item3.jpg', '2024-12-08 14:10:00');

-- --------------------------------------------------------

--
-- Структура таблицы `product_colors`
--

CREATE TABLE `product_colors` (
  `color_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `color_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `product_colors`
--

INSERT INTO `product_colors` (`color_id`, `product_id`, `color_name`) VALUES
(1, 1, 'Red'),
(2, 1, 'Black'),
(3, 2, 'White'),
(4, 2, 'Gray'),
(5, 3, 'Blue'),
(6, 3, 'Black');

-- --------------------------------------------------------

--
-- Структура таблицы `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT 'no_photo_product.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_url`) VALUES
(5, 2, 'item1.jpg'),
(6, 2, 'item2.jpg'),
(8, 2, 'item3.jpg'),
(9, 2, 'item4.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `product_sizes`
--

CREATE TABLE `product_sizes` (
  `size_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `product_sizes`
--

INSERT INTO `product_sizes` (`size_id`, `product_id`, `size_name`) VALUES
(1, 1, 'S'),
(2, 1, 'M'),
(3, 2, 'L'),
(4, 2, 'XL'),
(5, 3, '42'),
(6, 3, '44');

-- --------------------------------------------------------

--
-- Структура таблицы `product_tags`
--

CREATE TABLE `product_tags` (
  `product_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `product_tags`
--

INSERT INTO `product_tags` (`product_id`, `tag_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `tags`
--

INSERT INTO `tags` (`tag_id`, `name`) VALUES
(1, 'sale'),
(2, 'hot');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(500) NOT NULL,
  `avatar_url` varchar(255) DEFAULT 'default_user_avatar.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `nickname`, `email`, `password`, `avatar_url`, `created_at`) VALUES
(22, 'root@root', 'root@root', '$2y$10$KkwuiI6Isbqul76.c807bOtPhA1tHgfPfOvYMM1.7v/i20SaLX/xO', 'default_user_avatar.png', '2024-12-13 23:12:56'),
(23, 'xer@xer', 'xer@xer', '$2y$10$g/ZcZgHQlqk5HDbPyoGjG.gfgQqrCONLmBXTu50FLRGOvj4D5NYbe', 'default_user_avatar.png', '2024-12-13 23:50:39'),
(24, 'lolya@li', 'lolya@li', '$2y$10$NTYyyWDPUbrHdadu.Ge33uQUm1gaygAFJqb0UhHS3pZQJdQ4JNFYO', NULL, '2024-12-14 00:28:11');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `color_id` (`color_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `article_number` (`article_number`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `product_colors`
--
ALTER TABLE `product_colors`
  ADD PRIMARY KEY (`color_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`size_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_tags`
--
ALTER TABLE `product_tags`
  ADD PRIMARY KEY (`product_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Индексы таблицы `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT для таблицы `product_colors`
--
ALTER TABLE `product_colors`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`color_id`) REFERENCES `product_colors` (`color_id`),
  ADD CONSTRAINT `order_items_ibfk_4` FOREIGN KEY (`size_id`) REFERENCES `product_sizes` (`size_id`);

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Ограничения внешнего ключа таблицы `product_colors`
--
ALTER TABLE `product_colors`
  ADD CONSTRAINT `product_colors_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Ограничения внешнего ключа таблицы `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Ограничения внешнего ключа таблицы `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Ограничения внешнего ключа таблицы `product_tags`
--
ALTER TABLE `product_tags`
  ADD CONSTRAINT `product_tags_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `product_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
