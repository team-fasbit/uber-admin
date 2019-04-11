-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 07, 2018 at 10:24 AM
-- Server version: 5.7.24-0ubuntu0.16.04.1
-- PHP Version: 7.0.32-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tronline`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dashboard` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `booking_stats` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `driver_availability_stats` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `corporates` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `call_center_managers` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `users` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `providers` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sub_admins` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ride_requests_management` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vehicle_types` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `promo_codes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rental_management` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `airport_details` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destination_details` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pricing_management` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_ratings` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_ratings` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `documents_management` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency_management` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transactions` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `push_notifications` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `settings` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ads_management` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_activated` int(11) NOT NULL,
  `gender` enum('male','female','others') COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `dashboard`, `booking_stats`, `driver_availability_stats`, `corporates`, `call_center_managers`, `users`, `providers`, `sub_admins`, `ride_requests_management`, `vehicle_types`, `promo_codes`, `rental_management`, `airport_details`, `destination_details`, `pricing_management`, `provider_ratings`, `user_ratings`, `documents_management`, `currency_management`, `transactions`, `push_notifications`, `settings`, `ads_management`, `picture`, `description`, `is_activated`, `gender`, `mobile`, `paypal_email`, `address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'SmartCar', 'admin@SmartCar.com', '$2y$10$nHWG0m5ImRbhCDprHXa9EeiblStctGcFmwn12iInCg0Xw9MW86ipW', '1', '3', '3', '3', '1,2,3,4,10,11', '1,2,3,4,10,11', '1,2,3,4,5,10,11', '1,2,3,4,5,6,8,9,11', '1,2,3,4,10', '3,7,12,13', '1,2,3,4', '1,2,3,4', '1,2,3,4', '1,2,3,4', '1,2,3,4', '1,2,3,4', '3,4', '3,4', '1,2,3,4', '1,2,3,4', '3', '3', '3', '1,2,3,4', '', '', 1, 'male', '', '', '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_tron_wallet`
--

CREATE TABLE `admin_tron_wallet` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `private_key` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `public_key` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address_base58` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address_hex` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(10) UNSIGNED NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `airport_details`
--

CREATE TABLE `airport_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double(15,8) NOT NULL,
  `longitude` double(15,8) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `airport_prices`
--

CREATE TABLE `airport_prices` (
  `id` int(10) UNSIGNED NOT NULL,
  `package_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `airport_details_id` int(11) NOT NULL,
  `location_details_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `number_tolls` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_center_managers`
--

CREATE TABLE `call_center_managers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_activated` int(11) NOT NULL,
  `is_approved` int(11) NOT NULL,
  `payment_mode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default_card` int(11) NOT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('male','female','others') COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_reasons`
--

CREATE TABLE `cancellation_reasons` (
  `id` int(10) UNSIGNED NOT NULL,
  `cancel_reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cancel_fee` double(8,2) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_four` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `card_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method_nonce` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `card_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'na',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('up','pu') COLLATE utf8_unicode_ci NOT NULL,
  `delivered` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `corporates`
--

CREATE TABLE `corporates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_activated` int(11) NOT NULL,
  `is_approved` int(11) NOT NULL,
  `gender` enum('male','female','others') COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `corporates`
--

INSERT INTO `corporates` (`id`, `name`, `email`, `password`, `picture`, `description`, `is_activated`, `is_approved`, `gender`, `mobile`, `paypal_email`, `address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Corporate', 'corporate@smartcar.com', '$2y$10$U6AoxwBsLxjFkSMANFszr.iof0bi.lJxJ4UkStkWSr.6TyqObV3mG', '', '', 1, 1, 'male', '', '', '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `currency_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency_value` double(8,2) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `debts`
--

CREATE TABLE `debts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `allow` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hourly_packages`
--

CREATE TABLE `hourly_packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `number_hours` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `distance` double(8,2) NOT NULL,
  `car_type_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hourly_packages`
--

INSERT INTO `hourly_packages` (`id`, `number_hours`, `price`, `distance`, `car_type_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 10.00, 10.00, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_details`
--

CREATE TABLE `location_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double(15,8) NOT NULL,
  `longitude` double(15,8) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manager_requests`
--

CREATE TABLE `manager_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `caller_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `service_type_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `estimated_fare` double(8,2) NOT NULL,
  `s_latitude` double(15,8) NOT NULL,
  `s_longitude` double(15,8) NOT NULL,
  `d_latitude` double(15,8) NOT NULL,
  `d_longitude` double(15,8) NOT NULL,
  `s_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `d_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2016_08_29_064138_change_device_type_in_users_table', 1),
('2016_08_29_073204_create_mobile_registers_table', 1),
('2016_08_29_082431_create_page_counters_table', 1),
('2017_01_03_072214_create_providers_table', 1),
('2017_01_03_072300_create_admins_table', 1),
('2017_01_03_072425_create_service_types_table', 1),
('2017_01_03_072514_create_provider_services_table', 1),
('2017_01_03_072557_create_feedbacks_table', 1),
('2017_01_03_072634_create_requests_table', 1),
('2017_01_03_072710_create_requests_meta_table', 1),
('2017_01_03_072754_create_user_ratings_table', 1),
('2017_01_03_072849_create_provider_ratings_table', 1),
('2017_01_03_073026_create_settings_table', 1),
('2017_01_03_073110_create_cards_table', 1),
('2017_01_03_073157_create_request_payments_table', 1),
('2017_01_03_073248_create_documents_table', 1),
('2017_01_03_073323_create_provider_documents_table', 1),
('2017_01_03_073520_create_chat_messages_table', 1),
('2017_01_03_073729_create_walk_locations_table', 1),
('2017_01_11_065345_add_min_fare_into_service_types_table', 1),
('2017_01_11_075724_create_promo_codes_table', 1),
('2017_01_12_073740_create_jobs_table', 1),
('2017_01_12_073829_create_failed_jobs_table', 1),
('2017_01_18_083119_add_distance_to_request_payments_table', 1),
('2017_01_20_125338_create_provider_availabilities_table', 1),
('2017_01_20_125619_add_later_to_requests_table', 1),
('2017_01_21_083038_add_fields_to_cards_table', 1),
('2017_02_01_075736_create_corporates_table', 1),
('2017_02_01_133824_add_corporate_id_into_providers_table', 1),
('2017_02_10_130619_create_hourly_packages_table', 1),
('2017_02_13_081153_add_request_status_type_into_requests_table', 1),
('2017_02_17_082716_create_airport_details_table', 1),
('2017_02_17_083142_create_location_details_table', 1),
('2017_02_17_102350_create_airport_prices_table', 1),
('2017_02_23_102736_add_airport_price_id_to_requests_table', 1),
('2017_06_27_070530_alter_promo_code', 1),
('2017_06_30_071545_alter_service_types', 1),
('2017_07_10_133341_create_onecol_in_user', 1),
('2017_07_17_100934_add_columns_into_request_table', 1),
('2017_07_19_075349_add_cols_to_service_types', 1),
('2017_07_19_131249_add_cols_to_request_payment', 1),
('2017_07_19_133114_add_one_col_to_request_payment', 1),
('2017_09_11_130240_insert_single_val_settings', 1),
('2017_09_13_063910_create_currency_tablee', 1),
('2017_09_19_064420_add_distance_to_request_payments', 1),
('2017_09_26_131757_create_call_center_managers_table', 1),
('2017_09_28_065058_create_manager_requests_table', 1),
('2017_09_28_073254_change_caller_id_from_int_to_string_in_manager_requests_table', 1),
('2017_09_29_122805_add_paymentAndCard_columns_in_manager_requests_table', 1),
('2017_09_29_123952_add_manager_request_id_in_requests_table', 1),
('2017_09_30_084502_undo_unique_emailColumn_in_managerRequests_table', 1),
('2017_09_30_090512_drop_emailColumn_in_managerRequests_table', 1),
('2017_09_30_090612_add_emailColumn_in_managerRequests_table', 1),
('2017_10_02_055853_create_payment_mode_and_default_card_in_call_center_managers_table', 1),
('2017_10_02_060629_drop_payment_mode_and_default_card_in_call_center_managers_table', 1),
('2017_10_02_071404_add_manager_id_in_manager_requests_table', 1),
('2017_10_03_073520_change_manager_request_id_in_requests_table', 1),
('2017_10_03_073903_add_manager_unique_id_in_requests_table', 1),
('2017_10_04_093855_add_register_status_in_users_table', 1),
('2017_10_04_113302_add_image_columns_in_requests_table', 1),
('2017_10_04_121119_make_manager_id_deafultZero_in_requests_table', 1),
('2017_10_07_100602_add_lat_long_columns_to_airport_details_table', 1),
('2017_10_07_100929_add_lat_long_columns_to_location_details_table', 1),
('2017_10_24_063505_add_cols_service_type', 1),
('2017_10_27_104309_create_admin_id_column_in_requestsTable', 1),
('2017_11_06_074123_create_multiple_permitted_columns_in_adminstable', 1),
('2017_11_06_121937_create_call_center_managers_in_admins_table', 1),
('2017_11_08_064810_create_cancellation_fine_column_in_admins_table', 1),
('2017_11_09_061352_add_prov_earning_req_payments', 1),
('2017_11_09_110935_create_advertisement_table', 1),
('2017_11_09_111934_add_ads_mannagement_column_in_admins_table', 1),
('2017_11_10_132838_add_urls_column_in_advertisement_table', 1),
('2017_11_22_143859_create_cancellation_reasons_table', 1),
('2017_11_24_065618_add_cf_user', 1),
('2017_12_06_071945_add_promo_code_into_req_payments', 1),
('2018_01_18_060352_add_columns_provider_table', 1),
('2018_02_05_101204_create_user_favorites_table', 1),
('2018_07_03_115016_create_debts_table', 1),
('2018_07_10_120327_add_request_cancelled_to_request_table', 1),
('2018_07_10_120450_add_force_close_fields_to_providers_table', 1),
('2018_07_13_125539_add_debt_allow_to_debts_table', 1),
('2018_07_16_101115_add_surge_to_request_table', 1),
('2018_09_10_064628_add_referral_fields_to_user', 1),
('2018_11_21_074258_additional_stop_details_to_requests_table', 1),
('2018_11_21_074318_add_is_address_changed_to_requests_table', 1),
('2018_11_27_084127_create_users_tron_wallet_table', 1),
('2018_12_04_105242_create_admin_tron_wallet_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_registers`
--

CREATE TABLE `mobile_registers` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `mobile_registers`
--

INSERT INTO `mobile_registers` (`id`, `type`, `count`, `created_at`, `updated_at`) VALUES
(1, 'android', 0, NULL, NULL),
(2, 'ios', 0, NULL, NULL),
(3, 'web', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `page_counters`
--

CREATE TABLE `page_counters` (
  `id` int(10) UNSIGNED NOT NULL,
  `page` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int(10) UNSIGNED NOT NULL,
  `scope` int(11) NOT NULL,
  `coupon_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `uses` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `short_description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `long_description` text COLLATE utf8_unicode_ci NOT NULL,
  `max_promo` int(11) NOT NULL,
  `max_usage` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE `providers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plate_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `car_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token_expiry` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_type` enum('android','ios') COLLATE utf8_unicode_ci NOT NULL,
  `login_by` enum('manual','facebook','google') COLLATE utf8_unicode_ci NOT NULL,
  `social_unique_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fb_lg` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gl_lg` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_activated` int(11) NOT NULL,
  `is_approved` int(11) NOT NULL,
  `is_available` int(11) NOT NULL,
  `waiting_to_respond` int(11) NOT NULL,
  `corporate_id` int(11) NOT NULL,
  `gender` enum('male','female','others') COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double(15,8) NOT NULL,
  `longitude` double(15,8) NOT NULL,
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_activation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_email_activated` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token_refresh` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `app_version` double(8,2) NOT NULL DEFAULT '0.00',
  `is_closed` int(11) NOT NULL DEFAULT '0',
  `closed_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_availabilities`
--

CREATE TABLE `provider_availabilities` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_documents`
--

CREATE TABLE `provider_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) NOT NULL,
  `document_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `document_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_ratings`
--

CREATE TABLE `provider_ratings` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_services`
--

CREATE TABLE `provider_services` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_available` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL DEFAULT '0',
  `admin_id` int(11) NOT NULL,
  `manager_uniq_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `current_provider` int(11) NOT NULL,
  `confirmed_provider` int(11) NOT NULL,
  `request_start_time` datetime NOT NULL,
  `later` int(11) NOT NULL,
  `requested_time` datetime NOT NULL,
  `s_latitude` double(15,8) NOT NULL,
  `s_longitude` double(15,8) NOT NULL,
  `d_latitude` double(15,8) NOT NULL,
  `d_longitude` double(15,8) NOT NULL,
  `is_paid` tinyint(4) NOT NULL,
  `s_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `d_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `cancellation_fine` double(8,2) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `provider_status` int(11) NOT NULL,
  `request_type` int(11) NOT NULL,
  `request_status_type` int(11) NOT NULL,
  `hourly_package_id` int(11) NOT NULL,
  `airport_price_id` int(11) NOT NULL,
  `request_meta_id` int(11) NOT NULL,
  `promo_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `promo_scope` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `before_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `after_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cancel_dri_shown` int(11) NOT NULL DEFAULT '0',
  `cancel_usr_shown` int(11) NOT NULL DEFAULT '0',
  `request_cancelled` int(11) NOT NULL DEFAULT '0',
  `surge` double(8,2) NOT NULL DEFAULT '1.00',
  `is_adstop` int(11) NOT NULL DEFAULT '0',
  `adstop_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adstop_latitude` double(15,8) NOT NULL,
  `adstop_longitude` double(15,8) NOT NULL,
  `is_address_changed` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests_meta`
--

CREATE TABLE `requests_meta` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_cancelled` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_payments`
--

CREATE TABLE `request_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_id` int(11) NOT NULL,
  `payment_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_mode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_time` double NOT NULL,
  `base_price` double(8,2) NOT NULL,
  `min_fare` double(8,2) NOT NULL,
  `time_price` double(8,2) NOT NULL,
  `tax_price` double(8,2) NOT NULL,
  `booking_fee` double(8,2) NOT NULL,
  `promo_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `promo_value` double(8,2) NOT NULL,
  `provider_earnings` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total` double(8,2) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `distance_unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `distance_travel` double(8,2) NOT NULL,
  `distance_price` double(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

CREATE TABLE `service_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plate_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `number_seat` int(11) NOT NULL,
  `base_fare` double(8,2) NOT NULL,
  `min_fare` double(8,2) NOT NULL,
  `tax_fee` double(8,2) NOT NULL,
  `booking_fee` double(8,2) NOT NULL,
  `price_per_min` double(8,2) NOT NULL,
  `price_per_unit_distance` double(8,2) NOT NULL,
  `distance_unit` enum('kms','miles') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `service_types`
--

INSERT INTO `service_types` (`id`, `name`, `provider_name`, `color`, `model`, `plate_no`, `picture`, `status`, `order`, `created_at`, `updated_at`, `number_seat`, `base_fare`, `min_fare`, `tax_fee`, `booking_fee`, `price_per_min`, `price_per_unit_distance`, `distance_unit`) VALUES
(1, 'sedan', 'sedan', '', '', '', '', 1, 0, '2018-12-07 10:19:36', '2018-12-07 10:19:36', 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'kms');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `status`, `created_at`, `updated_at`) VALUES
(130, 'site_name', 'Smart Car', 0, NULL, NULL),
(131, 'site_logo', '', 0, NULL, NULL),
(132, 'site_icon', '', 0, NULL, NULL),
(133, 'provider_select_timeout', '60', 0, NULL, NULL),
(134, 'search_radius', '100', 0, NULL, NULL),
(135, 'base_price', '50', 0, NULL, NULL),
(136, 'price_per_minute', '10', 0, NULL, NULL),
(137, 'tax_price', '50', 0, NULL, NULL),
(138, 'price_per_unit_distance', '10', 0, NULL, NULL),
(139, 'stripe_secret_key', '', 0, NULL, NULL),
(140, 'stripe_publishable_key', '', 0, NULL, NULL),
(141, 'cod', '1', 0, NULL, NULL),
(142, 'paypal', '1', 0, NULL, NULL),
(143, 'card', '1', 0, NULL, NULL),
(144, 'walletbay', '1', 0, NULL, NULL),
(145, 'manual_request', '1', 0, NULL, NULL),
(146, 'paypal_email', '', 0, NULL, NULL),
(147, 'default_lang', 'en', 0, NULL, NULL),
(148, 'currency', '$', 0, NULL, NULL),
(149, 'mail_logo', '', 0, NULL, NULL),
(150, 'default_distance_unit', 'miles', 0, NULL, NULL),
(151, 'price_per_service', '1', 0, NULL, NULL),
(152, 'wallet_bay_key', '', 0, NULL, NULL),
(153, 'wallet_url', 'http://walletbay.net/apps', 0, NULL, NULL),
(154, 'provider_commission', '60', 0, NULL, NULL),
(155, 'cancellation_fine', '', 0, NULL, NULL),
(156, 'gcm_key', '', 0, NULL, NULL),
(157, 'force_upgrade', '0', 0, NULL, NULL),
(158, 'android_user_version', '1', 0, NULL, NULL),
(159, 'android_driver_version', '1', 0, NULL, NULL),
(160, 'ios_user_version', '1', 0, NULL, NULL),
(161, 'ios_driver_version', '1', 0, NULL, NULL),
(162, 'accept_debt_cash', '0', 0, NULL, NULL),
(163, 'surge_status', '0', 0, NULL, NULL),
(164, 'surge_a', '1', 0, NULL, NULL),
(165, 'surge_b', '1', 0, NULL, NULL),
(166, 'surge_c', '1', 0, NULL, NULL),
(167, 'surge_d', '1', 0, NULL, NULL),
(168, 'surge_e', '1', 0, NULL, NULL),
(169, 'surge_f', '1', 0, NULL, NULL),
(170, 'surge_g', '1', 0, NULL, NULL),
(171, 'referrer_bonus', '0', 0, NULL, NULL),
(172, 'referee_bonus', '0', 0, NULL, NULL),
(173, 'tron_address_base58', 'TLZdkGdKet4rMz4MShwJ8MzJSDuGvjFhYA', 0, NULL, NULL),
(174, 'tron_address_hex', 'TLZdkGdKet4rMz4MShwJ8MzJSDuGvjFhYA', 0, NULL, NULL),
(175, 'tron_private_key', '', 0, NULL, NULL),
(176, 'tron_api_url', 'http://46.101.106.16:3000', 0, NULL, NULL),
(177, 'tron_wallet', '1', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_registered` int(11) NOT NULL DEFAULT '0',
  `otp` int(11) NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token_expiry` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_type` enum('android','ios','web') COLLATE utf8_unicode_ci NOT NULL,
  `login_by` enum('manual','facebook','google') COLLATE utf8_unicode_ci NOT NULL,
  `social_unique_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fb_lg` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gl_lg` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_activated` int(11) NOT NULL,
  `gender` enum('male','female','others') COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double(15,8) NOT NULL,
  `longitude` double(15,8) NOT NULL,
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` int(11) NOT NULL,
  `payment_mode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cancellation_charges` double(8,2) NOT NULL,
  `default_card` int(11) NOT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `referral_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_ref_used` int(11) NOT NULL DEFAULT '0',
  `referrer_bonus` double(8,2) NOT NULL DEFAULT '0.00',
  `referee_bonus` double(8,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_tron_wallet`
--

CREATE TABLE `users_tron_wallet` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `private_key` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `public_key` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address_base58` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address_hex` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_favourites`
--

CREATE TABLE `user_favourites` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `favourite_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_ratings`
--

CREATE TABLE `user_ratings` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `walk_locations`
--

CREATE TABLE `walk_locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_id` int(11) NOT NULL,
  `latitude` double(15,8) NOT NULL,
  `longitude` double(15,8) NOT NULL,
  `distance` double(15,8) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_tron_wallet`
--
ALTER TABLE `admin_tron_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airport_details`
--
ALTER TABLE `airport_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airport_prices`
--
ALTER TABLE `airport_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `call_center_managers`
--
ALTER TABLE `call_center_managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `call_center_managers_email_unique` (`email`);

--
-- Indexes for table `cancellation_reasons`
--
ALTER TABLE `cancellation_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `corporates`
--
ALTER TABLE `corporates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `corporates_email_unique` (`email`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `debts`
--
ALTER TABLE `debts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hourly_packages`
--
ALTER TABLE `hourly_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`);

--
-- Indexes for table `location_details`
--
ALTER TABLE `location_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manager_requests`
--
ALTER TABLE `manager_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_registers`
--
ALTER TABLE `mobile_registers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_counters`
--
ALTER TABLE `page_counters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `providers_email_unique` (`email`);

--
-- Indexes for table `provider_availabilities`
--
ALTER TABLE `provider_availabilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_documents`
--
ALTER TABLE `provider_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_ratings`
--
ALTER TABLE `provider_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_services`
--
ALTER TABLE `provider_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests_meta`
--
ALTER TABLE `requests_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_payments`
--
ALTER TABLE `request_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_types`
--
ALTER TABLE `service_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users_tron_wallet`
--
ALTER TABLE `users_tron_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_favourites`
--
ALTER TABLE `user_favourites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_ratings`
--
ALTER TABLE `user_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `walk_locations`
--
ALTER TABLE `walk_locations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `admin_tron_wallet`
--
ALTER TABLE `admin_tron_wallet`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `airport_details`
--
ALTER TABLE `airport_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `airport_prices`
--
ALTER TABLE `airport_prices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `call_center_managers`
--
ALTER TABLE `call_center_managers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cancellation_reasons`
--
ALTER TABLE `cancellation_reasons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `corporates`
--
ALTER TABLE `corporates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `debts`
--
ALTER TABLE `debts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hourly_packages`
--
ALTER TABLE `hourly_packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `location_details`
--
ALTER TABLE `location_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `manager_requests`
--
ALTER TABLE `manager_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mobile_registers`
--
ALTER TABLE `mobile_registers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `page_counters`
--
ALTER TABLE `page_counters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `providers`
--
ALTER TABLE `providers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `provider_availabilities`
--
ALTER TABLE `provider_availabilities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `provider_documents`
--
ALTER TABLE `provider_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `provider_ratings`
--
ALTER TABLE `provider_ratings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `provider_services`
--
ALTER TABLE `provider_services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `requests_meta`
--
ALTER TABLE `requests_meta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `request_payments`
--
ALTER TABLE `request_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `service_types`
--
ALTER TABLE `service_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_tron_wallet`
--
ALTER TABLE `users_tron_wallet`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_favourites`
--
ALTER TABLE `user_favourites`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_ratings`
--
ALTER TABLE `user_ratings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `walk_locations`
--
ALTER TABLE `walk_locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
