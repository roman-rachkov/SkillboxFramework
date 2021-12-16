-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 17 2019 г., 08:42
-- Версия сервера: 5.7.20-log
-- Версия PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `eshop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `alias`, `title`, `sort`) VALUES
(1, 'woman', 'Женщины', 0),
(2, 'man', 'Мужчины', 1),
(3, 'children', 'Дети', 3),
(4, 'accessories', 'Аксессуары', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) NOT NULL,
  `thirdname` varchar(255) NOT NULL,
  `phone` decimal(40,0) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `name`, `surname`, `thirdname`, `phone`, `adress`, `email`) VALUES
(1, 'test', 'Testov', 'testovich', '123123123123131', 'г. Vlad, ул. street, д.32, кв. 123', 'test@test.ru'),
(2, 'фывфыв', 'фывфыфыв', 'фывфыв', '0', '', 'фывфыв'),
(4, 'qweqwe', 'werwer', 'qweqe', '0', 'г. qweqe, ул. qweqwe, д.123, кв. 123', 'qweqwe'),
(5, '123', '123', '123', '123', '', '123');

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `new` tinyint(1) NOT NULL DEFAULT '0',
  `sale` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `title`, `price`, `photo`, `new`, `sale`) VALUES
(1, 'Платье со складками. 1', 2999, 'good-1.jpg', 1, 0),
(2, 'Платье со складками. 2', 4999, 'good-2.jpg', 0, 0),
(3, 'Платье со складками. 4', 6999, 'good-1.jpg', 1, 0),
(4, 'Платье со складками. 1', 499, 'good-2.jpg', 0, 1),
(5, 'Платье со складками. 1', 1999, 'good-1.jpg', 1, 0),
(6, 'Платье со складками. 2', 12299, 'good-2.jpg', 0, 1),
(7, 'Платье со складками. 4', 15299, 'good-1.jpg', 1, 1),
(8, 'Платье со складками. 1', 1499, 'good-2.jpg', 0, 1),
(9, 'Платье со складками. 1', 2999, 'good-1.jpg', 1, 0),
(10, 'Платье со складками. 2', 4999, 'good-2.jpg', 0, 0),
(11, 'Платье со складками. 4', 6999, 'good-1.jpg', 1, 0),
(12, 'Платье со складками. 1', 499, 'good-2.jpg', 0, 0),
(13, 'Платье со складками. 1', 1999, 'good-1.jpg', 1, 0),
(14, 'Платье со складками. 2', 12299, 'good-2.jpg', 0, 0),
(15, 'Платье со складками. 4', 15299, 'good-1.jpg', 1, 0),
(16, 'Платье со складками. 1', 1499, 'good-2.jpg', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `goods_categories`
--

CREATE TABLE `goods_categories` (
  `goods_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `goods_categories`
--

INSERT INTO `goods_categories` (`goods_id`, `categories_id`) VALUES
(1, 1),
(5, 1),
(6, 1),
(11, 1),
(2, 2),
(3, 2),
(4, 2),
(10, 2),
(12, 2),
(2, 3),
(8, 3),
(9, 3),
(14, 3),
(15, 3),
(16, 3),
(1, 4),
(5, 4),
(7, 4),
(13, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `title`) VALUES
(1, 'administrator', 'Администратор'),
(2, 'operator', 'Оператор');

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `required_lvl` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `title`, `path`, `sort`, `required_lvl`) VALUES
(1, 'Главная', '/', 0, 0),
(2, 'Новинки', '/?new=true', 1, 0),
(3, 'Sale', '/?sale=true', 3, 0),
(4, 'Доставка', '/delivery', 4, 0),
(5, 'Товары', '/admin/goods', 1, 1),
(6, 'Заказы', '/admin', 0, 2),
(7, 'Выйти', '/admin/logout', 4, 2),
(8, 'Настройки', '/admin/settings', 3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `delivery` enum('pickup','delivery') DEFAULT 'pickup',
  `payment` enum('cash','card') DEFAULT 'cash',
  `comment` text,
  `status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `delivery`, `payment`, `comment`, `status`) VALUES
(1, 'delivery', 'card', '', 1),
(2, 'pickup', 'cash', '', 0),
(3, 'delivery', 'cash', '', 1),
(4, 'pickup', 'cash', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `orders_clients`
--

CREATE TABLE `orders_clients` (
  `orders_id` int(11) NOT NULL,
  `clients_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders_clients`
--

INSERT INTO `orders_clients` (`orders_id`, `clients_id`) VALUES
(1, 1),
(2, 2),
(3, 4),
(4, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `orders_goods`
--

CREATE TABLE `orders_goods` (
  `orders_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders_goods`
--

INSERT INTO `orders_goods` (`orders_id`, `goods_id`) VALUES
(1, 4),
(3, 4),
(4, 12),
(2, 16);

-- --------------------------------------------------------

--
-- Структура таблицы `pluses`
--

CREATE TABLE `pluses` (
  `id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `setting` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `name`, `setting`) VALUES
(1, 'minimal_price_free_delivery', '2000'),
(2, 'delivery_price', '280');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'admin@test.ru', '$2y$10$edfWy/10d/hBN4tCblAtPu7YzXNiUJBpKyeUX6zq7OryCC0Is/rY.'),
(2, 'operator@test.ru', '$2y$10$iaSATIWARNuUsybpccOEQeh25hIGoHa25w.w/pSjJ5yHHbMNUXYOW');

-- --------------------------------------------------------

--
-- Структура таблицы `users_groups`
--

CREATE TABLE `users_groups` (
  `users_id` int(11) NOT NULL,
  `groups_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_groups`
--

INSERT INTO `users_groups` (`users_id`, `groups_id`) VALUES
(1, 1),
(1, 2),
(2, 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `goods_categories`
--
ALTER TABLE `goods_categories`
  ADD PRIMARY KEY (`goods_id`,`categories_id`),
  ADD KEY `fk_goods_has_categories_categories1_idx` (`categories_id`),
  ADD KEY `fk_goods_has_categories_goods_idx` (`goods_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders_clients`
--
ALTER TABLE `orders_clients`
  ADD PRIMARY KEY (`orders_id`,`clients_id`),
  ADD KEY `fk_orders_has_clients_clients1_idx` (`clients_id`),
  ADD KEY `fk_orders_has_clients_orders1_idx` (`orders_id`);

--
-- Индексы таблицы `orders_goods`
--
ALTER TABLE `orders_goods`
  ADD PRIMARY KEY (`orders_id`,`goods_id`),
  ADD KEY `fk_orders_has_goods_goods1_idx` (`goods_id`),
  ADD KEY `fk_orders_has_goods_orders1_idx` (`orders_id`);

--
-- Индексы таблицы `pluses`
--
ALTER TABLE `pluses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Индексы таблицы `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`users_id`,`groups_id`),
  ADD KEY `fk_users_has_groups_groups1_idx` (`groups_id`),
  ADD KEY `fk_users_has_groups_users1_idx` (`users_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `pluses`
--
ALTER TABLE `pluses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `goods_categories`
--
ALTER TABLE `goods_categories`
  ADD CONSTRAINT `fk_goods_has_categories_categories1` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_goods_has_categories_goods` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `orders_clients`
--
ALTER TABLE `orders_clients`
  ADD CONSTRAINT `fk_orders_has_clients_clients1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orders_has_clients_orders1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `orders_goods`
--
ALTER TABLE `orders_goods`
  ADD CONSTRAINT `fk_orders_has_goods_goods1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orders_has_goods_orders1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_has_groups_groups1` FOREIGN KEY (`groups_id`) REFERENCES `groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_has_groups_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
