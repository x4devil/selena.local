-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июн 29 2016 г., 01:31
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `selena`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(3, 'Роботы'),
(4, 'Вертолеты');

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extend` varchar(5) NOT NULL,
  `id_product` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`id`, `extend`, `id_product`) VALUES
(1, '.jpg', 2),
(2, '.jpg', 2),
(3, '.jpg', 3),
(4, '.jpg', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `price` double NOT NULL,
  `id_category` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_category` (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `id_category`) VALUES
(2, 'Робот 1', 1000, 3),
(3, 'Робот 2', 2000, 3),
(4, 'Робот 3', 1000, 3),
(5, 'Робот 4', 1000, 3),
(6, 'Робот 5', 1000, 3),
(7, 'Робот 6', 1000, 3),
(8, 'Робот 7', 1000, 3),
(9, 'Робот 8', 1000, 3),
(10, 'Робот 9', 1000, 3),
(11, 'Робот 10', 1000, 3),
(40, 'Робот 11', 1000, 3),
(41, 'Робот 12', 1000, 3),
(42, 'Робот 13', 1000, 3),
(43, 'Робот 14', 1000, 3),
(44, 'Робот 15', 1000, 3),
(45, 'Робот 16', 1000, 3),
(46, 'Робот 17', 1000, 3),
(54, 'Робот 18', 1000, 3),
(55, 'Робот 19', 1000, 3),
(56, 'Робот 20', 1000, 3),
(57, 'Робот 21', 1000, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(1, 'admin', 'admin', 'admin@admin.ru', 'admin@admin.ru', 1, 'g2nknsuo75wkcksw4scowwk8ckocgsw', '$2y$13$g2nknsuo75wkcksw4scowuA/AP6UwIcJ129m8TLmf5Eq41qP8SeCW', '2016-06-29 00:46:39', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_request`
--

CREATE TABLE IF NOT EXISTS `user_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `lastname` varchar(300) NOT NULL,
  `company` varchar(300) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `middlename` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Дамп данных таблицы `user_request`
--

INSERT INTO `user_request` (`id`, `name`, `lastname`, `company`, `phone`, `email`, `created`, `status`, `middlename`) VALUES
(1, 'Иван', 'Иванов', 'ООО Рога и копыта', '123123123123', 'фывфвфывфыв', '2011-01-01 01:00:00', 2, 'Иванович'),
(2, 'Петр', 'Петров', 'ООО 1', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(4, 'Петр', 'Петров', 'ООО 3', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(5, 'Петр', 'Петров', 'ООО 4', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(6, 'Петр', 'Петров', 'ООО 5', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(7, 'Петр', 'Петров', 'ООО 6', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(8, 'Петр', 'Петров', 'ООО 7', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(9, 'Петр', 'Петров', 'ООО 8', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(10, 'Петр', 'Петров', 'ООО 9', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(11, 'Петр', 'Петров', 'ООО 10', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(12, 'Петр', 'Петров', 'ООО 11', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(13, 'Петр', 'Петров', 'ООО 13', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(14, 'Петр', 'Петров', 'ООО 14', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(15, 'Петр', 'Петров', 'ООО 15', '89888888888888888888', 'рпорпрп', '2016-06-24 00:00:00', 0, 'Петрович'),
(16, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-28 23:30:31', 0, 'Иванович'),
(17, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-28 23:31:55', 0, 'Иванович'),
(18, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-28 23:34:02', 0, 'Иванович'),
(19, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-28 23:38:05', 0, 'Иванович'),
(20, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-28 23:41:58', 0, 'Иванович'),
(21, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-28 23:50:29', 0, 'Иванович'),
(23, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:02:48', 0, 'Иванович'),
(24, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:03:31', 0, 'Иванович'),
(25, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:03:40', 0, 'Иванович'),
(26, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:04:07', 0, 'Иванович'),
(27, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:05:43', 0, 'Иванович'),
(28, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:06:41', 0, 'Иванович'),
(29, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:08:02', 0, 'Иванович'),
(30, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:08:41', 0, 'Иванович'),
(31, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:08:51', 0, 'Иванович'),
(32, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:09:00', 0, 'Иванович'),
(33, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:09:33', 0, 'Иванович'),
(34, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:09:35', 0, 'Иванович'),
(35, 'Петр', 'Петров', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:11:37', 0, 'Петрович'),
(36, 'Петр', 'Петров', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:22:22', 0, 'Петрович'),
(37, 'Петр', 'Петров', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:23:29', 0, 'Петрович'),
(38, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:42:41', 0, 'Иванович'),
(39, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:45:14', 1, 'Иванович'),
(40, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:45:58', 0, 'Иванович'),
(41, 'Петр', 'Петров', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:46:21', 0, 'Петрович'),
(42, 'Петр', 'Петров', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:48:38', 0, 'Петрович'),
(43, 'Петр', 'Петров', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:49:42', 0, 'Петрович'),
(44, 'Петр', 'Петров', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:50:11', 0, 'Петрович'),
(45, 'Петр', 'Петров', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:50:52', 0, 'Петрович'),
(46, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:52:52', 0, 'Иванович'),
(47, 'Иван', 'Иванов', '', '8999898989989', 'x4devil@mail.ru', '2016-06-29 00:53:51', 0, 'Иванович'),
(48, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-29 00:55:18', 0, 'Иванович'),
(49, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-29 01:00:27', 0, 'Иванович'),
(50, 'Иван', 'Иванов', '', '8999898989989', 'mail@mail.ru', '2016-06-29 01:01:38', 0, 'Иванович');

-- --------------------------------------------------------

--
-- Структура таблицы `user_request_product`
--

CREATE TABLE IF NOT EXISTS `user_request_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` double NOT NULL,
  `amount` smallint(6) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_user_request` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_product` (`id_product`),
  KEY `id_user_request` (`id_user_request`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Дамп данных таблицы `user_request_product`
--

INSERT INTO `user_request_product` (`id`, `price`, `amount`, `id_product`, `id_user_request`) VALUES
(1, 1000, 10, 2, 1),
(2, 1000, 10, 2, 1),
(3, 1002, 10, 3, 1),
(4, 1000, 12, 7, 21),
(5, 1000, 3, 9, 21),
(8, 1000, 12, 7, 23),
(9, 1000, 3, 9, 23),
(10, 1000, 12, 7, 24),
(11, 1000, 3, 9, 24),
(12, 1000, 12, 7, 25),
(13, 1000, 3, 9, 25),
(14, 1000, 12, 7, 26),
(15, 1000, 3, 9, 26),
(16, 1000, 12, 7, 27),
(17, 1000, 3, 9, 27),
(18, 1000, 12, 7, 28),
(19, 1000, 3, 9, 28),
(20, 1000, 12, 7, 29),
(21, 1000, 3, 9, 29),
(22, 1000, 12, 7, 30),
(23, 1000, 3, 9, 30),
(24, 1000, 12, 7, 31),
(25, 1000, 3, 9, 31),
(26, 1000, 12, 7, 32),
(27, 1000, 3, 9, 32),
(28, 1000, 12, 7, 33),
(29, 1000, 3, 9, 33),
(30, 1000, 12, 7, 34),
(31, 1000, 3, 9, 34),
(32, 1000, 12, 7, 35),
(33, 1000, 3, 9, 35),
(34, 1000, 12, 7, 36),
(35, 1000, 3, 9, 36),
(36, 1000, 12, 7, 37),
(37, 1000, 10, 2, 38),
(38, 1000, 10, 57, 38),
(39, 1000, 10, 6, 39),
(40, 1000, 10, 6, 41),
(41, 1000, 3, 2, 42),
(42, 1000, 3, 2, 43),
(43, 1000, 3, 2, 45),
(44, 1000, 10, 2, 46),
(45, 1000, 5, 2, 47),
(46, 1000, 3, 2, 48),
(47, 1000, 7, 46, 49),
(48, 1000, 7, 46, 50);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_request_product`
--
ALTER TABLE `user_request_product`
  ADD CONSTRAINT `user_request_product_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_request_product_ibfk_2` FOREIGN KEY (`id_user_request`) REFERENCES `user_request` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
