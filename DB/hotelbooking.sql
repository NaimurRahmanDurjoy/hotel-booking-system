-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 12, 2026 at 11:35 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

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
  `hotel_id` bigint UNSIGNED DEFAULT NULL,
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
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` bigint UNSIGNED NOT NULL,
  `manager_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('sedan','suv','microbus','luxury') COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dhaka',
  `transmission` enum('auto','manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'auto',
  `fuel_type` enum('octane','cng','diesel','electric') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'octane',
  `price_per_day` decimal(10,2) NOT NULL,
  `capacity` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','unavailable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `manager_id`, `name`, `brand`, `model_year`, `type`, `base_city`, `transmission`, `fuel_type`, `price_per_day`, `capacity`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'Toyota Noah', 'Toyota', '2022', 'microbus', 'Dhaka', 'auto', 'octane', '4500.00', 7, 'Spacious 7-seater microbus, perfect for family trips.', 'https://images.unsplash.com/photo-1517994112540-009c47ea476b?auto=format&fit=crop&w=800&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(2, 5, 'Toyota Corolla Cross', 'Toyota', '2023', 'suv', 'Dhaka', 'auto', 'octane', '6000.00', 5, 'Modern SUV with premium features and safety.', 'https://images.unsplash.com/photo-1621135802920-133df287f89c?auto=format&fit=crop&w=800&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(3, 8, 'Toyota Allion', 'Toyota', '2021', 'sedan', 'Dhaka', 'auto', 'octane', '3500.00', 4, 'Smooth sedan for city rides and business trips.', 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(4, 9, 'Mercedes-Benz E-Class', 'Mercedes', '2023', 'luxury', 'Dhaka', 'auto', 'octane', '15000.00', 4, 'Ultimate luxury and comfort for special occasions.', 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=800&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(5, 10, 'Toyota Hiace', 'Toyota', '2022', 'microbus', 'Dhaka', 'auto', 'diesel', '5500.00', 12, 'High-capacity van for large groups and tours.', 'https://images.unsplash.com/photo-1506015391300-4802dc74de2e?auto=format&fit=crop&w=800&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `car_bookings`
--

CREATE TABLE `car_bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `car_id` bigint UNSIGNED NOT NULL,
  `pickup_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dropoff_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pickup_date` date NOT NULL,
  `return_date` date NOT NULL,
  `pickup_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `images` json DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `manager_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `description`, `address`, `city`, `images`, `status`, `manager_id`, `created_at`, `updated_at`) VALUES
(1, 'Grand Azure Dhaka', 'Experience premium hospitality at Grand Azure Dhaka in the heart of Dhaka. Our hotel offers world-class amenities and unparalleled comfort for all guests.', 'Dhaka Main Road, Sector 0', 'Dhaka', '[\"https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80\"]', 'active', 4, '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(2, 'Ocean Breeze Resort', 'Experience premium hospitality at Ocean Breeze Resort in the heart of Cox\'s Bazar. Our hotel offers world-class amenities and unparalleled comfort for all guests.', 'Cox\'s Bazar Main Road, Sector 1', 'Cox\'s Bazar', '[\"https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80\"]', 'active', 5, '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(3, 'Sylhet Valley Inn', 'Experience premium hospitality at Sylhet Valley Inn in the heart of Sylhet. Our hotel offers world-class amenities and unparalleled comfort for all guests.', 'Sylhet Main Road, Sector 2', 'Sylhet', '[\"https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80\"]', 'active', 8, '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(4, 'Port City Hotel', 'Experience premium hospitality at Port City Hotel in the heart of Chittagong. Our hotel offers world-class amenities and unparalleled comfort for all guests.', 'Chittagong Main Road, Sector 3', 'Chittagong', '[\"https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&q=80\"]', 'active', 9, '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(5, 'Lakeview Rangamati', 'Experience premium hospitality at Lakeview Rangamati in the heart of Rangamati. Our hotel offers world-class amenities and unparalleled comfort for all guests.', 'Rangamati Main Road, Sector 4', 'Rangamati', '[\"https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=800&q=80\"]', 'active', 10, '2026-05-11 05:21:28', '2026-05-11 05:21:28');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `hotel_id` bigint UNSIGNED DEFAULT NULL
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
(12, '2026_05_02_134728_create_premium_plans_table', 3),
(13, '2026_05_02_135539_add_min_bookings_to_premium_plans_table', 3),
(14, '2026_05_02_142439_add_completed_bookings_count_to_users_table', 3),
(15, '2026_05_11_055416_create_hotels_table', 4),
(16, '2026_05_11_055425_add_hotel_id_to_rooms_services_bookings', 4),
(17, '2026_05_11_055430_create_travel_packages_table', 4),
(18, '2026_05_11_055438_create_travel_bookings_table', 4),
(19, '2026_05_11_060058_add_hotel_id_to_messages_table', 5),
(20, '2026_05_11_102043_add_details_to_travel_packages_table', 6),
(21, '2026_05_11_111613_create_cars_table', 7),
(22, '2026_05_11_111625_create_car_bookings_table', 7),
(23, '2026_05_11_113138_add_base_city_to_cars_table', 8),
(24, '2026_05_11_113203_add_cities_to_car_bookings_table', 9);

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
(6, 'App\\Models\\User', 1, 'auth-token', '55eafc9dc56be775ef45d5bb7a174927eb12ff85d7708433ff94cab3488216c2', '[\"*\"]', '2026-05-03 23:26:55', NULL, '2026-05-03 23:21:30', '2026-05-03 23:26:55'),
(7, 'App\\Models\\User', 3, 'auth-token', '31441909baf84f35782f0377f6f0a6768949afe66f78478f48c5066ff37279c6', '[\"*\"]', '2026-05-03 23:27:15', NULL, '2026-05-03 23:27:04', '2026-05-03 23:27:15'),
(8, 'App\\Models\\User', 1, 'auth-token', 'b615b49d799821e6ffb574877d116ea860b93ab676a26f4ee3a70c72b860ec4e', '[\"*\"]', '2026-05-03 23:27:39', NULL, '2026-05-03 23:27:34', '2026-05-03 23:27:39'),
(9, 'App\\Models\\User', 3, 'auth-token', '692531cb4d258cd6a1076db177d4bcce73876176cf19d02152851318212bf8ea', '[\"*\"]', '2026-05-04 02:35:46', NULL, '2026-05-03 23:28:15', '2026-05-04 02:35:46'),
(10, 'App\\Models\\User', 1, 'auth-token', '0bf439325bd3108f8f2f26c07596247767d33cc72423295c038891d9d364d6c8', '[\"*\"]', '2026-05-04 03:25:42', NULL, '2026-05-04 02:35:55', '2026-05-04 03:25:42'),
(11, 'App\\Models\\User', 1, 'auth-token', '068e0a4b809fce3114ade003f2f8008fb9af7b1d1684bc5fa18628f016edfb87', '[\"*\"]', '2026-05-11 02:48:47', NULL, '2026-05-11 01:43:28', '2026-05-11 02:48:47'),
(12, 'App\\Models\\User', 7, 'auth-token', '5bb9e98e2bcb88d5f0bae1afd8bd17a082ea53df27a5becc9147d6a31ad96457', '[\"*\"]', NULL, NULL, '2026-05-11 04:04:02', '2026-05-11 04:04:02'),
(13, 'App\\Models\\User', 3, 'auth-token', '90b36851b8f6e8aa170b7f39b4e34d1a5a823645fea85bb54974cadcc6408f32', '[\"*\"]', '2026-05-12 00:12:35', NULL, '2026-05-11 23:55:18', '2026-05-12 00:12:35'),
(14, 'App\\Models\\User', 1, 'auth-token', 'be60bcb2ca334cde5eaeb28c624e426ff84d5fcd04d85d88a83d174936ab5f1b', '[\"*\"]', NULL, NULL, '2026-05-12 00:12:54', '2026-05-12 00:12:54');

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
(1, 'Silver Member', 'silver', 3, 5, '0.00', '[\"5% off all bookings\", \"Priority support\"]', 1, '2026-05-03 23:24:58', '2026-05-03 23:24:58'),
(2, 'Gold Member', 'gold', 10, 10, '0.00', '[\"10% off all bookings\", \"Free upgrades\", \"Late check-out\"]', 1, '2026-05-03 23:24:58', '2026-05-03 23:24:58');

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

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED DEFAULT NULL,
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

INSERT INTO `rooms` (`id`, `hotel_id`, `room_number`, `room_type`, `description`, `price_per_night`, `capacity`, `amenities`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '101', 'deluxe', 'Beautiful Deluxe room with modern amenities and a great view of Dhaka.', '4279.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(2, 1, '102', 'suite', 'Beautiful Suite room with modern amenities and a great view of Dhaka.', '12274.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(3, 1, '103', 'standard', 'Beautiful Standard room with modern amenities and a great view of Dhaka.', '2955.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(4, 1, '104', 'presidential', 'Beautiful Presidential room with modern amenities and a great view of Dhaka.', '25132.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(5, 2, '201', 'deluxe', 'Beautiful Deluxe room with modern amenities and a great view of Cox\'s Bazar.', '4459.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(6, 2, '202', 'suite', 'Beautiful Suite room with modern amenities and a great view of Cox\'s Bazar.', '12271.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(7, 2, '203', 'standard', 'Beautiful Standard room with modern amenities and a great view of Cox\'s Bazar.', '2856.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(8, 2, '204', 'presidential', 'Beautiful Presidential room with modern amenities and a great view of Cox\'s Bazar.', '25206.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(9, 3, '301', 'deluxe', 'Beautiful Deluxe room with modern amenities and a great view of Sylhet.', '4237.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(10, 3, '302', 'suite', 'Beautiful Suite room with modern amenities and a great view of Sylhet.', '12350.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(11, 3, '303', 'standard', 'Beautiful Standard room with modern amenities and a great view of Sylhet.', '2720.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(12, 3, '304', 'presidential', 'Beautiful Presidential room with modern amenities and a great view of Sylhet.', '25131.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(13, 4, '401', 'deluxe', 'Beautiful Deluxe room with modern amenities and a great view of Chittagong.', '4252.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(14, 4, '402', 'suite', 'Beautiful Suite room with modern amenities and a great view of Chittagong.', '12448.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(15, 4, '403', 'standard', 'Beautiful Standard room with modern amenities and a great view of Chittagong.', '2616.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(16, 4, '404', 'presidential', 'Beautiful Presidential room with modern amenities and a great view of Chittagong.', '25224.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(17, 5, '501', 'deluxe', 'Beautiful Deluxe room with modern amenities and a great view of Rangamati.', '4130.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(18, 5, '502', 'suite', 'Beautiful Suite room with modern amenities and a great view of Rangamati.', '12305.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(19, 5, '503', 'standard', 'Beautiful Standard room with modern amenities and a great view of Rangamati.', '2696.00', 2, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28'),
(20, 5, '504', 'presidential', 'Beautiful Presidential room with modern amenities and a great view of Rangamati.', '25146.00', 4, '[\"WiFi\", \"AC\", \"TV\", \"Coffee Maker\"]', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=600&q=80', 'available', '2026-05-11 05:21:28', '2026-05-11 05:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED DEFAULT NULL,
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

INSERT INTO `services` (`id`, `hotel_id`, `name`, `description`, `price`, `image`, `is_available`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Spa & Wellness', 'Relax and rejuvenate with our professional spa treatments. Includes massage, facial, and body treatments.', '80.00', '/storage/services/dJtlTekiM2jlJ1YoZza7BGMLbc36MRaD6hE1693D.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:27:52'),
(2, NULL, 'Restaurant', 'Fine dining experience with international cuisine. Our chefs prepare delicious meals using fresh ingredients.', '50.00', '/storage/services/iFbGuOdAR3MfaiA2Y8lYn6S1Min95qVQccHdmzz4.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:28:16'),
(3, NULL, 'Gym & Fitness', 'State-of-the-art fitness center with modern equipment. Personal trainers available on request.', '30.00', '/storage/services/wmr7PCvH2RXwWmOwNdrQR6KtucvWhTRlxE7K3Lfr.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:28:30'),
(4, NULL, 'Room Service', '24/7 room service for all your dining needs. Enjoy delicious meals in the comfort of your room.', '20.00', '/storage/services/1504OoFeIE2CG7pPRSHGaqYz6aUUCnEevaprbSUx.png', 1, '2026-05-01 22:01:14', '2026-05-01 23:28:44'),
(5, NULL, 'Airport Transfer', 'Comfortable airport transfer service. Our professional drivers will pick you up in a luxury vehicle.', '50.00', '/storage/services/9BHycUmbJi5o2EsCB5xxZLm74guH96ksrK9OXlqL.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:28:56'),
(6, NULL, 'Laundry Service', 'Professional laundry and dry cleaning services. Quick turnaround available.', '25.00', '/storage/services/RnEjIJStmyFk3UBcAFwGsxmNOOw920coHhBeAX1S.jpg', 1, '2026-05-01 22:01:14', '2026-05-01 23:29:05');

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
('ATPzxgEYvydE2FXKS8VgElf8pzrRx50TrLiUQCqX', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiazRzVHd0RG5XWGo4MGp2b2hYcXlSdnpDd0RJdGpPb0VFU1FDNkViNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9ob3RlbC1ib29raW5nLXN5c3RlbS50ZXN0IjtzOjU6InJvdXRlIjtOO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1778499233),
('AxyX2zjWOmAqKGa3nqZnvoWONQmwlbXxGo0BuNF6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibTJxTFlEYmRnbmdWSmxtWGxZODQ5T3hoblhSZkQ1VEtqeVkxcmZZdSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NjoiaHR0cDovL2hvdGVsLWJvb2tpbmctc3lzdGVtLnRlc3QvbWFuYWdlci9yb29tcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vaG90ZWwtYm9va2luZy1zeXN0ZW0udGVzdC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778585406),
('j74UzYXdyVNvbje8Q1Yxj0POsRSJlXmVzKy0QPrP', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieDRJWFBvNWZwSEhFRGVMNDliODVBakM5b09NTzFYOVRYUVJmUVNHbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9ob3RlbC1ib29raW5nLXN5c3RlbS50ZXN0L2FkbWluL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1778566374),
('Sy8pwMPVbE1MSHnk1DCw4HMZGnJeevhYiV58vOu8', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYWRGTHpMSVlWTEJWTWpJT1BVM3FNc25VN3JQZ1NvMFY2cWY2dGlMSCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU2OiJodHRwOi8vaG90ZWwtYm9va2luZy1zeXN0ZW0udGVzdC9tYW5hZ2VyL3RyYXZlbC1ib29raW5ncyI7czo1OiJyb3V0ZSI7czoyOToibWFuYWdlci50cmF2ZWxfYm9va2luZ3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1778567458);

-- --------------------------------------------------------

--
-- Table structure for table `travel_bookings`
--

CREATE TABLE `travel_bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `travel_package_id` bigint UNSIGNED NOT NULL,
  `travel_date` date NOT NULL,
  `guests` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travel_packages`
--

CREATE TABLE `travel_packages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int NOT NULL,
  `images` json DEFAULT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `transport` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accommodation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meals` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `travel_packages`
--

INSERT INTO `travel_packages` (`id`, `title`, `description`, `destination`, `price`, `duration_days`, `images`, `vendor_id`, `created_at`, `updated_at`, `transport`, `accommodation`, `meals`) VALUES
(1, 'Sundarbans Forest Expedition', 'Deep jungle safari, boat stay, and tiger tracking experience.', 'Sundarbans', '12500.00', 4, '[\"/storage/packages/sundarbans.png\"]', 4, '2026-05-11 05:21:28', '2026-05-11 23:58:47', 'AC Launch & Boat', 'Forest Lodge & Boat Cabin', 'Breakfast, Lunch, Dinner (Traditional)'),
(2, 'Sajek Valley Cloud Tour', 'Stay above the clouds, visit Kanglak Hill and enjoy the sunrise.', 'Sajek', '6500.00', 2, '[\"/storage/packages/sajek.png\"]', 5, '2026-05-11 05:21:28', '2026-05-11 23:58:47', 'Chander Gari (Jeep)', 'Hillview Resort (Eco-Cottage)', 'Breakfast & Dinner'),
(3, 'Cox\'s Bazar Beach Relaxation', 'Luxury stay at Inani beach, sunset dinner, and water sports.', 'Cox\'s Bazar', '9000.00', 3, '[\"/storage/packages/coxs_bazar.png\"]', 8, '2026-05-11 05:21:28', '2026-05-11 23:58:47', 'AC Bus (Green Line)', '5-Star Beach Resort', 'Buffet Breakfast & Seafood Dinner'),
(4, 'Sylhet Tea Garden Retreat', 'Visit Ratargul Swamp Forest, Jaflong, and lush tea gardens.', 'Sylhet', '7500.00', 3, '[\"/storage/packages/sylhet.png\"]', 9, '2026-05-11 05:21:28', '2026-05-11 23:58:47', 'Private Car', 'Boutique Tea Resort', 'Breakfast & Traditional Sylheti Lunch'),
(5, 'Saint Martin Island Escape', 'Crystal clear water, coral beach, and fresh seafood experience.', 'Saint Martin', '11000.00', 3, '[\"/storage/packages/saint_martin.png\"]', 10, '2026-05-11 05:21:28', '2026-05-11 23:58:47', 'Ship (Keari Sindbad)', 'Ocean View Cottage', 'Full Board Meals (All inclusive)'),
(6, 'Rangamati Kaptai Lake Tour', 'Boat cruise in Kaptai Lake, visit hanging bridge and waterfalls.', 'Rangamati', '5500.00', 2, '[\"/storage/packages/rangamati.png\"]', 4, '2026-05-11 05:21:28', '2026-05-11 23:58:47', 'AC Bus & Boat', 'Lakeside Resort', 'Breakfast & Bamboo Chicken Lunch'),
(7, 'Bandarban Nilgiri Expedition', 'Visit Nilgiri, Nilachal, and explore the tribal culture and hills.', 'Bandarban', '8500.00', 3, '[\"/storage/packages/bandarban.png\"]', 5, '2026-05-11 05:21:28', '2026-05-11 23:58:47', 'Jeep (Land Cruiser)', 'Nilgiri Hill Resort', 'Breakfast & Tribal Special Dinner');

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
(1, 'Admin User', 'admin@hotel.com', NULL, '$2y$12$4CvEIxJktkDyVnehYnQqQeWSVFYFs4cfdjabM1fUH6wUjcwH91UWO', 'admin', 0, '1234567890', 'Admin Address', 0, NULL, NULL, '2026-05-01 22:01:13', '2026-05-01 22:01:13'),
(2, 'Manager User', 'manager@hotel.com', NULL, '$2y$12$xn7F0JvcRxb6dM40JwS4SeW.08pMXGPNxoDSfPHQjH3eC2PaaLFM6', 'manager', 0, '1234567891', 'Manager Address', 0, NULL, NULL, '2026-05-01 22:01:14', '2026-05-01 22:01:14'),
(3, 'Samia', 'samia@gmail.com', NULL, '$2y$12$WDFg.MbcEcdlA4PF8.jyKewFk8SOxvSS.GkPlc0fElWezqsReNl4K', 'customer', 0, '1234567892', 'Customer Address', 0, NULL, NULL, '2026-05-01 22:01:14', '2026-05-01 22:01:14'),
(4, 'Manager 1', 'manager1@example.com', NULL, '$2y$12$7JVdrhbhdlgYehYlFpULleBOucBfnnxjtp8xFKBTE6.J37InEZKAO', 'manager', 0, NULL, NULL, 0, NULL, NULL, '2026-05-11 01:17:11', '2026-05-11 05:21:27'),
(5, 'Manager 2', 'manager2@example.com', NULL, '$2y$12$YYc2ivwyOOPZmNyhXp8M9OMAbaG4kRLLa8Iun7BeLJ3j/zyYZAQrG', 'manager', 0, NULL, NULL, 0, NULL, NULL, '2026-05-11 01:17:11', '2026-05-11 05:21:28'),
(7, 'System Admin', 'admin@example.com', NULL, '$2y$12$t3FEl1Dps0CJ9GDpHknWKew24ZA5UHzgINOQ/I8LJVAz31Ei5mqaK', 'admin', 0, NULL, NULL, 0, NULL, NULL, '2026-05-11 03:59:06', '2026-05-11 03:59:06'),
(8, 'Manager 3', 'manager3@example.com', NULL, '$2y$12$5Wi9ng75MhZgwExQ8OBLFeA9r65OIiKnByrgHf3FVLTBXDpsCHQqy', 'manager', 0, NULL, NULL, 0, NULL, NULL, '2026-05-11 04:24:21', '2026-05-11 05:21:28'),
(9, 'Manager 4', 'manager4@example.com', NULL, '$2y$12$KN1ALNlF.PjnidgvaLT/CuuL80YE1Gd5KhtZSUx5HQxD4g5y/qN4C', 'manager', 0, NULL, NULL, 0, NULL, NULL, '2026-05-11 04:24:21', '2026-05-11 05:21:28'),
(10, 'Manager 5', 'manager5@example.com', NULL, '$2y$12$RzhlsBdCsBBlekRkiKmafO28X.gyVc6tof6WlfHa8YqQosqhr4N8y', 'manager', 0, NULL, NULL, 0, NULL, NULL, '2026-05-11 04:24:22', '2026-05-11 05:21:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_room_id_foreign` (`room_id`),
  ADD KEY `bookings_hotel_id_foreign` (`hotel_id`);

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
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cars_manager_id_foreign` (`manager_id`);

--
-- Indexes for table `car_bookings`
--
ALTER TABLE `car_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_bookings_user_id_foreign` (`user_id`),
  ADD KEY `car_bookings_car_id_foreign` (`car_id`);

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
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotels_manager_id_foreign` (`manager_id`);

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
  ADD KEY `messages_booking_id_foreign` (`booking_id`),
  ADD KEY `messages_hotel_id_foreign` (`hotel_id`);

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
  ADD UNIQUE KEY `rooms_room_number_unique` (`room_number`),
  ADD KEY `rooms_hotel_id_foreign` (`hotel_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_hotel_id_foreign` (`hotel_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `travel_bookings`
--
ALTER TABLE `travel_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `travel_bookings_user_id_foreign` (`user_id`),
  ADD KEY `travel_bookings_travel_package_id_foreign` (`travel_package_id`);

--
-- Indexes for table `travel_packages`
--
ALTER TABLE `travel_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `travel_packages_vendor_id_foreign` (`vendor_id`);

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
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `car_bookings`
--
ALTER TABLE `car_bookings`
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
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `premium_plans`
--
ALTER TABLE `premium_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `premium_subscriptions`
--
ALTER TABLE `premium_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `travel_bookings`
--
ALTER TABLE `travel_bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `travel_packages`
--
ALTER TABLE `travel_packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `car_bookings`
--
ALTER TABLE `car_bookings`
  ADD CONSTRAINT `car_bookings_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `car_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `premium_subscriptions`
--
ALTER TABLE `premium_subscriptions`
  ADD CONSTRAINT `premium_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `travel_bookings`
--
ALTER TABLE `travel_bookings`
  ADD CONSTRAINT `travel_bookings_travel_package_id_foreign` FOREIGN KEY (`travel_package_id`) REFERENCES `travel_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travel_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `travel_packages`
--
ALTER TABLE `travel_packages`
  ADD CONSTRAINT `travel_packages_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
