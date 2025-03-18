/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80401
 Source Host           : localhost:3306
 Source Schema         : mydomotics

 Target Server Type    : MySQL
 Target Server Version : 80401
 File Encoding         : 65001

 Date: 23/10/2024 12:44:10
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache
-- ----------------------------
BEGIN;
INSERT INTO `cache` VALUES ('info@fanale.name1|::1', 'i:1;', 1729447776);
INSERT INTO `cache` VALUES ('info@fanale.name1|::1:timer', 'i:1729447776;', 1729447776);
COMMIT;

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
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
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of job_batches
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of jobs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2024_10_10_110420_create_roles_table', 2);
INSERT INTO `migrations` VALUES (5, '2024_10_10_112751_create_permission_tables', 3);
INSERT INTO `migrations` VALUES (6, '2024_10_10_114626_create_products_table', 4);
INSERT INTO `migrations` VALUES (7, '2024_10_10_125400_create_rooms_table', 5);
INSERT INTO `migrations` VALUES (8, '2024_10_14_214825_create_product_room_table', 6);
INSERT INTO `migrations` VALUES (9, '2024_10_14_233726_create_quotations_table', 7);
INSERT INTO `migrations` VALUES (10, '2024_10_14_233753_create_quotation_product_table', 7);
COMMIT;

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
BEGIN;
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 1);
INSERT INTO `model_has_roles` VALUES (1, 'App\\Models\\User', 2);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 2);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 4);
INSERT INTO `model_has_roles` VALUES (4, 'App\\Models\\User', 5);
COMMIT;

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permissions
-- ----------------------------
BEGIN;
INSERT INTO `permissions` VALUES (1, 'create-role', 'web', '2024-10-10 12:15:19', '2024-10-10 12:15:19');
INSERT INTO `permissions` VALUES (2, 'edit-role', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `permissions` VALUES (3, 'delete-role', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `permissions` VALUES (4, 'create-user', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `permissions` VALUES (5, 'edit-user', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `permissions` VALUES (6, 'delete-user', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `permissions` VALUES (7, 'view-product', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `permissions` VALUES (8, 'create-product', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `permissions` VALUES (9, 'edit-product', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `permissions` VALUES (10, 'delete-product', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
COMMIT;

-- ----------------------------
-- Table structure for product_quotation
-- ----------------------------
DROP TABLE IF EXISTS `product_quotation`;
CREATE TABLE `product_quotation` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint unsigned NOT NULL,
  `product_room_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotation_product_quotation_id_foreign` (`quotation_id`),
  KEY `quotation_product_product_room_id_foreign` (`product_room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of product_quotation
-- ----------------------------
BEGIN;
INSERT INTO `product_quotation` VALUES (19, 7, 2, 5, 3.00, '2024-10-15 00:43:52', '2024-10-15 01:30:57', 12);
INSERT INTO `product_quotation` VALUES (20, 7, 2, 22, 3.00, '2024-10-15 00:44:01', '2024-10-15 01:30:57', 13);
INSERT INTO `product_quotation` VALUES (21, 7, 3, 14, 45.00, '2024-10-15 01:34:03', '2024-10-15 01:34:03', 14);
INSERT INTO `product_quotation` VALUES (22, 8, 1, 444, 3.00, '2024-10-15 12:13:52', '2024-10-15 12:15:22', 12);
INSERT INTO `product_quotation` VALUES (23, 9, 1, 1, 3.00, '2024-10-15 12:17:17', '2024-10-15 12:17:26', 13);
INSERT INTO `product_quotation` VALUES (25, 10, 1, 10, 2.00, '2024-10-15 12:25:07', '2024-10-22 14:03:39', 12);
INSERT INTO `product_quotation` VALUES (26, 10, 1, 10, 9.00, '2024-10-15 12:43:07', '2024-10-22 14:03:39', 13);
INSERT INTO `product_quotation` VALUES (27, 10, 2, 4, 4.00, '2024-10-15 12:43:31', '2024-10-22 14:03:39', 14);
INSERT INTO `product_quotation` VALUES (28, 10, 3, 20, 45.00, '2024-10-20 18:56:32', '2024-10-22 14:03:39', 14);
INSERT INTO `product_quotation` VALUES (29, 11, 1, 3, 3.00, '2024-10-22 14:04:38', '2024-10-22 17:56:23', 12);
INSERT INTO `product_quotation` VALUES (30, 12, 1, 144, 3.00, '2024-10-23 07:00:37', '2024-10-23 07:00:46', 12);
INSERT INTO `product_quotation` VALUES (31, 13, 1, 1, 40.00, '2024-10-23 07:00:54', '2024-10-23 09:15:47', 12);
INSERT INTO `product_quotation` VALUES (32, 13, 1, 10, 25.00, '2024-10-23 09:15:05', '2024-10-23 09:15:47', 15);
INSERT INTO `product_quotation` VALUES (33, 13, 2, 14, 30.00, '2024-10-23 09:16:04', '2024-10-23 09:16:04', 15);
INSERT INTO `product_quotation` VALUES (34, 13, 2, 1, 34.00, '2024-10-23 09:16:08', '2024-10-23 09:16:08', 14);
COMMIT;

-- ----------------------------
-- Table structure for product_role
-- ----------------------------
DROP TABLE IF EXISTS `product_role`;
CREATE TABLE `product_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of product_role
-- ----------------------------
BEGIN;
INSERT INTO `product_role` VALUES (1, 10, 1, 30.00, NULL, NULL);
INSERT INTO `product_role` VALUES (2, 10, 2, 45.00, NULL, NULL);
INSERT INTO `product_role` VALUES (3, 11, 1, 45.00, NULL, NULL);
INSERT INTO `product_role` VALUES (4, 11, 2, 55.00, NULL, NULL);
INSERT INTO `product_role` VALUES (5, 12, 1, 3.00, NULL, NULL);
INSERT INTO `product_role` VALUES (6, 12, 2, 4.00, NULL, NULL);
INSERT INTO `product_role` VALUES (7, 13, 1, 3.00, NULL, NULL);
INSERT INTO `product_role` VALUES (8, 13, 2, 4.00, NULL, NULL);
INSERT INTO `product_role` VALUES (12, 14, 4, 4.00, NULL, NULL);
INSERT INTO `product_role` VALUES (15, 14, 1, 34.00, NULL, NULL);
INSERT INTO `product_role` VALUES (16, 14, 2, 666.00, NULL, NULL);
INSERT INTO `product_role` VALUES (17, 14, 3, 4.00, NULL, NULL);
INSERT INTO `product_role` VALUES (18, 15, 1, 30.00, NULL, NULL);
INSERT INTO `product_role` VALUES (19, 15, 2, 50.00, NULL, NULL);
INSERT INTO `product_role` VALUES (20, 15, 3, 60.00, NULL, NULL);
INSERT INTO `product_role` VALUES (21, 15, 4, 70.00, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for product_room
-- ----------------------------
DROP TABLE IF EXISTS `product_room`;
CREATE TABLE `product_room` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_room_product_id_foreign` (`product_id`),
  KEY `product_room_room_id_foreign` (`room_id`),
  CONSTRAINT `product_room_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_room_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of product_room
-- ----------------------------
BEGIN;
INSERT INTO `product_room` VALUES (7, 12, 1, NULL, NULL);
INSERT INTO `product_room` VALUES (8, 12, 2, NULL, NULL);
INSERT INTO `product_room` VALUES (9, 13, 1, NULL, NULL);
INSERT INTO `product_room` VALUES (10, 13, 2, NULL, NULL);
INSERT INTO `product_room` VALUES (11, 14, 3, NULL, NULL);
INSERT INTO `product_room` VALUES (12, 14, 1, NULL, NULL);
INSERT INTO `product_room` VALUES (13, 14, 2, NULL, NULL);
INSERT INTO `product_room` VALUES (14, 15, 1, NULL, NULL);
INSERT INTO `product_room` VALUES (15, 15, 2, NULL, NULL);
INSERT INTO `product_room` VALUES (16, 15, 4, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of products
-- ----------------------------
BEGIN;
INSERT INTO `products` VALUES (12, NULL, 'eeee', 'eeee', NULL, 'products/SdR8lQFEzvxiXslUpBFc91WHCxpoQMM36TI4sNtW.png', '2024-10-14 22:36:15', '2024-10-14 22:36:15');
INSERT INTO `products` VALUES (13, NULL, 'rtyry', 'eeee', NULL, 'products/iFjqAt4H7PEjWrY0cwVekRv2BHwpfg8bKyzcjy79.png', '2024-10-14 22:36:48', '2024-10-15 00:59:16');
INSERT INTO `products` VALUES (14, NULL, 'bagno', 'vbbb', NULL, NULL, '2024-10-15 01:33:57', '2024-10-15 01:33:57');
INSERT INTO `products` VALUES (15, NULL, 'nuovo prodotto', 'eeee', NULL, NULL, '2024-10-23 09:14:41', '2024-10-23 09:14:41');
COMMIT;

-- ----------------------------
-- Table structure for quotations
-- ----------------------------
DROP TABLE IF EXISTS `quotations`;
CREATE TABLE `quotations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `discount` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `note` varchar(4000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotations_user_id_foreign` (`user_id`),
  CONSTRAINT `quotations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of quotations
-- ----------------------------
BEGIN;
INSERT INTO `quotations` VALUES (8, 1, 'completed', NULL, NULL, '2024-10-15 12:13:52', '2024-10-15 12:14:36', NULL);
INSERT INTO `quotations` VALUES (10, 1, 'sent', 80.00, 205.20, '2024-10-15 12:25:07', '2024-10-22 14:03:39', NULL);
INSERT INTO `quotations` VALUES (11, 1, 'completed', 9.00, 8.19, '2024-10-22 14:04:38', '2024-10-22 18:05:50', NULL);
INSERT INTO `quotations` VALUES (12, 1, 'confirmed', NULL, 432.00, '2024-10-23 07:00:37', '2024-10-23 07:00:48', NULL);
INSERT INTO `quotations` VALUES (13, 1, 'confirmed', 10.00, 261.00, '2024-10-23 07:00:54', '2024-10-23 09:18:18', NULL);
COMMIT;

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
BEGIN;
INSERT INTO `role_has_permissions` VALUES (4, 2);
INSERT INTO `role_has_permissions` VALUES (5, 2);
INSERT INTO `role_has_permissions` VALUES (6, 2);
INSERT INTO `role_has_permissions` VALUES (8, 2);
INSERT INTO `role_has_permissions` VALUES (9, 2);
INSERT INTO `role_has_permissions` VALUES (10, 2);
INSERT INTO `role_has_permissions` VALUES (8, 3);
INSERT INTO `role_has_permissions` VALUES (9, 3);
INSERT INTO `role_has_permissions` VALUES (10, 3);
INSERT INTO `role_has_permissions` VALUES (7, 4);
COMMIT;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
BEGIN;
INSERT INTO `roles` VALUES (1, 'Super Admin', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `roles` VALUES (2, 'Admin', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `roles` VALUES (3, 'Product Manager', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `roles` VALUES (4, 'User', 'web', '2024-10-10 12:15:20', '2024-10-10 12:15:20');
COMMIT;

-- ----------------------------
-- Table structure for rooms
-- ----------------------------
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of rooms
-- ----------------------------
BEGIN;
INSERT INTO `rooms` VALUES (1, 'Ingresso1', 'ingresso', 'rooms/YTxw6rqCAuk86jxXSMG9PlVdnPUgrveEjMC79oQt.png', NULL, '2024-10-23 06:56:23');
INSERT INTO `rooms` VALUES (2, 'Cucina', 'cucin', 'rooms/iVSzb5PxVNjpIpXcYVAQj3f5Lpi8CIzLDMiSotor.png', NULL, '2024-10-23 09:13:18');
INSERT INTO `rooms` VALUES (3, 'Bagno 1', 'bagno-1', NULL, NULL, NULL);
INSERT INTO `rooms` VALUES (4, 'Bagno 2', 'bagno-2', NULL, NULL, NULL);
INSERT INTO `rooms` VALUES (8, 'salone', 'salone', NULL, '2024-10-23 09:13:31', '2024-10-23 09:13:31');
COMMIT;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sessions
-- ----------------------------
BEGIN;
INSERT INTO `sessions` VALUES ('LL57CzffIuu2YLWHwRG874JO1DM3Ikl2cf2skQRg', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:131.0) Gecko/20100101 Firefox/131.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieFhGNEZkVXRjZ1hNekZSblFHa0RHMTR0UlpLOUpJcmxsaEk0Z3VpRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vdGVzdC5teWRvbW90aWNzLmxvY2FsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1729674995);
INSERT INTO `sessions` VALUES ('Z1lRw0BevimXPbQKdHieKwk0QF12bbCxzfGZK2JC', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:131.0) Gecko/20100101 Firefox/131.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicTF5cFpPTnRnNWxRTVdLM0UzTTcxNlZiRGRxbWxuejdXSmRKcVcySCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vdGVzdC5teWRvbW90aWNzLmxvY2FsIjt9fQ==', 1729675237);
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 'Raimondo', 'info@fanale.name', NULL, '$2y$12$95cqBMVtPDoJks/pIHS8HuHpsgC34CkVJ7jpRiTXIVhRE3k/PI8DW', NULL, '2024-10-10 10:47:11', '2024-10-10 10:47:11');
INSERT INTO `users` VALUES (2, 'Javed Ur Rehman', 'javed@allphptricks.com', NULL, '$2y$12$M9YQSrdOMtq1Vgwxh7NFceOZ2opxvEPsyOPBxaWlO5VvSfFlcTSX6', NULL, '2024-10-10 12:15:20', '2024-10-10 12:15:20');
INSERT INTO `users` VALUES (3, 'Syed Ahsan Kamal', 'ahsan@allphptricks.com', NULL, '$2y$12$RM5ngpRpr8E5N7G35BTOQOWxI0xcbV79d40UkKHWKAd2irtDUSAfW', NULL, '2024-10-10 12:15:21', '2024-10-10 12:15:21');
INSERT INTO `users` VALUES (4, 'Abdul Muqeet', 'muqeet@allphptricks.com', NULL, '$2y$12$zqiMXby43u4J1d23Afouce9578MCnvSbdo1iXXjWkS09AZCFWExYi', NULL, '2024-10-10 12:15:22', '2024-10-10 12:15:22');
INSERT INTO `users` VALUES (5, 'Naghman Ali', 'naghman@allphptricks.com', NULL, '$2y$12$oK2efUvzmX1Ch2UYjV.V5ea2PHMgCNxQ/h2/JmSp64LVcAjU8Rcza', NULL, '2024-10-10 12:15:23', '2024-10-10 12:15:23');
INSERT INTO `users` VALUES (6, 'Test User', 'test@example.com', '2024-10-10 12:15:23', '$2y$12$/ddTm48zZKbGpR4qfSHZbubmQN/bhOt6wrvOI76mBsfSx1pArAVEa', '0scEp2DJ98', '2024-10-10 12:15:23', '2024-10-10 12:15:23');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
