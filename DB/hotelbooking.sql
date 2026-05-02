-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 02, 2026 at 03:18 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotelbooking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `discount_applied` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','confirmed','rejected','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `receiver_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000003_create_rooms_table', 1),
(5, '2024_01_01_000004_create_services_table', 1),
(6, '2024_01_01_000005_create_bookings_table', 1),
(7, '2024_01_01_000006_create_booking_services_table', 1),
(8, '2024_01_01_000007_create_messages_table', 1),
(9, '2024_01_01_000008_create_premium_subscriptions_table', 1),
(10, '2024_05_02_000009_create_personal_access_tokens_table', 2),
(11, '2026_05_02_134325_create_contact_messages_table', 3),
(12, '2026_05_02_134728_create_premium_plans_table', 4),
(13, '2026_05_02_135539_add_min_bookings_to_premium_plans_table', 5),
(14, '2026_05_02_142439_add_completed_bookings_count_to_users_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth-token', 'f2691aa95f97e7eddf09c671b3e429cfe3bb5deca9d050a96960b62eb04e02fe', '[\"*\"]', '2026-05-01 22:21:27', NULL, '2026-05-01 22:18:37', '2026-05-01 22:21:27'),
(2, 'App\\Models\\User', 3, 'auth-token', 'd5a97b9035972dbaa88eeaa7140e9c1f71f3b4fdf4d54da0e46201f03057c03e', '[\"*\"]', '2026-05-01 22:21:46', NULL, '2026-05-01 22:21:46', '2026-05-01 22:21:46'),
(3, 'App\\Models\\User', 1, 'auth-token', '6df58d4119a18366e1cfec3944f0854e5c5ed9c66125cb083e5c81265013c19c', '[\"*\"]', NULL, NULL, '2026-05-01 22:30:52', '2026-05-01 22:30:52'),
(4, 'App\\Models\\User', 3, 'auth-token', 'aa89935d7127151a1cffeff7c1c4e38a8737e9ba53552d561281d75eb7bf5473', '[\"*\"]', '2026-05-01 23:29:05', NULL, '2026-05-01 22:31:44', '2026-05-01 23:29:05'),
(5, 'App\\Models\\User', 1, 'auth-token', '61179df8ba8aa398b788fef49e820f2a37c96d9477dfba2373972a5377bfb819', '[\"*\"]', NULL, NULL, '2026-05-01 22:43:10', '2026-05-01 22:43:10'),
(6, 'App\\Models\\User', 1, 'auth-token', '9ec73141eab9f2b8b234adbbc4928a98f366fd6e2f5d0c1b970f9c743ae87b1c', '[\"*\"]', '2026-05-02 09:06:00', NULL, '2026-05-02 07:19:19', '2026-05-02 09:06:00'),
(7, 'App\\Models\\User', 3, 'auth-token', '70f1fd20b22c6342e49937a8e1a7f4eaf00e918034c889901dab6569d6bb5f04', '[\"*\"]', '2026-05-02 09:08:10', NULL, '2026-05-02 07:20:59', '2026-05-02 09:08:10'),
(8, 'App\\Models\\User', 2, 'auth-token', 'c072072b211bffaf995a1d71152c77bed37cab868edde70cdf460d23f1084c2a', '[\"*\"]', '2026-05-02 09:07:58', NULL, '2026-05-02 09:06:32', '2026-05-02 09:07:58'),
(9, 'App\\Models\\User', 1, 'auth-token', '972d4e0881b08a9910fc1ede5083c2f841475a45a65dea6b98456248c2e22d99', '[\"*\"]', '2026-05-02 09:12:34', NULL, '2026-05-02 09:08:02', '2026-05-02 09:12:34'),
(10, 'App\\Models\\User', 2, 'auth-token', '4ffc13cb84ec103bf0c0e02f3abc42c5b65fdfa5ba0af3765aa60b62610f521d', '[\"*\"]', '2026-05-02 09:12:58', NULL, '2026-05-02 09:08:18', '2026-05-02 09:12:58'),
(11, 'App\\Models\\User', 1, 'auth-token', 'dfe20bf972557d84b8e0b9884e5c1f29dde7f911d17ec9e39f2521e79e6abfc6', '[\"*\"]', '2026-05-02 09:15:45', NULL, '2026-05-02 09:12:52', '2026-05-02 09:15:45'),
(12, 'App\\Models\\User', 1, 'auth-token', '6ea60f06228896ab09a8a0cdad7c0e002fccd98159127f2806ffa8648d8de46b', '[\"*\"]', '2026-05-02 09:17:01', NULL, '2026-05-02 09:15:57', '2026-05-02 09:17:01'),
(13, 'App\\Models\\User', 1, 'auth-token', '9e71b8071b8b618a9c062193238c61c14d17444960eb70ffb0a4c47cf29a2f02', '[\"*\"]', '2026-05-02 09:17:54', NULL, '2026-05-02 09:17:43', '2026-05-02 09:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `premium_plans`
--

CREATE TABLE `premium_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tier_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_bookings` int NOT NULL DEFAULT '0',
  `discount_percentage` int NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `benefits` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `premium_plans`
--

INSERT INTO `premium_plans` (`id`, `name`, `tier_key`, `min_bookings`, `discount_percentage`, `price`, `benefits`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Silver Member', 'silver', 3, 5, '0.00', '[\"5% off all bookings\", \"Priority support\"]', 1, '2026-05-02 07:48:18', '2026-05-02 07:56:15'),
(2, 'Gold Member', 'gold', 10, 10, '0.00', '[\"10% off all bookings\", \"Free upgrades\", \"Late check-out\"]', 1, '2026-05-02 07:48:18', '2026-05-02 07:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `premium_subscriptions`
--

CREATE TABLE `premium_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tier` enum('silver','gold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `premium_subscriptions`
--

INSERT INTO `premium_subscriptions` (`id`, `user_id`, `tier`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'gold', '2026-05-02', '2026-06-02', 1, '2026-05-02 08:26:29', '2026-05-02 08:26:29');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `room_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type` enum('standard','deluxe','suite','presidential') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `capacity` int NOT NULL,
  `amenities` json DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','occupied','maintenance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `room_type`, `description`, `price_per_night`, `capacity`, `amenities`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, '101', 'standard', 'Comfortable standard room with all basic amenities. Perfect for solo travelers or couples.', '100.00', 2, '\"[\\\"WiFi\\\",\\\"TV\\\",\\\"Air Conditioning\\\",\\\"Private Bathroom\\\"]\"', '/storage/rooms/3RjVECZVS4cELF7ErUQbfr2d9T9jrBd9zZ8KUnUX.jpg', 'available', '2026-05-01 22:01:14', '2026-05-01 23:19:18'),
(2, '102', 'standard', 'Spacious standard room with city view. Features modern furnishings and comfortable bedding.', '120.00', 2, '\"[\\\"WiFi\\\",\\\"TV\\\",\\\"Air Conditioning\\\",\\\"Private Bathroom\\\",\\\"City View\\\"]\"', '/storage/rooms/fTI9a0Zs2GSSQc0X4iJCZrBZhzFai9POhgMtg4ZB.jpg', 'available', '2026-05-01 22:01:14', '2026-05-01 23:19:29'),
(3, '201', 'deluxe', 'Elegant deluxe room with premium amenities. Features a king-size bed and separate seating area.', '200.00', 2, '\"[\\\"WiFi\\\",\\\"Smart TV\\\",\\\"Air Conditioning\\\",\\\"Mini Bar\\\",\\\"Safe\\\",\\\"City View\\\"]\"', '/storage/rooms/nb42k8NIjtuRZoK10dsV446sbkNpwIVVevfdlxlj.jpg', 'available', '2026-05-01 22:01:14', '2026-05-01 23:19:38'),
(4, '202', 'deluxe', 'Luxurious deluxe room with ocean view. Includes balcony and premium bathroom amenities.', '250.00', 3, '\"[\\\"WiFi\\\",\\\"Smart TV\\\",\\\"Air Conditioning\\\",\\\"Mini Bar\\\",\\\"Safe\\\",\\\"Ocean View\\\",\\\"Balcony\\\"]\"', '/storage/rooms/JsVrGDS02lWb1rAZJWTd4YworXAySoDT5y7zAiDE.jpg', 'available', '2026-05-01 22:01:14', '2026-05-01 23:19:48'),
(5, '301', 'suite', 'Stunning suite with separate living room and bedroom. Panoramic views and premium services.', '400.00', 4, '\"[\\\"WiFi\\\",\\\"Smart TV\\\",\\\"Air Conditioning\\\",\\\"Mini Bar\\\",\\\"Safe\\\",\\\"Ocean View\\\",\\\"Balcony\\\",\\\"Living Room\\\",\\\"Jacuzzi\\\"]\"', '/storage/rooms/beDIZnONojcsUkW0VU3Fj8ddq9aLuHTdpOtIhPIF.jpg', 'available', '2026-05-01 22:01:14', '2026-05-01 23:19:58'),
(6, '401', 'presidential', 'Ultimate luxury presidential suite. Features multiple rooms, private terrace, and butler service.', '1000.00', 6, '\"[\\\"WiFi\\\",\\\"Smart TV\\\",\\\"Air Conditioning\\\",\\\"Mini Bar\\\",\\\"Safe\\\",\\\"Ocean View\\\",\\\"Private Terrace\\\",\\\"Living Room\\\",\\\"Dining Room\\\",\\\"Jacuzzi\\\",\\\"Butler Service\\\"]\"', '/storage/rooms/vn2uzHY1hdxTDIuGC33WNvJbV3zVSO3MKPZNHnW3.jpg', 'available', '2026-05-01 22:01:14', '2026-05-01 23:20:07');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `image`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 'Spa & Wellness', 'Relax and rejuvenate with our professional spa treatments. Includes massage, facial, and body treatments.', '80.00', '/storage/services/dJtlTekiM2jlJ1YoZza7BGMLbc36MRaD6hE1693D.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:27:52'),
(2, 'Restaurant', 'Fine dining experience with international cuisine. Our chefs prepare delicious meals using fresh ingredients.', '50.00', '/storage/services/iFbGuOdAR3MfaiA2Y8lYn6S1Min95qVQccHdmzz4.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:28:16'),
(3, 'Gym & Fitness', 'State-of-the-art fitness center with modern equipment. Personal trainers available on request.', '30.00', '/storage/services/wmr7PCvH2RXwWmOwNdrQR6KtucvWhTRlxE7K3Lfr.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:28:30'),
(4, 'Room Service', '24/7 room service for all your dining needs. Enjoy delicious meals in the comfort of your room.', '20.00', '/storage/services/1504OoFeIE2CG7pPRSHGaqYz6aUUCnEevaprbSUx.png', 1, '2026-05-01 22:01:14', '2026-05-01 23:28:44'),
(5, 'Airport Transfer', 'Comfortable airport transfer service. Our professional drivers will pick you up in a luxury vehicle.', '50.00', '/storage/services/9BHycUmbJi5o2EsCB5xxZLm74guH96ksrK9OXlqL.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:28:56'),
(6, 'Laundry Service', 'Professional laundry and dry cleaning services. Quick turnaround available.', '25.00', '/storage/services/RnEjIJStmyFk3UBcAFwGsxmNOOw920coHhBeAX1S.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:29:05');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('65fRXC2AKAZfoTc8FdJGkTp3CbPvDNJRnJV76wJX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzFjdlBoTjRFZG1vNW9saFY3aHplY0VSYVg1RUFIWkMxYkFLOTdlQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9ob3RlbC1ib29raW5nLXN5c3RlbS50ZXN0IjtzOjU6InJvdXRlIjtOO319', 1777735074),
('CNYRcCnNXcheFBW51hS2F6UUeYEi0MPyZ829UuOf', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicHdoeFI2NlFaZGFheXJvNUV1OWpNbWZwVDFUYXNCamFDb2k1VDV2VCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly9ob3RlbC1ib29raW5nLXN5c3RlbS50ZXN0L21hbmFnZXIvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE3OiJtYW5hZ2VyLmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1777734781);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('customer','manager','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `completed_bookings_count` int NOT NULL DEFAULT '0',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `premium_tier` enum('silver','gold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `completed_bookings_count`, `phone`, `address`, `is_premium`, `premium_tier`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@hotel.com', NULL, '$2y$12$4CvEIxJktkDyVnehYnQqQeWSVFYFs4cfdjabM1fUH6wUjcwH91UWO', 'admin', 10, '1234567890', 'Admin Address', 1, 'gold', NULL, '2026-05-01 22:01:13', '2026-05-02 08:26:29'),
(2, 'Manager User', 'manager@hotel.com', NULL, '$2y$12$xn7F0JvcRxb6dM40JwS4SeW.08pMXGPNxoDSfPHQjH3eC2PaaLFM6', 'manager', 0, '1234567891', 'Manager Address', 0, NULL, NULL, '2026-05-01 22:01:14', '2026-05-01 22:01:14'),
(3, 'John Doe', 'john@example.com', NULL, '$2y$12$WDFg.MbcEcdlA4PF8.jyKewFk8SOxvSS.GkPlc0fElWezqsReNl4K', 'customer', 0, '1234567892', 'Customer Address', 0, NULL, NULL, '2026-05-01 22:01:14', '2026-05-01 22:01:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_room_id_foreign` (`room_id`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_services_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_services_service_id_foreign` (`service_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`),
  ADD KEY `messages_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `premium_plans`
--
ALTER TABLE `premium_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `premium_plans_tier_key_unique` (`tier_key`);

--
-- Indexes for table `premium_subscriptions`
--
ALTER TABLE `premium_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `premium_subscriptions_user_id_foreign` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rooms_room_number_unique` (`room_number`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_services`
--
ALTER TABLE `booking_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `premium_plans`
--
ALTER TABLE `premium_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `premium_subscriptions`
--
ALTER TABLE `premium_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `premium_subscriptions`
--
ALTER TABLE `premium_subscriptions`
  ADD CONSTRAINT `premium_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
