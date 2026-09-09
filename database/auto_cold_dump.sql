-- MySQL dump 10.13  Distrib 8.0.46, for Linux (aarch64)
--
-- Host: localhost    Database: auto_cold
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('auto-cold-cache-app_settings','a:16:{s:12:\"company_name\";s:9:\"AUTO COLD\";s:16:\"company_subtitle\";s:27:\"Elétrica e Ar Condicionado\";s:10:\"owner_name\";s:15:\"JULIANO RIBEIRO\";s:8:\"whatsapp\";s:11:\"64993295596\";s:5:\"phone\";s:15:\"(64) 99329-5596\";s:6:\"slogan\";s:68:\"CONFIANÇA E QUALIDADE: A COMBINAÇÃO PERFEITA PARA O SEU VEÍCULO.\";s:7:\"address\";s:39:\"R. Monte Alegre, 271 - Jardim Liberdade\";s:4:\"city\";s:25:\"Itumbiara - GO, 75510-090\";s:8:\"latitude\";s:11:\"-18.4079971\";s:9:\"longitude\";s:11:\"-49.2249619\";s:9:\"maps_link\";s:259:\"https://www.google.com/maps/place/R.+Monte+Alegre,+271+-+Jardim+Liberdade,+Itumbiara+-+GO,+75510-090/@-18.4079971,-49.2256056,238m/data=!3m2!1e3!4b1!4m6!3m5!1s0x94a10d0d1df7616d:0x234bb98b2302d442!8m2!3d-18.4079971!4d-49.2249619!16s%2Fg%2F11wvgpf2r5?entry=ttu\";s:13:\"primary_color\";s:7:\"#06b6d4\";s:15:\"secondary_color\";s:7:\"#2563eb\";s:9:\"logo_dark\";s:20:\"images/logo-dark.png\";s:10:\"logo_light\";s:21:\"images/logo-light.png\";s:11:\"logo_height\";s:2:\"48\";}',2104266157);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'eletrica',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Baterias & Acumuladores','baterias-acumuladores','eletrica','Baterias automotivas 12V/24V para veículos leves, utilitários e caminhões.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(2,'Motores de Partida & Alternadores','partida-e-alternadores','eletrica','Peças completas e reparos: induzidos, estatores, placas de diodo, reguladores e escovas.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(3,'Iluminação & Sinalização','iluminacao-sinalizacao','eletrica','Lâmpadas H1, H4, H7, lâmpadas de painel, lanternas e relés de pisca.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(4,'Relés, Fusíveis & Chicotes','reles-fusiveis-chicotes','eletrica','Relés de 4 e 5 pinos, caixas de fusíveis tipo faca, mini e max, terminais elétricos.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(5,'Sensores & Injeção Eletrônica','sensores-injecao-eletronica','eletrica','Sonda lambda, sensor de rotação, sensor de temperatura, atuadores e conectores.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(6,'Ar Condicionado Automotivo','ar-condicionado-automotivo','eletrica','Gás refrigerante R134a, óleo PAG, compressores, condensadores e filtros de cabine.','2026-09-08 22:22:28','2026-09-08 22:22:28');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `neighborhood` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Roberto Fernandes da Silva','284.912.848-12','roberto.silva@gmail.com','(64) 99245-1234','64992451234',NULL,'Rua Santos Dumont, 450 - Bairro Afonso Pena',NULL,NULL,'Itumbiara','GO',NULL,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL),(2,'Mariana Alcantara Santos','391.842.108-55','mariana.alcantara@hotmail.com','(64) 99876-5432','64998765432',NULL,'Av. Modesto de Carvalho, 1120',NULL,NULL,'Itumbiara','GO',NULL,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL),(3,'CLIENTE AVULSO / BALCÃO',NULL,NULL,'(00) 0000-0000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Cadastro rápido para atendimentos sem identificação de cliente.','2026-09-08 22:35:04','2026-09-08 22:35:04',NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_09_08_000001_create_roles_and_acl_tables',1),(5,'2026_09_08_000002_create_suppliers_table',1),(6,'2026_09_08_000003_create_categories_table',1),(7,'2026_09_08_000004_create_products_table',1),(8,'2026_09_08_000005_create_stock_movements_table',1),(9,'2026_09_08_000006_create_customers_and_vehicles_tables',1),(10,'2026_09_08_000007_create_service_orders_tables',1),(11,'2026_09_08_000008_create_purchase_orders_tables',1),(12,'2026_09_08_000009_create_service_order_photos_tables',1),(13,'2026_09_08_221344_create_settings_table',1),(14,'2026_09_08_224654_make_product_id_nullable_and_add_item_name_to_service_order_items',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `default_supplier_id` bigint unsigned DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oem_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufacturer_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `voltage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amperage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `power` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin_count` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_compatibility` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UN',
  `current_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `min_stock` decimal(12,2) NOT NULL DEFAULT '1.00',
  `cost_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_barcode_unique` (`barcode`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_default_supplier_id_foreign` (`default_supplier_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_default_supplier_id_foreign` FOREIGN KEY (`default_supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,2,'BAT-MOU-60','7891234560012','M60GD',NULL,'Bateria Automotiva 60Ah 12V Polo Direito (M60GD)',NULL,NULL,'12V','60Ah',NULL,NULL,'Linha leve universal: Gol, Onix, HB20, Fox, Polo, Ka, Fiesta','Prateleira A1 - Piso','UN',8.00,5.00,380.00,540.00,1,'2026-09-08 22:22:28','2026-09-08 22:45:21',NULL),(2,1,2,'BAT-MOU-150','7891234560029','M150BD',NULL,'Bateria Caminhão / Utilitário 150Ah 12V (M150BD)',NULL,NULL,'12V','150Ah',NULL,NULL,'Caminhões Mercedes-Benz, Ford Cargo, Volvo VM, Tratores','Prateleira A2 - Piso','UN',3.00,2.00,820.00,1190.00,1,'2026-09-08 22:22:28','2026-09-08 22:45:16',NULL),(3,2,1,'ARR-BOS-12V-01','7891234560036','F000AL0401',NULL,'Motor de Arranque / Partida 12V Bosch',NULL,NULL,'12V','1.1kW',NULL,NULL,'VW Gol, Voyage, Saveiro, Fox motor EA111 1.0 e 1.6','Prateleira B1 - Caixa 04','UN',1.00,2.00,420.00,650.00,1,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL),(4,2,1,'REG-BOS-14V-90A','7891234560043','F00M145225',NULL,'Regulador de Voltagem 14V Bosch 90A/120A',NULL,NULL,'12V','90A',NULL,NULL,'Alternadores Bosch Fiat Palio, Strada, Uno, Punto Fire','Gaveteiro G3 - Gaveta 12','UN',2.00,4.00,85.00,145.00,1,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL),(5,4,3,'REL-DNI-0101','7891234560050','DNI-0101',NULL,'Relé Auxiliar Universal 12V 40A 4 Pinos DNI',NULL,NULL,'12V','40A',NULL,NULL,'Farol de milha, buzina, ar condicionado, bomba de combustível','Gaveteiro G1 - Gaveta 01','UN',28.00,15.00,12.00,28.00,1,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL),(6,6,1,'GAS-R134A-13KG','7891234560067',NULL,NULL,'Gás Refrigerante R134a Automotivo Lata 13.6kg',NULL,NULL,'Universal',NULL,NULL,NULL,'Carga e recarga de ar condicionado automotivo em geral','Almoxarifado Central - Cilindros','KG',4.00,2.00,540.00,890.00,1,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `service_order_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT '1.00',
  `unit_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `purchase_date` date DEFAULT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_order_code_unique` (`order_code`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_service_order_id_foreign` (`service_order_id`),
  KEY `purchase_orders_product_id_foreign` (`product_id`),
  KEY `purchase_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `purchase_orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
INSERT INTO `purchase_orders` VALUES (1,'PED-2026-0001',NULL,1,NULL,1,'Mercado Livre','MLB-200049281','NL123456789BR','https://rastreamento.correios.com.br','Módulo de Injeção Bosch ME7.5.30 Gol G5',1.00,650.00,35.00,685.00,'shipped','2026-09-07','2026-09-10',NULL,'Vendedor Mercado Líder Platinum com garantia de 6 meses.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(2,'PED-2026-0002',NULL,NULL,NULL,1,'Shopee','SHP-99214012','BR987654321SP',NULL,'Kit 100 Conectores Chicote Automotivo 2 e 4 Vias',1.00,89.90,0.00,89.90,'pending','2026-09-08','2026-09-13',NULL,'Reposição de conectores para bancada elétrica.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(3,'PED-2026-0003',NULL,2,NULL,1,'Distribuidora Local','DST-44109','ENTREGA-MOTOBOY',NULL,'Compressor Denso 12V Ar Condicionado Fiat Strada',1.00,1250.00,20.00,1270.00,'delivered','2026-09-05',NULL,'2026-09-06 22:22:28','Compressor novo original com nota fiscal e garantia.','2026-09-08 22:22:28','2026-09-08 22:22:28');
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Administrador','Acesso total a todas as funções, relatórios, cadastros e configurações do sistema.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(2,'manager','Gerente','Gerencia compras, fornecedores, estoque e usuários operacionais.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(3,'electrician','Eletricista / Técnico','Consulta peças elétricas e requisita materiais para veículos/serviços.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(4,'stockist','Estoquista / Atendente','Registra entradas e saídas de peças e gerencia localização no almoxarifado.','2026-09-08 22:22:28','2026-09-08 22:22:28');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_order_items`
--

DROP TABLE IF EXISTS `service_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UN',
  `unit_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_order_items_service_order_id_foreign` (`service_order_id`),
  KEY `service_order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `service_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_order_items_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_order_items`
--

LOCK TABLES `service_order_items` WRITE;
/*!40000 ALTER TABLE `service_order_items` DISABLE KEYS */;
INSERT INTO `service_order_items` VALUES (1,1,3,NULL,1.00,'UN',420.00,650.00,650.00,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(2,2,1,NULL,1.00,'UN',380.00,540.00,540.00,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(3,2,5,NULL,1.00,'UN',12.00,28.00,28.00,'2026-09-08 22:22:28','2026-09-08 22:22:28');
/*!40000 ALTER TABLE `service_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_order_photos`
--

DROP TABLE IF EXISTS `service_order_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_order_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `stage` enum('before','after','diagnostic') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'before',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_order_photos_service_order_id_foreign` (`service_order_id`),
  KEY `service_order_photos_user_id_foreign` (`user_id`),
  CONSTRAINT `service_order_photos_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_order_photos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_order_photos`
--

LOCK TABLES `service_order_photos` WRITE;
/*!40000 ALTER TABLE `service_order_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_order_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_order_services`
--

DROP TABLE IF EXISTS `service_order_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_order_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_order_id` bigint unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT '1.00',
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_order_services_service_order_id_foreign` (`service_order_id`),
  CONSTRAINT `service_order_services_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_order_services`
--

LOCK TABLES `service_order_services` WRITE;
/*!40000 ALTER TABLE `service_order_services` DISABLE KEYS */;
INSERT INTO `service_order_services` VALUES (1,1,'Mão de obra para troca de motor de arranque e teste de carga no alternador',1.00,220.00,220.00,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(2,2,'Serviço de recarga de gás R134a + Teste de vazamento UV + Higienização de Ar Condicionado',1.00,280.00,280.00,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(3,2,'Diagnóstico elétrico com scanner e teste de fuga de corrente',1.00,120.00,120.00,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(4,3,'REVISÃO E MONTAGEM',1.00,200.00,200.00,'2026-09-08 22:44:06','2026-09-08 22:44:06');
/*!40000 ALTER TABLE `service_order_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_orders`
--

DROP TABLE IF EXISTS `service_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `vehicle_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `status` enum('budget','approved','in_progress','waiting_parts','completed','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'budget',
  `reported_defect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `technical_diagnosis` text COLLATE utf8mb4_unicode_ci,
  `solution_applied` text COLLATE utf8mb4_unicode_ci,
  `services_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `products_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `entry_km` int DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `expected_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `internal_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_orders_order_number_unique` (`order_number`),
  KEY `service_orders_customer_id_foreign` (`customer_id`),
  KEY `service_orders_vehicle_id_foreign` (`vehicle_id`),
  KEY `service_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `service_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `service_orders_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_orders`
--

LOCK TABLES `service_orders` WRITE;
/*!40000 ALTER TABLE `service_orders` DISABLE KEYS */;
INSERT INTO `service_orders` VALUES (1,'OS-2026-0001',1,1,2,'in_progress','Veículo não dá partida pela manhã, faz barulho de estalo no motor de arranque mas não gira o motor.','Bateria com carga normal (12.6V). Testado motor de arranque na bancada: induzido em curto e escovas desgastadas.','Substituição do motor de arranque e revisão do cabo positivo da bateria.',220.00,650.00,0.00,870.00,64500,'2026-09-06','2026-09-09',NULL,NULL,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(2,'OS-2026-0002',2,2,2,'completed','Ar condicionado parou de gelar no trânsito e luz da bateria piscou no painel.','Vazamento de gás na válvula de serviço e alternador com regulador de voltagem com falha.','Carga de gás R134a ecológica com contraste, higienização com ozônio e troca da bateria Moura 60Ah.',400.00,568.00,0.00,968.00,38000,'2026-09-05',NULL,'2026-09-07',NULL,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(3,'OS-2026-0003',3,3,1,'budget','DFSDFDSF','SDFSFSDF',NULL,200.00,0.00,0.00,200.00,NULL,'2026-09-08',NULL,NULL,NULL,'2026-09-08 22:43:01','2026-09-08 22:45:21');
/*!40000 ALTER TABLE `service_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('ybNGZh7qvPT8KRJrULs5ypJ4PlrcpzIlW0T6BLDm',1,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZ2lBWHdCeTVtRUQ0WUlJRnZ3WENsYWRxWGRnVHl5WU5qd1p1aHJYayI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvc2VydmljZV9vcmRlcnMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIxOiJzZXJ2aWNlX29yZGVycy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1788907649);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'company_name','AUTO COLD','2026-09-08 22:22:28','2026-09-08 22:22:28'),(2,'company_subtitle','Elétrica e Ar Condicionado','2026-09-08 22:22:28','2026-09-08 22:22:28'),(3,'owner_name','JULIANO RIBEIRO','2026-09-08 22:22:28','2026-09-08 22:22:28'),(4,'whatsapp','64993295596','2026-09-08 22:22:28','2026-09-08 22:22:28'),(5,'phone','(64) 99329-5596','2026-09-08 22:22:28','2026-09-08 22:22:28'),(6,'slogan','CONFIANÇA E QUALIDADE: A COMBINAÇÃO PERFEITA PARA O SEU VEÍCULO.','2026-09-08 22:22:28','2026-09-08 22:22:28'),(7,'address','R. Monte Alegre, 271 - Jardim Liberdade','2026-09-08 22:22:28','2026-09-08 22:22:28'),(8,'city','Itumbiara - GO, 75510-090','2026-09-08 22:22:28','2026-09-08 22:22:28'),(9,'latitude','-18.4079971','2026-09-08 22:22:28','2026-09-08 22:22:28'),(10,'longitude','-49.2249619','2026-09-08 22:22:28','2026-09-08 22:22:28'),(11,'maps_link','https://www.google.com/maps/place/R.+Monte+Alegre,+271+-+Jardim+Liberdade,+Itumbiara+-+GO,+75510-090/@-18.4079971,-49.2256056,238m/data=!3m2!1e3!4b1!4m6!3m5!1s0x94a10d0d1df7616d:0x234bb98b2302d442!8m2!3d-18.4079971!4d-49.2249619!16s%2Fg%2F11wvgpf2r5?entry=ttu','2026-09-08 22:22:28','2026-09-08 22:22:28'),(12,'primary_color','#06b6d4','2026-09-08 22:22:28','2026-09-08 22:22:28'),(13,'secondary_color','#2563eb','2026-09-08 22:22:28','2026-09-08 22:22:28'),(14,'logo_dark','images/logo-dark.png','2026-09-08 22:22:28','2026-09-08 22:22:28'),(15,'logo_light','images/logo-light.png','2026-09-08 22:22:28','2026-09-08 22:22:28'),(16,'logo_height','48','2026-09-08 22:22:28','2026-09-08 22:22:28');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `type` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` enum('purchase','adjustment_in','return_in','service_order','direct_sale','internal_use','loss_damage','adjustment_out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_plate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_user_id_foreign` (`user_id`),
  KEY `stock_movements_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
INSERT INTO `stock_movements` VALUES (1,1,1,2,'in','purchase',10.00,380.00,540.00,3800.00,'NF-98421',NULL,NULL,'Entrada lote mensal baterias 60Ah Moura.','2026-09-03 22:22:28','2026-09-08 22:22:28'),(2,3,1,1,'in','purchase',2.00,420.00,650.00,840.00,'NF-77341',NULL,NULL,'Motores de arranque para linha EA111.','2026-09-05 22:22:28','2026-09-08 22:22:28'),(3,6,1,1,'in','purchase',4.00,540.00,890.00,2160.00,'NF-55120',NULL,NULL,'Cilindros de gás ecológico R134a para recargas de ar condicionado.','2026-09-06 22:22:28','2026-09-08 22:22:28'),(4,2,1,NULL,'out','service_order',1.00,820.00,12000.00,12000.00,NULL,'ABC123','OS-2026-0003','Peça aplicada na OS-2026-0003','2026-09-08 22:43:49','2026-09-08 22:43:49'),(5,1,1,NULL,'out','service_order',1.00,380.00,1000.00,1000.00,NULL,'ABC123','OS-2026-0003','Peça aplicada na OS-2026-0003','2026-09-08 22:45:11','2026-09-08 22:45:11'),(6,2,1,NULL,'in','return_in',1.00,820.00,12000.00,12000.00,NULL,'ABC123','OS-2026-0003','Estorno de peça removida da OS-2026-0003','2026-09-08 22:45:16','2026-09-08 22:45:16'),(7,1,1,NULL,'in','return_in',1.00,380.00,1000.00,1000.00,NULL,'ABC123','OS-2026-0003','Estorno de peça removida da OS-2026-0003','2026-09-08 22:45:21','2026-09-08 22:45:21');
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `corporate_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_registration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `neighborhood` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_document_number_unique` (`document_number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'Robert Bosch Ltda','Bosch Autopeças','45.990.181/0001-89','112.456.789.110','vendas.auto@bosch.com.br','(11) 3741-2000','(11) 98765-4321','Ricardo Silva',NULL,'Via Anhangüera, km 98',NULL,NULL,'Campinas','SP','Fornecedor principal de alternadores, motores de partida, sensores e velas.',1,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL),(2,'Acumuladores Moura S/A','Baterias Moura','10.584.090/0001-92','020.123.456.789','comercial@moura.com.br','(81) 3411-1000','(11) 97777-8888','Fernanda Lima',NULL,'Rua Cel. Antonio Marinho, 65',NULL,NULL,'Belo Jardim','PE','Distribuidor direto de baterias 12V e 24V para linha leve e pesada.',1,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL),(3,'DNI Indústria Eletrônica e Autopeças Ltda','DNI Eletrônica Automotiva','54.218.441/0001-50','108.987.654.321','vendas@dni.com.br','(11) 3933-8888','(11) 98888-4433','Eduardo Martins',NULL,NULL,NULL,NULL,'São Paulo','SP','Especialista em relés auxiliares, sirenes, chicotes e conectores.',1,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL),(4,'Osram do Brasil Lâmpadas Elétricas Ltda','Osram Iluminação Automotiva','61.123.456/0001-01',NULL,'contato.auto@osram.com.br','(11) 3687-1000','(11) 99123-5566','Juliana Costa',NULL,NULL,NULL,NULL,'Osasco','SP','Linha completa de lâmpadas halógenas, LED e xenon automotivos.',1,'2026-09-08 22:22:28','2026-09-08 22:22:28',NULL);
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Administrador Auto Cold','admin','admin@autocold.com.br','(64) 99329-5596',NULL,'$2y$12$DQWsACq1yQlCAgE20eklUONuWFWXUj1TkKfcrN9jexmPDpR40Stjy',1,NULL,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(2,3,'Carlos Eletricista','carlos.eletrica','carlos@autocold.com.br','(64) 99999-0002',NULL,'$2y$12$yvZ0q.MDinZObuSwoWjKnOzoWQDz8TEilMMkF3nZT3dK0pFKOCflu',1,NULL,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(3,4,'Marcos Estoque','marcos.estoque','marcos@autocold.com.br','(64) 99999-0003',NULL,'$2y$12$Dd.OD7c7oxYnpALU7fzUPuiem1qDtRgoyzUDvVmjftEUFIsVs69dq',1,NULL,'2026-09-08 22:22:28','2026-09-08 22:22:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `plate` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fuel_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chassis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_km` int DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_plate_unique` (`plate`),
  KEY `vehicles_customer_id_foreign` (`customer_id`),
  CONSTRAINT `vehicles_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,1,'BRA2E19','Volkswagen','Gol G6 1.6 MSI Flex','2015/2016','Prata','Flex',NULL,64500,NULL,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(2,2,'RTO9A88','Fiat','Strada Freedom 1.3 Cabine Dupla','2022/2023','Preto','Flex',NULL,38000,NULL,'2026-09-08 22:22:28','2026-09-08 22:22:28'),(3,3,'ABC123','Geral','GOL 1.5',NULL,NULL,NULL,NULL,NULL,NULL,'2026-09-08 22:43:01','2026-09-08 22:43:01');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-09  4:18:29
