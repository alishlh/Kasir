-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 02, 2025 at 06:38 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir_update_v4`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_flows`
--

CREATE TABLE `cash_flows` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_flows`
--

INSERT INTO `cash_flows` (`id`, `date`, `type`, `source`, `amount`, `notes`, `created_at`, `updated_at`) VALUES
(28, '2025-07-02', 'income', 'initial_capital', 5000000, 'Modal Awal', '2025-07-02 05:49:08', '2025-07-02 05:49:08'),
(29, '2025-07-02', 'expense', 'rent', 500000, 'Sewa ruko 1 bulan', '2025-07-02 05:49:54', '2025-07-02 05:49:54'),
(30, '2025-07-02', 'expense', 'electricity', 100000, 'Listrik Token 1 Bulan', '2025-07-02 05:50:28', '2025-07-02 05:50:28'),
(31, '2025-07-02', 'expense', 'operational_cost', 150000, 'Biaya Operasional Awal Buka', '2025-07-02 05:51:23', '2025-07-02 05:51:23'),
(32, '2025-07-02', 'expense', 'purchase_stock', 1406000, 'Otomatis dari penambahan stok Inventory dengan Nomor Referensi: INV-20250702-01', '2025-07-02 06:08:09', '2025-07-02 06:08:09'),
(33, '2025-07-02', 'expense', 'purchase_stock', 1155000, 'Otomatis dari penambahan stok Inventory dengan Nomor Referensi: INV-20250702-02', '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(34, '2025-07-02', 'expense', 'purchase_stock', 750000, 'Otomatis dari penambahan stok Inventory dengan Nomor Referensi: INV-20250702-03', '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(35, '2025-07-02', 'income', 'additional_capital', 3000000, 'Penambahan modal untuk nambah stock yang kurang', '2025-07-02 06:18:08', '2025-07-02 06:18:08'),
(36, '2025-07-02', 'expense', 'purchase_stock', 2710000, 'Otomatis dari penambahan stok Inventory dengan Nomor Referensi: INV-20250702-04', '2025-07-02 06:22:24', '2025-07-02 06:22:24');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Makanan Ringan', '2025-07-01 14:06:24', '2025-07-01 14:06:24', NULL),
(2, 'Minuman', '2025-07-01 14:06:24', '2025-07-01 14:06:24', NULL),
(3, 'Alat Tulis Kantor (ATK)', '2025-07-01 14:06:24', '2025-07-01 14:06:24', NULL),
(4, 'Produk Kebersihan', '2025-07-01 14:06:24', '2025-07-01 14:06:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('in','out','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `reference_number`, `type`, `source`, `total`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'INV-20250702-01', 'in', 'purchase_stock', 1406000, NULL, '2025-07-02 06:08:09', '2025-07-02 06:08:09'),
(2, 'INV-20250702-02', 'in', 'purchase_stock', 1155000, NULL, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(3, 'INV-20250702-03', 'in', 'purchase_stock', 750000, NULL, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(4, 'INV-20250702-04', 'in', 'purchase_stock', 2710000, NULL, '2025-07-02 06:22:24', '2025-07-02 06:22:24');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `cost_price` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `inventory_id`, `product_id`, `cost_price`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4500, 20, '2025-07-02 06:08:09', '2025-07-02 06:08:09'),
(2, 1, 2, 2800, 20, '2025-07-02 06:08:09', '2025-07-02 06:08:09'),
(3, 1, 3, 8500, 20, '2025-07-02 06:08:09', '2025-07-02 06:08:09'),
(4, 1, 4, 8500, 20, '2025-07-02 06:08:09', '2025-07-02 06:08:09'),
(5, 1, 5, 10000, 20, '2025-07-02 06:08:09', '2025-07-02 06:08:09'),
(6, 1, 6, 1500, 20, '2025-07-02 06:08:10', '2025-07-02 06:08:10'),
(7, 1, 7, 6000, 20, '2025-07-02 06:08:10', '2025-07-02 06:08:10'),
(8, 1, 8, 20000, 20, '2025-07-02 06:08:10', '2025-07-02 06:08:10'),
(9, 1, 9, 7000, 20, '2025-07-02 06:08:10', '2025-07-02 06:08:10'),
(10, 1, 10, 1500, 20, '2025-07-02 06:08:10', '2025-07-02 06:08:10'),
(11, 2, 11, 2500, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(12, 2, 12, 3000, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(13, 2, 13, 4500, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(14, 2, 14, 4000, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(15, 2, 15, 4000, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(16, 2, 16, 5000, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(17, 2, 17, 6500, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(18, 2, 18, 2500, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(19, 2, 19, 4000, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(20, 2, 20, 2500, 30, '2025-07-02 06:11:55', '2025-07-02 06:11:55'),
(21, 3, 21, 2000, 20, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(22, 3, 22, 1500, 20, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(23, 3, 23, 2500, 20, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(24, 3, 24, 2000, 20, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(25, 3, 25, 1500, 20, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(26, 3, 26, 6000, 20, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(27, 3, 27, 40000, 5, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(28, 3, 28, 4000, 20, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(29, 3, 29, 1000, 20, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(30, 3, 30, 7000, 20, '2025-07-02 06:16:29', '2025-07-02 06:16:29'),
(31, 4, 31, 3500, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24'),
(32, 4, 32, 18000, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24'),
(33, 4, 33, 5000, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24'),
(34, 4, 34, 10000, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24'),
(35, 4, 35, 12000, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24'),
(36, 4, 36, 15000, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24'),
(37, 4, 37, 10000, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24'),
(38, 4, 38, 30000, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24'),
(39, 4, 39, 18000, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24'),
(40, 4, 40, 14000, 20, '2025-07-02 06:22:24', '2025-07-02 06:22:24');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_06_25_092758_create_permission_tables', 1),
(5, '2025_05_28_052536_create_categories_table', 1),
(6, '2025_05_28_052554_create_products_table', 1),
(7, '2025_05_28_052608_create_payment_methods_table', 1),
(8, '2025_05_28_052629_create_transactions_table', 1),
(9, '2025_05_28_052640_create_transaction_items_table', 1),
(10, '2025_05_28_052715_create_inventories_table', 1),
(11, '2025_05_28_052738_create_cash_flows_table', 1),
(12, '2025_05_28_052755_create_reports_table', 1),
(13, '2025_05_28_052809_create_settings_table', 1),
(14, '2025_06_01_084553_create_inventoryitems_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_cash` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `image`, `is_cash`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Tunai', '01JZ4KD1YR0JZD2ACGSBEPWG3C.png', 1, '2025-07-02 03:38:03', '2025-07-02 03:38:03', NULL),
(2, 'QRIS', '01JZ4KDT8XMYN3T23M0CVT7XEB.png', 0, '2025-07-02 03:38:28', '2025-07-02 03:38:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_any_cash::flow', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(2, 'create_cash::flow', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(3, 'update_cash::flow', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(4, 'delete_any_cash::flow', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(5, 'view_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(6, 'view_any_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(7, 'create_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(8, 'update_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(9, 'delete_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(10, 'delete_any_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(11, 'restore_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(12, 'restore_any_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(13, 'force_delete_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(14, 'force_delete_any_category', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(15, 'view_any_inventory', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(16, 'create_inventory', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(17, 'update_inventory', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(18, 'delete_any_inventory', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(19, 'view_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(20, 'view_any_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(21, 'create_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(22, 'update_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(23, 'delete_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(24, 'delete_any_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(25, 'restore_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(26, 'restore_any_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(27, 'force_delete_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(28, 'force_delete_any_payment::method', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(29, 'view_any_product', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(30, 'create_product', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(31, 'update_product', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(32, 'delete_product', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(33, 'delete_any_product', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(34, 'restore_product', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(35, 'restore_any_product', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(36, 'force_delete_product', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(37, 'force_delete_any_product', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(38, 'view_any_report', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(39, 'create_report', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(40, 'update_report', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(41, 'delete_any_report', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(42, 'view_role', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(43, 'view_any_role', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(44, 'create_role', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(45, 'update_role', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(46, 'delete_role', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(47, 'delete_any_role', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(48, 'view_any_setting', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(49, 'create_setting', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(50, 'update_setting', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(51, 'delete_any_setting', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(52, 'view_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(53, 'view_any_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(54, 'create_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(55, 'update_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(56, 'delete_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(57, 'delete_any_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(58, 'restore_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(59, 'restore_any_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(60, 'force_delete_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(61, 'force_delete_any_transaction', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(62, 'view_any_user', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(63, 'create_user', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(64, 'update_user', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(65, 'delete_any_user', 'web', '2025-07-01 14:07:44', '2025-07-01 14:07:44'),
(66, '_Dashboard', 'web', '2025-07-01 14:07:45', '2025-07-01 14:07:45'),
(67, '_PosPage', 'web', '2025-07-01 14:07:45', '2025-07-01 14:07:45');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int DEFAULT NULL,
  `cost_price` int NOT NULL,
  `price` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `stock`, `cost_price`, `price`, `image`, `sku`, `barcode`, `description`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Qtela Singkong Original 60g', 20, 4500, 5500, 'products/01JZ35YGDYS36RXV70RP44CZV3.png', 'QT-SK-ORI-60', '8992761132214', 'Keripik singkong renyah rasa original.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:09', NULL),
(2, 1, 'Indomie Goreng Original', 20, 2800, 3500, 'products/01JZ360DPVBN22Q22QJ479J2CG.png', 'IND-GR-ORI-85', '089686040011', 'Mi instan goreng rasa original legendaris.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:09', NULL),
(3, 1, 'Chitato Sapi Panggang 68g', 20, 8500, 10000, 'products/01JZ3629V0MBFCYSGQZJERNA5W.jpg', 'CHT-SP-68', '8998866110037', 'Keripik kentang dengan bumbu sapi panggang.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:09', NULL),
(4, 1, 'Lays Rumput Laut 68g', 20, 8500, 10000, 'products/01JZ36465BEHCRDXJJE31F9ZHB.jpg', 'LYS-RL-68', '8992761158016', 'Keripik kentang tipis rasa rumput laut.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:09', NULL),
(5, 1, 'Silverqueen Cashew 58g', 20, 10000, 12500, 'products/01JZ3657KZXHAABE4N4Z2XNYDN.png', 'SVQ-CS-58', '8991001101119', 'Cokelat susu dengan kacang mede.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:09', NULL),
(6, 1, 'Beng-Beng Regular 20g', 20, 1500, 2000, 'products/01JZ366PGYHJ1ZMYJFBYJJ39NM.jpg', 'BBG-REG-20', '8996001300018', 'Wafer karamel renyah berlapis cokelat.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:10', NULL),
(7, 1, 'Tango Wafer Cokelat 130g', 20, 6000, 7500, 'products/01JZ368Q35VFJXE2EAAPNKM67Y.webp', 'TGO-CK-130', '8991001201017', 'Wafer renyah dengan krim cokelat tebal.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:10', NULL),
(8, 1, 'Pringles Original 107g', 20, 20000, 24000, 'products/01JZ36A1WQD630YH4H6HT05F77.webp', 'PRG-ORI-107', '038000138435', 'Keripik kentang dalam tabung rasa original.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:10', NULL),
(9, 1, 'Oishi Pillows Cokelat 100g', 20, 7000, 8500, 'products/01JZ36CCT7J3F3FCGQ010C8ED1.jpg', 'OIS-PLC-100', '8995166102148', 'Biskuit renyah dengan isian krim cokelat.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:10', NULL),
(10, 1, 'Nabati Wafer Keju 50g', 20, 1500, 2000, 'products/01JZ36DJJKQQSDSVJSENZMFE78.jpg', 'NBT-KJ-50', '8993175535017', 'Wafer renyah dengan krim keju richeese.', 1, '2025-07-01 14:06:24', '2025-07-02 06:08:10', NULL),
(11, 2, 'Aqua Air Mineral 600ml', 30, 2500, 3500, 'products/01JZ36FGRJ3BG26HSJNYW692S8.jpg', 'AQA-MIN-600', '8992761141124', 'Air mineral murni dalam kemasan botol 600ml.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(12, 2, 'Teh Kotak Sosro 200ml', 30, 3000, 4000, 'products/01JZ36H4V86M35R5KH2BKP9B6V.webp', 'SOS-TK-200', '8998899000108', 'Minuman teh melati siap saji.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(13, 2, 'Coca-Cola Kaleng 330ml', 30, 4500, 6000, 'products/01JZ36JAC1WPN91WF3P2JVS14X.jpg', 'CC-KLG-330', '8992761101111', 'Minuman soda rasa kola dalam kemasan kaleng.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(14, 2, 'Fanta Strawberry Botol 390ml', 30, 4000, 5500, 'products/01JZ36MB70TWQVFQGHZNGA17AY.png', 'FNT-ST-390', '8992761102224', 'Minuman soda rasa stroberi.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(15, 2, 'Sprite Botol 390ml', 30, 4000, 5500, 'products/01JZ36NPH1H1ET4VRDCBGF5JTZ.webp', 'SPR-LN-390', '8992761103337', 'Minuman soda rasa lemon-lime.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(16, 2, 'Ultra Milk Cokelat 250ml', 30, 5000, 6500, 'products/01JZ36PRWEHBK1JY0BF6HNHXSA.jpg', 'ULT-CK-250', '8998899102017', 'Susu UHT rasa cokelat.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(17, 2, 'Pocari Sweat Botol 500ml', 30, 6500, 8000, 'products/01JZ36R7C73KYWVTWGA26C8SG4.webp', 'PCS-500', '4987035332517', 'Minuman isotonik pengganti cairan tubuh.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(18, 2, 'Floridina Orange 350ml', 30, 2500, 3500, 'products/01JZ36TR5RTT7NCJ6SS4PRN55Y.jpg', 'FLD-ORG-350', '8998866601019', 'Minuman sari buah jeruk dengan bulir asli.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(19, 2, 'Frestea Teh Melati 500ml', 30, 4000, 5000, 'products/01JZ36W1XPWEEZVVFKEPKNQ1HB.png', 'FRS-TM-500', '8992761111127', 'Minuman teh rasa melati.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(20, 2, 'Le Minerale 600ml', 30, 2500, 3500, 'products/01JZ36XA38EW000KS1181C26PQ.png', 'LMN-600', '8992761191129', 'Air mineral dengan kandungan mineral alami.', 1, '2025-07-01 14:06:24', '2025-07-02 06:11:55', NULL),
(21, 3, 'Buku Tulis Sinar Dunia 38 Lbr', 20, 2000, 3000, 'products/01JZ3N8V5ZPCABXB69833CZ8XA.jpg', 'SIDU-BK-38', '8991389000017', 'Buku tulis ukuran standar isi 38 lembar.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(22, 3, 'Pulpen Standard AE7 Hitam', 20, 1500, 2500, 'products/01JZ3NAFYQ44ZADGS91AHJVETH.jpg', 'STD-AE7-BLK', '8991831101019', 'Pulpen tinta hitam dengan mata pena 0.5mm.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(23, 3, 'Pensil 2B Faber-Castell', 20, 2500, 4000, 'products/01JZ3NC13F3HD7F67TQQMWN28A.png', 'FC-P2B', '8991338000021', 'Pensil berkualitas untuk ujian dan menulis.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(24, 3, 'Penghapus Faber-Castell', 20, 2000, 3500, 'products/01JZ3NDW3FSJTM9RZCEYGX0GNV.png', 'FC-ERASER', '4005401871715', 'Penghapus bebas debu dan bersih.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(25, 3, 'Penggaris Butterfly 30cm', 20, 1500, 2500, 'products/01JZ3NFH960EGA719VCGXE7JDY.jpg', 'BFLY-RUL-30', '8991320001150', 'Penggaris plastik transparan 30 cm.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(26, 3, 'Spidol Snowman Boardmarker Hitam', 20, 6000, 8000, 'products/01JZ3NGQVAVMXA12AG6T3YXE9F.png', 'SNOW-BM-BLK', '8993888120015', 'Spidol untuk papan tulis putih, mudah dihapus.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(27, 3, 'Kertas HVS A4 Sinar Dunia 75gsm', 5, 40000, 48000, 'products/01JZ3NHR195AK2DTC9MWP7EZN9.jpg', 'SIDU-A4-75', '8991389100113', 'Satu rim kertas HVS ukuran A4 75 gsm.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(28, 3, 'Tipe-X Kenko KE-01', 20, 4000, 6000, 'products/01JZ3NJVBVGYBHKWKRVEN2MTTF.jpg', 'KNK-KE01', '8993993000109', 'Cairan koreksi tulisan cepat kering.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(29, 3, 'Lem Kertas Glukol 22ml', 20, 1000, 2000, 'products/01JZ3NM70MJBG5MSYW2MGZEJHN.jpg', 'GLU-22ML', '8991320000016', 'Lem kertas cair serbaguna.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(30, 3, 'Stabilo Boss Original Kuning', 20, 7000, 9000, 'products/01JZ3NN3AMRYYKM3VEH0H0CA72.jpg', 'STB-BS-YLW', '4006381155018', 'Text highlighter warna kuning neon.', 1, '2025-07-01 14:06:24', '2025-07-02 06:16:29', NULL),
(31, 4, 'Sabun Lifebuoy Total 10 85g', 20, 3500, 4500, 'products/01JZ3NPTK4ZV9NRZ0QYB3N4MAX.webp', 'LFB-T10-85', '8999999039014', 'Sabun mandi batang anti-bakteri.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL),
(32, 4, 'Sampo Pantene Anti Lepek 135ml', 20, 18000, 22000, 'products/01JZ3NRFGXSNAHEVXNADCWK9Z5.png', 'PNT-AL-135', '4902430727017', 'Sampo untuk rambut bebas lepek.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL),
(33, 4, 'Sikat Gigi Pepsodent Double Care', 20, 5000, 7500, 'products/01JZ3NTCZGRJ5K8ZWEYTEBRRD3.jpg', 'PSD-SG-DC', '8999999038017', 'Sikat gigi dengan bulu halus dan lembut.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL),
(34, 4, 'Pasta Gigi Pepsodent 190g', 20, 10000, 13000, 'products/01JZ3NWYJ3M53ZC5K114Q0SVXN.png', 'PSD-PG-190', '8999999001011', 'Pasta gigi pencegah gigi berlubang.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL),
(35, 4, 'Sunlight Pencuci Piring Jeruk Nipis 755ml', 20, 12000, 15000, 'products/01JZ3NYFXZQPQS44PA3KMQKYQ4.webp', 'SN-JN-755', '8999999039618', 'Sabun cuci piring dengan ekstrak jeruk nipis.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL),
(36, 4, 'Wipol Karbol Wangi Classic Pine 780ml', 20, 15000, 18000, 'products/01JZ3NZAVDV0544EAFBPPM5SND.jpg', 'WPL-CP-780', '8999999042014', 'Cairan disinfektan pembersih lantai.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL),
(37, 4, 'Super Pell Pembersih Lantai 770ml', 20, 10000, 13000, 'products/01JZ3P0HC48E19QF0VB3KJ1Q9B.jpg', 'SPP-PL-770', '8999999039410', 'Pembersih lantai dengan teknologi power clean.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL),
(38, 4, 'Baygon Semprot Flower Garden 600ml', 20, 30000, 35000, 'products/01JZ3P2B57XD9K3HCVK70DW1SV.png', 'BYG-FG-600', '8991999000115', 'Insektisida semprot pembasmi nyamuk & serangga.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL),
(39, 4, 'Rinso Deterjen Anti Noda 770g', 20, 18000, 21000, 'products/01JZ3P3SRRX7RJK4H72QYBWG0C.png', 'RNS-AN-770', '8999999540019', 'Deterjen bubuk penghilang noda membandel.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL),
(40, 4, 'Molto Pewangi Pakaian Pink 820ml', 20, 14000, 17000, 'products/01JZ3P52537MT700M79YQFTSR3.jpg', 'MLT-PK-820', '8999999041017', 'Pewangi dan pelembut pakaian konsentrat.', 1, '2025-07-01 14:06:24', '2025-07-02 06:22:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_type` enum('inflow','outflow','sales') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `path_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-07-01 14:07:18', '2025-07-01 14:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('EkwaD3aofM2BofHO7y5pJt13sAbz0nEy2GNjrOiG', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiMUQ5OUNpeDZVeDFMQnM0SFRWVE1vWkprc1l3bDl4WE9Ud1FTSHZDOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvdHJhbnNhY3Rpb25zIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJEkxa3NJMWJLRzhsN1IvcWtPMm5jNU9ORzl5Ny9Ka2oyMVBVckFuUENPa0R2UEh4eUd0c1c2IjtzOjg6ImZpbGFtZW50IjthOjA6e31zOjY6InRhYmxlcyI7YToxOntzOjIxOiJMaXN0UHJvZHVjdHNfcGVyX3BhZ2UiO3M6MzoiYWxsIjt9czoxMDoib3JkZXJJdGVtcyI7YToxOntpOjA7YTo3OntzOjEwOiJwcm9kdWN0X2lkIjtpOjE7czo0OiJuYW1lIjtzOjI3OiJRdGVsYSBTaW5na29uZyBPcmlnaW5hbCA2MGciO3M6NToicHJpY2UiO2k6NTUwMDtzOjEwOiJjb3N0X3ByaWNlIjtpOjQ1MDA7czoxMjoidG90YWxfcHJvZml0IjtpOjEwMDA7czo5OiJpbWFnZV91cmwiO3M6Mzk6InByb2R1Y3RzLzAxSlozNVlHRFlTMzZSWFY3MFJQNDRDWlYzLnBuZyI7czo4OiJxdWFudGl0eSI7aToxO319fQ==', 1751437686);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `print_via_bluetooth` tinyint(1) NOT NULL DEFAULT '0',
  `name_printer_local` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `logo`, `name`, `phone`, `address`, `print_via_bluetooth`, `name_printer_local`, `created_at`, `updated_at`) VALUES
(1, 'images/01JZ3PW0CAT123TEZESX8XW625.jpg', 'Toko Maju Jaya', '081234567890', 'Jl. Pahlawan No. 123, Kota Sejahtera, 14045', 1, NULL, '2025-07-01 19:18:05', '2025-07-01 19:19:24');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_method_id` bigint UNSIGNED NOT NULL,
  `transaction_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `total` int NOT NULL,
  `cash_received` int NOT NULL,
  `change` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  `cost_price` int NOT NULL,
  `total_profit` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Owner Toko', 'admin@gmail.com', '2025-07-01 14:06:24', '$2y$12$I1ksI1bKG8l7R/qkO2nc5ONG9y7/Jkj21PUrAnPCOkDvPHxyGtsW6', '8cBA4TgJDP', '2025-07-01 14:06:24', '2025-07-01 14:06:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cash_flows`
--
ALTER TABLE `cash_flows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_items_inventory_id_foreign` (`inventory_id`),
  ADD KEY `inventory_items_product_id_foreign` (`product_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_items_product_id_foreign` (`product_id`);

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
-- AUTO_INCREMENT for table `cash_flows`
--
ALTER TABLE `cash_flows`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD CONSTRAINT `inventory_items_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`);

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
