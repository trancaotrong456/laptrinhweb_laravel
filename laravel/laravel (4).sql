-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th6 02, 2026 lúc 08:27 AM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `laravel`
--
CREATE DATABASE IF NOT EXISTS `laravel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `laravel`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_foreign` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(12,2) NOT NULL,
  `min_order_value` decimal(12,2) DEFAULT NULL,
  `max_discount` decimal(12,2) DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `usage_limit` int UNSIGNED DEFAULT NULL,
  `used_count` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order_value`, `max_discount`, `starts_at`, `ends_at`, `usage_limit`, `used_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME10', 'percent', 10.00, 100000.00, 50000.00, NULL, NULL, NULL, 0, 1, '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(2, 'GIAM30K', 'fixed', 30000.00, 200000.00, NULL, NULL, NULL, NULL, 0, 1, '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(3, 'FREE02062026', 'fixed', 100000.00, NULL, NULL, NULL, NULL, 100, 0, 1, '2026-06-01 23:44:02', '2026-06-02 00:12:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_05_01_000000_create_users_table', 1),
(2, '2026_06_01_000002_create_categories_table', 1),
(3, '2026_06_01_000003_create_products_table', 1),
(4, '2026_06_01_000004_create_user_cart_items_table', 1),
(5, '2026_06_01_000005_create_coupons_table', 1),
(6, '2026_06_01_000006_create_user_saved_coupons_table', 1),
(7, '2026_06_01_000007_create_product_reviews_table', 1),
(8, '2026_06_01_000008_create_posts_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Còn hàng',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `quantity`, `description`, `image`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hamburger', 121618.00, 78, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(2, 'Khoai tay chien', 188220.00, 87, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(3, 'Tra sua', 297449.00, 68, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(4, 'Pizza', 35203.00, 43, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(5, 'Pizza', 183287.00, 40, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(6, 'Banh mi', 288884.00, 88, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(7, 'Khoai tay chien', 19882.00, 25, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(8, 'Matcha', 105537.00, 69, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(9, 'Ca phe', 296474.00, 2, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(10, 'Ca phe', 40688.00, 39, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(11, 'Tra sua', 290758.00, 61, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(12, 'Hamburger', 295104.00, 21, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(13, 'Ga ran', 6246.00, 98, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(14, 'Matcha', 470013.00, 48, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(15, 'Banh mi', 32711.00, 59, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(16, 'Hamburger', 486796.00, 67, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(17, 'Banh mi', 327520.00, 53, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(18, 'Banh mi', 237140.00, 60, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(19, 'Tra sua', 478957.00, 8, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58'),
(20, 'Khoai tay chien', 416241.00, 68, NULL, 'default.jpg', 1, 'Còn hàng', '2026-06-01 22:51:58', '2026-06-01 22:51:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_reviews_product_id_user_id_unique` (`product_id`,`user_id`),
  KEY `product_reviews_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` tinyint NOT NULL DEFAULT '0',
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `phone`, `address`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$fbYo7TPqSQTIAuuqBHJANu0ElsdUj/DbIb0aF1NFMa6uAn1fBROf.', NULL, '2026-04-16 04:38:31', '2026-06-01 22:56:23', 1, NULL, NULL),
(2, 'Khách Hàng', 'khachhang@gmail.com', NULL, '$2y$10$i2r/hQk81fO0G9vH7RkHLe3fV2G8P8W8x8Y8z8K8M8N8O8P8Q8R8S', NULL, '2026-04-16 04:38:31', '2026-04-16 04:38:31', 0, NULL, NULL),
(7, 'Trần Cao Trọng', 'trancaotrong456@gmail.com', NULL, '$2y$10$coZ2H48fS78sHwZOnKxSLe2O6F6lRzK9wH7xH6N7k7V7K7a7e7g7.', 'h57sBTplr63QcX4DaWoEbiWWq9g3rep6VanJZCU8Spe5R11bF5qnrR9EYsER', '2026-05-06 14:11:14', '2026-05-06 14:11:14', 0, '0915780867', 'HCM'),
(6, 'Ngô Bá Thắng', 'nguyenvana@gmail.com', NULL, '$2y$10$coZ2H48fS78sHwZOnKxSLe2O6F6lRzK9wH7xH6N7k7V7K7a7e7g7.', NULL, '2026-04-23 05:15:51', '2026-04-23 05:15:51', 0, NULL, '120 yên lãng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_cart_items`
--

DROP TABLE IF EXISTS `user_cart_items`;
CREATE TABLE IF NOT EXISTS `user_cart_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `product_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_cart_items_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `user_cart_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_cart_items`
--

INSERT INTO `user_cart_items` (`id`, `user_id`, `product_id`, `product_name`, `product_price`, `product_image`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 'Matcha', 105537.00, 'default.jpg', 1, '2026-06-01 23:21:02', '2026-06-01 23:21:02'),
(2, 1, 20, 'Khoai tay chien', 416241.00, 'default.jpg', 1, '2026-06-02 01:17:24', '2026-06-02 01:17:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_saved_coupons`
--

DROP TABLE IF EXISTS `user_saved_coupons`;
CREATE TABLE IF NOT EXISTS `user_saved_coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_saved_coupons_user_id_coupon_id_unique` (`user_id`,`coupon_id`),
  KEY `user_saved_coupons_coupon_id_foreign` (`coupon_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_saved_coupons`
--

INSERT INTO `user_saved_coupons` (`id`, `user_id`, `coupon_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2026-06-02 00:12:23', '2026-06-02 00:12:23'),
(2, 1, 2, '2026-06-02 00:12:27', '2026-06-02 00:12:27'),
(3, 1, 1, '2026-06-02 00:12:30', '2026-06-02 00:12:30');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
