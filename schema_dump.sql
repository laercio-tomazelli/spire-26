mysqldump: Deprecated program name. It will be removed in a future release, use '/usr/bin/mariadb-dump' instead
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: spire_prod_new_01_12
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `bill_of_materials`
--

DROP TABLE IF EXISTS `bill_of_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bill_of_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` int(11) DEFAULT NULL,
  `model_id` bigint(20) DEFAULT NULL,
  `part_id` bigint(20) DEFAULT NULL,
  `qtd` tinyint(4) NOT NULL,
  `line` varchar(50) DEFAULT NULL,
  `is_provided` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bling_tokens`
--

DROP TABLE IF EXISTS `bling_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bling_tokens` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `client_id` varchar(255) DEFAULT NULL,
  `secret_key` varchar(255) DEFAULT '255',
  `authorization` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `created_at` varchar(0) DEFAULT NULL,
  `updated_at` varchar(0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ceps`
--

DROP TABLE IF EXISTS `ceps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ceps` (
  `Codigo` bigint(20) NOT NULL AUTO_INCREMENT,
  `cep` int(10) unsigned zerofill DEFAULT NULL,
  `cep_faixa` int(10) unsigned zerofill DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `cidade` varchar(250) DEFAULT NULL,
  `endereco` varchar(250) DEFAULT NULL,
  `complemento` varchar(250) DEFAULT NULL,
  `bairro` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=1307623 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clifor`
--

DROP TABLE IF EXISTS `clifor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clifor` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `codigo_tipo` varchar(50) DEFAULT NULL,
  `codigo_atividade` varchar(50) DEFAULT NULL,
  `nome_razao` varchar(255) DEFAULT NULL,
  `nome_fantasia` varchar(255) DEFAULT NULL,
  `nome_razao_reduzido` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `numero_endereco` varchar(10) DEFAULT NULL,
  `complemento_endereco` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `codigo_cidade` varchar(50) NOT NULL DEFAULT '0',
  `uf` varchar(2) DEFAULT NULL,
  `codigo_pais` varchar(50) NOT NULL DEFAULT '0',
  `pais` varchar(50) NOT NULL DEFAULT 'Brasil',
  `cep` varchar(15) DEFAULT NULL,
  `cpf_cnpj` varchar(20) DEFAULT NULL,
  `rg_ie` varchar(20) DEFAULT NULL,
  `ccm` varchar(20) DEFAULT NULL,
  `ddd_residencia` varchar(10) DEFAULT NULL,
  `telefone_residencia` varchar(19) DEFAULT NULL,
  `ddd_comercial` varchar(10) DEFAULT NULL,
  `telefone_comercial` varchar(20) DEFAULT NULL,
  `ddd_celular` varchar(10) DEFAULT NULL,
  `telefone_celular` varchar(18) DEFAULT NULL,
  `ddd_recado` varchar(2) DEFAULT NULL,
  `telefone_recado` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  `contato_adm` varchar(255) DEFAULT NULL,
  `contato_financeiro` varchar(255) DEFAULT NULL,
  `contato_tecnico` varchar(255) DEFAULT NULL,
  `contato_logistica` varchar(255) DEFAULT NULL,
  `classe` varchar(50) DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `obs_cliente` varchar(255) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `consumidor_especial` varchar(19) DEFAULT NULL,
  `codigo_posto` varchar(9) DEFAULT NULL,
  `numero_os` varchar(9) DEFAULT NULL,
  `regiao` varchar(17) DEFAULT NULL,
  `uid` varchar(50) DEFAULT NULL,
  `serie_clifor` varchar(16) DEFAULT NULL,
  `modelo_clifor` varchar(12) DEFAULT NULL,
  `uid_file` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `from_invoice` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `nome_razao` (`nome_razao`),
  KEY `telefone_celular` (`telefone_celular`),
  KEY `cidade` (`cidade`),
  KEY `telefone_residencia` (`telefone_residencia`),
  KEY `uf` (`uf`),
  KEY `modelo` (`modelo_clifor`),
  KEY `cpf_cnpj` (`cpf_cnpj`),
  KEY `cep` (`cep`),
  KEY `numero_os` (`numero_os`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clifor_changes`
--

DROP TABLE IF EXISTS `clifor_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clifor_changes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo_clifor` int(11) NOT NULL,
  `field` varchar(100) NOT NULL,
  `previous_value` varchar(255) DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contact_type`
--

DROP TABLE IF EXISTS `contact_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contact_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `ddd` varchar(2) DEFAULT NULL,
  `contact_number` varchar(50) NOT NULL,
  `contact_person` varchar(50) DEFAULT NULL,
  `preferential` tinyint(4) NOT NULL DEFAULT 0,
  `obs` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=648 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `costs`
--

DROP TABLE IF EXISTS `costs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `costs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_type` varchar(250) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `lg_value` float DEFAULT 0,
  `tcl_value` float DEFAULT 0,
  `britania_value` float DEFAULT 0,
  `efl_value` float DEFAULT 0,
  `default` tinyint(4) NOT NULL,
  `fixed_cost` tinyint(4) DEFAULT 1,
  `fixed_unit` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `document_types`
--

DROP TABLE IF EXISTS `document_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_types` (
  `id` bigint(20) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_types_type_unique` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ex_evidence_file_types`
--

DROP TABLE IF EXISTS `ex_evidence_file_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ex_evidence_file_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `mandatory` varchar(5) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ex_evidence_files`
--

DROP TABLE IF EXISTS `ex_evidence_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ex_evidence_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exchange_id` bigint(20) unsigned DEFAULT NULL,
  `uuid` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `type_file` bigint(20) unsigned DEFAULT NULL,
  `observation` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ex_evidence_files_exchange_id_index` (`exchange_id`),
  KEY `ex_evidence_files_uuid_index` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ex_follow_files`
--

DROP TABLE IF EXISTS `ex_follow_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ex_follow_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ex_follow_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ex_follow_files_ex_follow_id_index` (`ex_follow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ex_follow_privacies`
--

DROP TABLE IF EXISTS `ex_follow_privacies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ex_follow_privacies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ex_follows`
--

DROP TABLE IF EXISTS `ex_follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ex_follows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exchange_id` bigint(20) unsigned NOT NULL,
  `event` text NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'U' COMMENT 'U=User, S=System, I=Insert',
  `privacy_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `ex_follow_privacy_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ex_follows_exchange_id_index` (`exchange_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ex_statuses`
--

DROP TABLE IF EXISTS `ex_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ex_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ex_statuses_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exchange_reasons`
--

DROP TABLE IF EXISTS `exchange_reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `exchange_reasons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `exchange_reasons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exchanges`
--

DROP TABLE IF EXISTS `exchanges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `exchanges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('OS','CONSUMER') NOT NULL DEFAULT 'CONSUMER' COMMENT 'OS=Via Posto, CONSUMER=Consumidor Direto',
  `uuid` varchar(100) DEFAULT NULL,
  `os_id` bigint(20) unsigned DEFAULT NULL COMMENT 'OS de origem (para Via Posto)',
  `os_troca_id` bigint(20) unsigned DEFAULT NULL COMMENT 'OS de troca gerada',
  `clifor_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Consumidor',
  `partner_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Posto autorizado',
  `modelo_original` varchar(100) DEFAULT NULL,
  `modelo_original_id` bigint(20) unsigned DEFAULT NULL,
  `numero_serie` varchar(100) DEFAULT NULL,
  `revenda` varchar(200) DEFAULT NULL,
  `numero_nf_compra` varchar(50) DEFAULT NULL,
  `data_nf_compra` date DEFAULT NULL,
  `valor_nf` decimal(10,2) DEFAULT NULL,
  `defeito_reclamado` text DEFAULT NULL,
  `condicoes_produto` text DEFAULT NULL,
  `tipo_troca` enum('P','D') DEFAULT NULL COMMENT 'P=Produto, D=Devolução',
  `valor_negociado` decimal(10,2) DEFAULT NULL,
  `modelo_troca_id` bigint(20) unsigned DEFAULT NULL,
  `modelo_troca` varchar(100) DEFAULT NULL,
  `motivo_troca` text DEFAULT NULL,
  `exchange_reason_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('DRAFT','PENDING','APPROVED','AWAITING_INVOICE','REJECTED','COMPLETED','CANCELLED') NOT NULL DEFAULT 'DRAFT',
  `status_id` bigint(20) unsigned DEFAULT NULL,
  `evidencia_nf` varchar(255) DEFAULT NULL,
  `evidencia_etiqueta` varchar(255) DEFAULT NULL,
  `evidencia_defeito` varchar(255) DEFAULT NULL,
  `requested_by` bigint(20) unsigned DEFAULT NULL,
  `requested_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `order_item_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exchanges_os_id_index` (`os_id`),
  KEY `exchanges_clifor_id_index` (`clifor_id`),
  KEY `exchanges_partner_id_index` (`partner_id`),
  KEY `exchanges_status_index` (`status`),
  KEY `exchanges_uuid_index` (`uuid`),
  KEY `exchanges_exchange_reason_id_foreign` (`exchange_reason_id`),
  CONSTRAINT `exchanges_exchange_reason_id_foreign` FOREIGN KEY (`exchange_reason_id`) REFERENCES `exchange_reasons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=25007 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fiscal_invoice_follows`
--

DROP TABLE IF EXISTS `fiscal_invoice_follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fiscal_invoice_follows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fiscal_invoice_id` int(11) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `os_id` bigint(20) DEFAULT NULL,
  `binded` tinyint(4) DEFAULT 0,
  `event` varchar(250) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `colors` varchar(250) DEFAULT NULL,
  `icon` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `part_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fiscal_invoice_items`
--

DROP TABLE IF EXISTS `fiscal_invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fiscal_invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fiscal_invoice_id` bigint(20) unsigned NOT NULL,
  `codigo_produto` varchar(255) DEFAULT NULL,
  `numero_nf` varchar(255) DEFAULT NULL,
  `chave_nfe` varchar(255) DEFAULT NULL,
  `ean` varchar(50) DEFAULT NULL,
  `produto` varchar(255) DEFAULT NULL,
  `ncm` varchar(15) DEFAULT NULL,
  `benef` varchar(255) DEFAULT NULL,
  `cfop` varchar(255) DEFAULT NULL,
  `cest` varchar(15) DEFAULT NULL,
  `un_com` varchar(15) DEFAULT NULL,
  `qtd_com` varchar(15) DEFAULT NULL,
  `valor_unitario` varchar(255) DEFAULT NULL,
  `valor_total` varchar(255) DEFAULT NULL,
  `ean_tributado` varchar(15) DEFAULT NULL,
  `un_tributado` varchar(15) DEFAULT NULL,
  `qtd_tributado` varchar(15) DEFAULT NULL,
  `valor_unitario_tributado` varchar(255) DEFAULT NULL,
  `icms_origem` varchar(10) DEFAULT NULL,
  `icms_cst` varchar(15) DEFAULT NULL,
  `icms_modo_base_calculo` varchar(15) DEFAULT NULL,
  `icms_base_calculo` varchar(255) DEFAULT NULL,
  `icms` varchar(255) DEFAULT NULL,
  `icms_valor` varchar(255) DEFAULT NULL,
  `ipi_enquadramento` varchar(15) DEFAULT NULL,
  `ipi_cst` varchar(15) DEFAULT NULL,
  `pis_cst` varchar(15) DEFAULT NULL,
  `pis_base_calculo` varchar(255) DEFAULT NULL,
  `pis_p` varchar(15) DEFAULT NULL,
  `pis_valor` varchar(255) DEFAULT NULL,
  `cofins_cst` varchar(15) DEFAULT NULL,
  `cofins_base_calculo` varchar(255) DEFAULT NULL,
  `cofins_p` varchar(15) DEFAULT NULL,
  `cofins_valor` varchar(255) DEFAULT NULL,
  `notas_referenciadas` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fiscal_invoice_items` (`fiscal_invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fiscal_invoices`
--

DROP TABLE IF EXISTS `fiscal_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fiscal_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero_nf` varchar(255) NOT NULL,
  `brand` tinyint(4) DEFAULT NULL,
  `tipo_nf` varchar(10) DEFAULT NULL,
  `finalidade` varchar(1) DEFAULT NULL,
  `indicador_presenca` varchar(1) DEFAULT NULL,
  `natureza_operacao` varchar(255) NOT NULL,
  `notas_referenciadas` varchar(255) DEFAULT NULL,
  `data_emissao` datetime NOT NULL,
  `local_destino` varchar(1) DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `serie` varchar(255) NOT NULL,
  `consumidor_final` varchar(1) DEFAULT NULL,
  `data_saida_entrada` datetime NOT NULL,
  `codigo_emitente` int(11) DEFAULT NULL,
  `cnpj_emitente` varchar(255) NOT NULL,
  `nome_emitente` varchar(255) NOT NULL,
  `fantasia_emitente` varchar(255) DEFAULT NULL,
  `endereco_emitente` varchar(255) NOT NULL,
  `numero_endereco_emitente` varchar(255) NOT NULL,
  `bairro_emitente` varchar(255) NOT NULL,
  `codigo_municipio_emitente` varchar(255) DEFAULT NULL,
  `municipio_emitente` varchar(255) DEFAULT NULL,
  `uf_emitente` varchar(255) NOT NULL,
  `cep_emitente` varchar(255) NOT NULL,
  `codigo_pais_emitente` varchar(255) DEFAULT NULL,
  `pais_emitente` varchar(255) NOT NULL,
  `fone_emitente` varchar(255) DEFAULT NULL,
  `ie_emitente` varchar(255) DEFAULT NULL,
  `regime_tributario` varchar(1) DEFAULT NULL,
  `codigo_destinatario` int(11) DEFAULT NULL,
  `cnpj_destinatario` varchar(255) NOT NULL,
  `nome_destinatario` varchar(255) NOT NULL,
  `endereco_destinatario` varchar(255) NOT NULL,
  `numero_endereco_destinatario` varchar(255) DEFAULT NULL,
  `bairro_destinatario` varchar(255) NOT NULL,
  `codigo_municipio_destinatario` varchar(255) NOT NULL,
  `municipio_destinatario` varchar(255) NOT NULL,
  `uf_destinatario` varchar(255) NOT NULL,
  `cep_destinatario` varchar(255) DEFAULT NULL,
  `codigo_pais_destinatario` varchar(255) NOT NULL,
  `pais_destinatario` varchar(255) DEFAULT NULL,
  `fone_destinatario` varchar(255) DEFAULT NULL,
  `ie_destinatario` varchar(255) DEFAULT NULL,
  `indicador_ie_destinatario` varchar(255) DEFAULT NULL,
  `icms_bc` varchar(255) DEFAULT NULL,
  `icms` varchar(255) DEFAULT NULL,
  `icms_deson` varchar(255) DEFAULT NULL,
  `icms_fcp` varchar(255) DEFAULT NULL,
  `icms_bcst` varchar(255) DEFAULT NULL,
  `icms_st` varchar(255) DEFAULT NULL,
  `icms_fcpst` varchar(255) DEFAULT NULL,
  `icms_fcpstret` varchar(255) DEFAULT NULL,
  `icms_prod` varchar(255) DEFAULT NULL,
  `icms_frete` varchar(255) DEFAULT NULL,
  `icms_seg` varchar(255) DEFAULT NULL,
  `icms_desc` varchar(255) DEFAULT NULL,
  `ii` varchar(255) DEFAULT NULL,
  `ipi` varchar(255) DEFAULT NULL,
  `ipi_devol` varchar(255) DEFAULT NULL,
  `pis` varchar(255) DEFAULT NULL,
  `cofins` varchar(255) DEFAULT NULL,
  `outro` varchar(255) DEFAULT NULL,
  `nf` varchar(255) DEFAULT NULL,
  `total_tributado` varchar(255) DEFAULT NULL,
  `informacoes_adicionais` text DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `chave_nfe` varchar(255) DEFAULT NULL,
  `data_recebimento` varchar(255) DEFAULT NULL,
  `enter_stock` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nfs_cep_destinatario_index` (`cep_destinatario`),
  KEY `nfs_municipio_emitente_index` (`municipio_emitente`),
  KEY `nfs_numero_nf_index` (`numero_nf`),
  KEY `nfs_cep_emitente_index` (`cep_emitente`),
  KEY `nfs_cnpj_emitente_index` (`cnpj_emitente`),
  KEY `nfs_cnpj_destinatario_index` (`cnpj_destinatario`),
  KEY `nfs_nome_emitente_index` (`nome_emitente`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fornecedores`
--

DROP TABLE IF EXISTS `fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fornecedores` (
  `Cod_Fornecedor` int(11) NOT NULL AUTO_INCREMENT,
  `Desc_Fornecedor` varchar(50) DEFAULT NULL,
  `Obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Cod_Fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `itemlocs`
--

DROP TABLE IF EXISTS `itemlocs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `itemlocs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) DEFAULT NULL,
  `part_code` varchar(255) DEFAULT NULL,
  `available` int(11) DEFAULT NULL,
  `reserved` int(11) DEFAULT 0,
  `pending` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `part_code_idx` (`id`,`part_code`),
  KEY `idx_cod_deposito` (`id`,`warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1222 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `itempend`
--

DROP TABLE IF EXISTS `itempend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `itempend` (
  `Num_Pend` int(11) NOT NULL AUTO_INCREMENT,
  `Num_Trans_Pendencia` int(11) DEFAULT NULL,
  `Num_Trans_Atendimento` int(11) DEFAULT NULL,
  `Num_Trans_Alteracao` int(11) DEFAULT NULL,
  `Atendida` varchar(1) DEFAULT NULL,
  `Obs_Pendencia` varchar(255) DEFAULT NULL,
  `Cod_Pend` int(11) NOT NULL,
  `os` int(11) DEFAULT NULL,
  `item` varchar(50) NOT NULL,
  `qtd` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Num_Pend`),
  KEY `ItemTransCod_Trans` (`Atendida`),
  KEY `ItemTransCod_Deposito` (`Num_Trans_Atendimento`),
  KEY `ItemTransCod_Fornecedor` (`Num_Pend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `itemres`
--

DROP TABLE IF EXISTS `itemres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `itemres` (
  `Num_Res` int(11) NOT NULL AUTO_INCREMENT,
  `Num_Trans_Reserva` int(11) DEFAULT NULL,
  `Num_Trans_Atendimento` int(11) DEFAULT NULL,
  `Num_Trans_Alteracao` int(11) DEFAULT NULL,
  `Atendida` varchar(1) DEFAULT NULL,
  `Obs_Reserva` varchar(255) DEFAULT NULL,
  `Cod_Res` int(11) NOT NULL,
  `os` int(11) DEFAULT NULL,
  `item` varchar(50) DEFAULT NULL,
  `qtd` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Num_Res`),
  KEY `ItemTransCod_Trans` (`Atendida`),
  KEY `ItemTransCod_Deposito` (`Num_Trans_Atendimento`),
  KEY `ItemTransCod_Fornecedor` (`Num_Res`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `itemtrans`
--

DROP TABLE IF EXISTS `itemtrans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `itemtrans` (
  `Num_Trans` int(11) NOT NULL AUTO_INCREMENT,
  `Cod_Fornecedor` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `Cod_Trans` int(11) DEFAULT NULL,
  `Qtd` int(11) DEFAULT NULL,
  `Cod_Usuario` smallint(6) DEFAULT NULL,
  `Cod_TipoDoc` int(11) DEFAULT NULL,
  `Num_Documento` varchar(50) DEFAULT NULL,
  `Data_documento` datetime DEFAULT NULL,
  `Data_trans` datetime DEFAULT NULL,
  `Obs_Trans` varchar(50) DEFAULT NULL,
  `Valor_Unitario` varchar(250) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `part_code` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`Num_Trans`),
  KEY `ItemTransCod_Fornecedor` (`Cod_Fornecedor`),
  KEY `ItemTransCod_Trans` (`Cod_Trans`),
  KEY `ItemTransCod_Deposito` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=2516 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nfs`
--

DROP TABLE IF EXISTS `nfs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nfs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero_nf` varchar(255) NOT NULL,
  `natureza_operacao` varchar(255) NOT NULL,
  `data_emissao` datetime NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `serie` varchar(255) NOT NULL,
  `data_saida_entrada` datetime NOT NULL,
  `cnpj_emitente` varchar(255) NOT NULL,
  `nome_emitente` varchar(255) NOT NULL,
  `fantasia_emitente` varchar(255) DEFAULT NULL,
  `endereco_emitente` varchar(255) NOT NULL,
  `numero_endereco_emitente` varchar(255) NOT NULL,
  `bairro_emitente` varchar(255) NOT NULL,
  `codigo_municipio_emitente` varchar(255) DEFAULT NULL,
  `municipio_emitente` varchar(255) DEFAULT NULL,
  `uf_emitente` varchar(255) NOT NULL,
  `cep_emitente` varchar(255) NOT NULL,
  `codigo_pais_emitente` varchar(255) NOT NULL,
  `pais_emitente` varchar(255) NOT NULL,
  `fone_emitente` varchar(255) DEFAULT NULL,
  `ie_emitente` varchar(255) DEFAULT NULL,
  `cnpj_destinatario` varchar(255) NOT NULL,
  `nome_destinatario` varchar(255) NOT NULL,
  `endereco_destinatario` varchar(255) NOT NULL,
  `numero_endereco_destinatario` varchar(255) DEFAULT NULL,
  `bairro_destinatario` varchar(255) NOT NULL,
  `codigo_municipio_destinatario` varchar(255) NOT NULL,
  `municipio_destinatario` varchar(255) NOT NULL,
  `uf_destinatario` varchar(255) NOT NULL,
  `cep_destinatario` varchar(255) DEFAULT NULL,
  `codigo_pais_destinatario` varchar(255) NOT NULL,
  `pais_destinatario` varchar(255) NOT NULL,
  `fone_destinatario` varchar(255) DEFAULT NULL,
  `ie_destinatario` varchar(255) DEFAULT NULL,
  `indicador_ie_destinatario` varchar(255) DEFAULT NULL,
  `icms_bc` varchar(255) DEFAULT NULL,
  `icms` varchar(255) DEFAULT NULL,
  `icms_deson` varchar(255) DEFAULT NULL,
  `icms_fcp` varchar(255) DEFAULT NULL,
  `icms_bcst` varchar(255) DEFAULT NULL,
  `icms_st` varchar(255) DEFAULT NULL,
  `icms_fcpst` varchar(255) DEFAULT NULL,
  `icms_fcpstret` varchar(255) DEFAULT NULL,
  `icms_prod` varchar(255) DEFAULT NULL,
  `icms_frete` varchar(255) DEFAULT NULL,
  `icms_seg` varchar(255) DEFAULT NULL,
  `icms_desc` varchar(255) DEFAULT NULL,
  `ii` varchar(255) DEFAULT NULL,
  `ipi` varchar(255) DEFAULT NULL,
  `ipi_devol` varchar(255) DEFAULT NULL,
  `pis` varchar(255) DEFAULT NULL,
  `cofins` varchar(255) DEFAULT NULL,
  `outro` varchar(255) DEFAULT NULL,
  `nf` varchar(255) DEFAULT NULL,
  `total_tributado` varchar(255) DEFAULT NULL,
  `informacoes_adicionais` text DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `chave_nfe` varchar(255) DEFAULT NULL,
  `data_recebimento` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nfs_numero_nf_index` (`numero_nf`),
  KEY `nfs_cep_emitente_index` (`cep_emitente`),
  KEY `nfs_cnpj_emitente_index` (`cnpj_emitente`),
  KEY `nfs_cnpj_destinatario_index` (`cnpj_destinatario`),
  KEY `nfs_nome_emitente_index` (`nome_emitente`),
  KEY `nfs_cep_destinatario_index` (`cep_destinatario`),
  KEY `nfs_municipio_emitente_index` (`municipio_emitente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_follows`
--

DROP TABLE IF EXISTS `order_follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_follows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `data_acomp` datetime NOT NULL DEFAULT current_timestamp(),
  `event` mediumtext DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `type` char(2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=691 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `number` int(11) DEFAULT NULL COMMENT 'Order Number',
  `os_id` bigint(20) DEFAULT NULL,
  `exchange_id` bigint(20) unsigned DEFAULT NULL,
  `os_type` varchar(5) DEFAULT NULL COMMENT 'OS Type',
  `order_type` varchar(5) DEFAULT NULL COMMENT 'Order Type',
  `status_id` bigint(20) DEFAULT NULL COMMENT 'Order Status',
  `bill_status` varchar(1) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL COMMENT 'Order Date',
  `line_qtd` int(11) DEFAULT NULL COMMENT 'Line Qtd',
  `total` double DEFAULT NULL COMMENT 'Total Value',
  `uid` text DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `pedido_bling` int(11) DEFAULT NULL,
  `data_pedido_bling` datetime DEFAULT NULL,
  `order_gateway` int(11) DEFAULT NULL,
  `order_date_gateway` datetime DEFAULT NULL,
  `order_gateway_input` int(11) DEFAULT NULL,
  `order_date_gateway_input` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `verified` datetime DEFAULT NULL,
  `separated` datetime DEFAULT NULL,
  `collected` datetime DEFAULT NULL,
  `delivered` datetime DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `partner_id` int(11) DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `gateway_status` varchar(250) DEFAULT NULL,
  `estimated_delivery` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_number` (`number`),
  KEY `idx_number_os` (`os_id`),
  KEY `orders_exchange_id_index` (`exchange_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5095 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orders_items`
--

DROP TABLE IF EXISTS `orders_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Order Number',
  `order_id` bigint(20) DEFAULT NULL,
  `item` varchar(50) NOT NULL COMMENT 'Order Item',
  `substitute_item` varchar(50) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL COMMENT 'Item Qtd',
  `ICMS` double DEFAULT NULL COMMENT 'ICMS Value',
  `IPI` double DEFAULT NULL COMMENT 'IPI Value',
  `ST` double DEFAULT NULL COMMENT 'ST Value',
  `total` double DEFAULT NULL COMMENT 'Total Value',
  `nf_number` varchar(11) DEFAULT NULL COMMENT 'NF Number',
  `nf_date` date DEFAULT NULL COMMENT 'NF Date',
  `item_reservation` tinyint(4) NOT NULL DEFAULT 0,
  `uid` varchar(255) DEFAULT NULL,
  `bill_status_item` varchar(1) DEFAULT NULL,
  `id_osdet` int(11) DEFAULT NULL,
  `block_order` tinyint(4) NOT NULL DEFAULT 1,
  `nfe_ok` tinyint(4) NOT NULL DEFAULT 0,
  `manufactor_nf_number` varchar(50) DEFAULT NULL,
  `manufactor_nf_date` datetime DEFAULT NULL,
  `obs_item` text DEFAULT NULL,
  `verified` varchar(250) DEFAULT NULL,
  `separated` varchar(250) DEFAULT NULL,
  `collected` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nf_binding` bigint(20) DEFAULT 0,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_os_number` (`item`),
  KEY `idx_order_number` (`order_id`),
  KEY `id_osdet` (`id_osdet`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orders_nfs`
--

DROP TABLE IF EXISTS `orders_nfs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders_nfs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `os_number` varchar(20) DEFAULT NULL,
  `order_number` varchar(50) DEFAULT NULL,
  `nfe_number` varchar(20) DEFAULT NULL,
  `nfe_date` datetime DEFAULT NULL,
  `nfe_file` varchar(50) DEFAULT NULL,
  `cfop` varchar(255) DEFAULT NULL,
  `nfe_product_code` varchar(50) DEFAULT NULL,
  `nfe_product` varchar(255) DEFAULT NULL,
  `nfe_value` varchar(50) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `nfe_key` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orders_statuses`
--

DROP TABLE IF EXISTS `orders_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders_statuses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(17) DEFAULT NULL,
  `description` varchar(55) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` varchar(0) DEFAULT NULL,
  `updated_at` varchar(0) DEFAULT NULL,
  `alias` char(2) NOT NULL DEFAULT ' C',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os`
--

DROP TABLE IF EXISTS `os`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `data_abertura` datetime DEFAULT NULL,
  `usuario_abertura` varchar(50) DEFAULT NULL,
  `protocolo` varchar(50) DEFAULT NULL,
  `numero_cr` varchar(11) DEFAULT NULL,
  `numero_os_cliente` varchar(100) DEFAULT NULL,
  `data_os_cliente` datetime DEFAULT NULL,
  `numero_pre_os_fabricante` varchar(20) DEFAULT NULL,
  `data_pre_os_fabricante` datetime DEFAULT NULL,
  `codigo_status_acompanhamento` int(11) DEFAULT NULL,
  `codigo_status_acompanhamento_alterado_em` datetime DEFAULT NULL,
  `codigo_tipo_os` int(11) DEFAULT NULL,
  `codigo_tipo_atendimento` int(11) DEFAULT NULL,
  `codigo_local_atendimento` int(11) DEFAULT NULL,
  `codigo_status` int(11) DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL,
  `marca` int(11) DEFAULT NULL,
  `fabricante` varchar(15) DEFAULT NULL,
  `modelo` tinytext DEFAULT NULL,
  `modelo_recebido` varchar(30) DEFAULT NULL,
  `numero_serie` varchar(255) DEFAULT NULL,
  `revenda` varchar(255) DEFAULT NULL,
  `numero_nf_compra` varchar(255) DEFAULT NULL,
  `data_nf_compra` date DEFAULT NULL,
  `valor` varchar(255) NOT NULL DEFAULT '0',
  `defeito_reclamado` mediumtext DEFAULT NULL,
  `defeito_constatado` mediumtext DEFAULT NULL,
  `acessorios` varchar(255) DEFAULT NULL,
  `condicoes` varchar(255) DEFAULT NULL,
  `obs` text DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT NULL,
  `usuario_avaliacao` varchar(30) DEFAULT NULL,
  `data_reparo` datetime DEFAULT NULL,
  `usuario_reparo` varchar(30) DEFAULT NULL,
  `data_fechamento` datetime DEFAULT NULL,
  `usuario_fechamento` varchar(30) DEFAULT NULL,
  `eticket` varchar(15) DEFAULT NULL,
  `data_eticket` datetime DEFAULT NULL,
  `numero_nf_entrada` varchar(15) DEFAULT NULL,
  `data_nf_entrada` datetime DEFAULT NULL,
  `objeto_entrada` varchar(15) DEFAULT NULL,
  `numero_nf_saida` varchar(15) DEFAULT NULL,
  `data_nf_saida` datetime DEFAULT NULL,
  `objeto_saida` varchar(15) DEFAULT NULL,
  `data_objeto_saida` datetime DEFAULT NULL,
  `numero_posto` varchar(15) DEFAULT NULL,
  `clifor_id` bigint(20) DEFAULT NULL,
  `codigo_posto` varchar(15) DEFAULT NULL,
  `prioridade` int(11) DEFAULT NULL,
  `volta_etapa` int(11) DEFAULT NULL,
  `nome_posto_resumido` varchar(255) DEFAULT NULL,
  `tecnico_designado` varchar(30) DEFAULT NULL,
  `uuid` varchar(50) DEFAULT NULL,
  `descricao_reparo` varchar(255) DEFAULT NULL COMMENT 'Descrição do Reparo',
  `condicao_defeito` char(1) DEFAULT NULL,
  `sintoma` varchar(100) DEFAULT NULL,
  `reingresso` char(1) NOT NULL DEFAULT 'N',
  `custo_extra` float NOT NULL DEFAULT 0,
  `uso_pecas` varchar(1) NOT NULL DEFAULT 'N',
  `tipo_reparo` bigint(20) DEFAULT NULL,
  `sem_defeito` char(1) NOT NULL DEFAULT 'N',
  `ct_filha` char(1) NOT NULL DEFAULT 'N' COMMENT 'os ct filha',
  `ct_os_posto` varchar(50) DEFAULT NULL COMMENT 'os posto ct',
  `ct_gcs_posto` varchar(15) DEFAULT NULL COMMENT 'gcs posto',
  `enviado_produto_ct` char(1) NOT NULL DEFAULT 'N',
  `categoria` char(1) NOT NULL DEFAULT 'N',
  `escalado_segundo_nivel` char(1) NOT NULL DEFAULT 'N',
  `tipo_troca` char(1) DEFAULT NULL,
  `motivo_escalado` mediumtext DEFAULT NULL,
  `usuario_escala` varchar(100) DEFAULT NULL,
  `data_escala` datetime DEFAULT NULL,
  `trocado` char(1) NOT NULL DEFAULT 'N',
  `motivo_troca` mediumtext DEFAULT NULL,
  `modelo_trocado` varchar(100) DEFAULT NULL,
  `modelo_troca_id` bigint(20) unsigned DEFAULT NULL,
  `negociado` char(1) NOT NULL DEFAULT 'N',
  `valor_negociado` double NOT NULL DEFAULT 0,
  `data_analise_troca` datetime DEFAULT NULL,
  `data_aprovacao_troca` timestamp NULL DEFAULT NULL,
  `usuario_analise_troca` varchar(100) DEFAULT NULL,
  `resultado_analise_troca` varchar(1) DEFAULT NULL,
  `money_back` char(1) NOT NULL DEFAULT 'N',
  `valor_money_back` double NOT NULL DEFAULT 0,
  `descricao_analise_troca` mediumtext DEFAULT NULL,
  `cod_cli` int(11) NOT NULL DEFAULT 0,
  `uid_file` varchar(50) DEFAULT NULL,
  `osAlterada` int(11) NOT NULL DEFAULT 0,
  `data_ultima_alteracao` datetime DEFAULT NULL,
  `cpf_alterado` int(11) NOT NULL DEFAULT 0,
  `nf_alterado` int(11) NOT NULL DEFAULT 0,
  `acessorios_alterado` int(11) NOT NULL DEFAULT 0,
  `nome_alterado` int(11) NOT NULL DEFAULT 0,
  `retirado_por` varchar(50) DEFAULT NULL,
  `ultimo_status` int(11) DEFAULT NULL,
  `os_aceita` int(11) DEFAULT 0,
  `data_aceite` datetime DEFAULT NULL,
  `user_aceite` varchar(50) DEFAULT NULL,
  `os_rejeitada` int(11) NOT NULL DEFAULT 0,
  `data_rejeicao` datetime DEFAULT NULL,
  `user_rejeicao` varchar(50) DEFAULT NULL,
  `is_display` int(11) DEFAULT NULL,
  `data_real_reparo` date DEFAULT NULL,
  `motivo_rejeicao` mediumtext DEFAULT NULL,
  `nf_td` varchar(10) DEFAULT NULL,
  `data_prevista` date DEFAULT NULL,
  `status_envio_pedido` varchar(100) DEFAULT NULL,
  `data_ok_fabricante` datetime DEFAULT NULL,
  `user_ok_fabricante` varchar(50) DEFAULT NULL,
  `defeito_apresentado` varchar(255) DEFAULT NULL,
  `data_recebido` datetime DEFAULT NULL,
  `serie_recebida` varchar(255) DEFAULT NULL,
  `data_autorizado` datetime DEFAULT NULL,
  `valor_devolucao` float DEFAULT NULL,
  `data_envio` datetime DEFAULT NULL,
  `nf_envio` varchar(100) DEFAULT NULL,
  `data_nf_envio` datetime DEFAULT NULL,
  `file_nf_envio` varchar(255) DEFAULT NULL,
  `isento_nf_envio` int(11) DEFAULT 0,
  `tipo_fechamento` tinyint(4) NOT NULL DEFAULT 0,
  `rastreio` varchar(15) DEFAULT NULL,
  `obs_processo` mediumtext DEFAULT NULL,
  `data_entrega_troca` datetime DEFAULT NULL,
  `is_display_bkp` int(11) DEFAULT NULL,
  `status_envio` varchar(255) DEFAULT NULL,
  `data_nf_td` datetime DEFAULT NULL,
  `data_entrega_nf_td` datetime DEFAULT NULL,
  `mao_obra` varchar(20) DEFAULT NULL,
  `distancia` int(11) DEFAULT NULL,
  `valor_km` varchar(20) DEFAULT NULL,
  `qtd_visitas` tinyint(4) DEFAULT NULL,
  `data_prevista_visita` datetime DEFAULT NULL,
  `close_adm` tinyint(4) DEFAULT NULL,
  `nf_coleta` varchar(100) DEFAULT NULL,
  `data_nf_coleta` datetime DEFAULT NULL,
  `numero_coleta` varchar(100) DEFAULT NULL,
  `data_coleta` datetime DEFAULT NULL,
  `tipo_atendimento` varchar(255) DEFAULT NULL,
  `status_fabricante` varchar(250) DEFAULT NULL,
  `data_fechamento_fabricante` datetime DEFAULT NULL,
  `critical` tinyint(4) DEFAULT 0,
  `partner_id` bigint(20) DEFAULT NULL,
  `service_invitation` tinyint(4) NOT NULL DEFAULT 1,
  `response_invite_date` datetime DEFAULT NULL,
  `response_invite_user` varchar(50) DEFAULT NULL,
  `invite_rejected_reason` text DEFAULT NULL,
  `os_pai` varchar(250) DEFAULT NULL,
  `os_origem_troca` bigint(20) unsigned DEFAULT NULL,
  `os_troca` bigint(20) unsigned DEFAULT NULL,
  `tipo_produto` int(11) DEFAULT 0,
  `purchase_nf_file` varchar(255) DEFAULT '''''',
  `obs_clifor` text DEFAULT NULL,
  `os_id_tpv` varchar(100) DEFAULT NULL,
  `numero_os_reingresso` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `protocolo` (`protocolo`,`numero_cr`),
  KEY `idx_codigo_status` (`codigo_status`),
  KEY `idx_numero_os_cliente` (`numero_os_cliente`),
  KEY `idx_marca` (`marca`),
  KEY `idx_modelo` (`modelo`(85)),
  KEY `idx_codigo_clifor` (`clifor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7121 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_accept_statuses`
--

DROP TABLE IF EXISTS `os_accept_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_accept_statuses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `text_color` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `bg_color` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_adm_support`
--

DROP TABLE IF EXISTS `os_adm_support`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_adm_support` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `os_id` bigint(20) DEFAULT NULL,
  `event` mediumtext DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` char(2) DEFAULT 'U' COMMENT 'U = User, S = Sistema',
  `origin` char(1) DEFAULT NULL COMMENT 'P = Posto, M = Manufactor',
  `status` char(1) DEFAULT 'O' COMMENT 'O = Open, C = Closed',
  `with_ball` char(1) DEFAULT 'M' COMMENT 'P = Posto, M = Manufactor',
  `user_id` bigint(20) DEFAULT 0,
  `privacy_id` bigint(20) DEFAULT 1 COMMENT '1 - public, 2 -EFL, 3 - EFL + partners, 4 - EFL + clients',
  `os_follow_privacy_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_adm_support_files`
--

DROP TABLE IF EXISTS `os_adm_support_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_adm_support_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `os_adm_support_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_changes`
--

DROP TABLE IF EXISTS `os_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_changes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `field` varchar(100) NOT NULL,
  `previous_value` varchar(255) DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_closing_audits`
--

DROP TABLE IF EXISTS `os_closing_audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_closing_audits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `os_closing_consolidated_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `field` varchar(255) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `user_id` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `os_closing_audits_os_closing_consolidated_id_foreign` (`os_closing_consolidated_id`),
  CONSTRAINT `os_closing_audits_os_closing_consolidated_id_foreign` FOREIGN KEY (`os_closing_consolidated_id`) REFERENCES `os_closing_consolidateds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_closing_consolidateds`
--

DROP TABLE IF EXISTS `os_closing_consolidateds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_closing_consolidateds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reference_month` varchar(7) NOT NULL COMMENT 'Formato: 2025-09',
  `partner_code` varchar(20) NOT NULL COMMENT 'Exemplo: SP002-GBR',
  `cnpj` varchar(20) DEFAULT NULL COMMENT 'Exemplo: 04.539.690/0001-02',
  `razao_social` varchar(255) DEFAULT NULL COMMENT 'Nome da empresa',
  `total_value` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor total das OS',
  `upload_nf` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Pendente=0, Sim=1',
  `numero_nf` varchar(50) DEFAULT NULL COMMENT 'Número da Nota Fiscal',
  `nf_files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Array com caminhos dos arquivos da NF' CHECK (json_valid(`nf_files`)),
  `status_financeiro` enum('pendente','aprovado','recusado','rejeitada','pago') DEFAULT 'pendente',
  `rejection_reason` text DEFAULT NULL COMMENT 'Motivo da rejeição',
  `nf_change_reason` text DEFAULT NULL COMMENT 'Motivo da alteração/troca da NF',
  `nf_changed_at` timestamp NULL DEFAULT NULL COMMENT 'Data/hora da última alteração da NF',
  `nf_previous_number` varchar(50) DEFAULT NULL COMMENT 'Número anterior da NF',
  `payment_forecast` datetime DEFAULT NULL COMMENT 'Data prevista de pagamento',
  `manifestacao` text DEFAULT NULL COMMENT 'Observações/manifestações',
  `status_manifestacao` enum('pendente','aprovado','recusado') NOT NULL DEFAULT 'pendente' COMMENT 'Status da manifestação',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `os_closing_consolidateds_reference_month_partner_code_unique` (`reference_month`,`partner_code`),
  KEY `os_closing_consolidateds_reference_month_index` (`reference_month`),
  KEY `os_closing_consolidateds_partner_code_index` (`partner_code`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Consolidação de fechamento de OS por posto e mês';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_closings`
--

DROP TABLE IF EXISTS `os_closings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_closings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `os_id` bigint(20) NOT NULL DEFAULT 0,
  `partner_code` varchar(20) NOT NULL DEFAULT '0',
  `closing_date` datetime DEFAULT NULL,
  `protocol` varchar(50) DEFAULT NULL,
  `reference_month` varchar(20) DEFAULT NULL,
  `total_value` float DEFAULT 0,
  `consolidated` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_forecast` datetime DEFAULT NULL COMMENT 'Previsão de pagamento',
  PRIMARY KEY (`id`),
  UNIQUE KEY `os_closings_os_id_unique` (`os_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_costs`
--

DROP TABLE IF EXISTS `os_costs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_costs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `os_id` bigint(20) NOT NULL,
  `cost_id` bigint(20) NOT NULL,
  `approved` tinyint(4) DEFAULT NULL,
  `approver_user_id` bigint(20) DEFAULT NULL,
  `cost_obs` text DEFAULT NULL,
  `cost_validation_obs` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit` int(11) DEFAULT 1,
  `variable_value` float DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_document_downloads`
--

DROP TABLE IF EXISTS `os_document_downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_document_downloads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_file` varchar(255) NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `downloaded_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `os_document_downloads_os_id_downloaded_at_index` (`os_id`,`downloaded_at`),
  KEY `os_document_downloads_user_id_downloaded_at_index` (`user_id`,`downloaded_at`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_evidence_file_types`
--

DROP TABLE IF EXISTS `os_evidence_file_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_evidence_file_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `file_name` varchar(250) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mandatory` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_evidence_files`
--

DROP TABLE IF EXISTS `os_evidence_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_evidence_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `type_file` int(11) DEFAULT NULL,
  `numero_os_cliente` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `os_id` bigint(20) DEFAULT NULL,
  `observation` varchar(250) DEFAULT NULL,
  `uuid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=680 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_follow`
--

DROP TABLE IF EXISTS `os_follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_follow` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `os_number` bigint(20) DEFAULT NULL,
  `event` mediumtext DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` char(2) DEFAULT 'U' COMMENT 'U = User, S = Sistema',
  `user_id` bigint(20) DEFAULT 0,
  `privacy_id` bigint(20) DEFAULT 1 COMMENT '1 - public, 2 -Take&delivery, 3 - take&delivery + partners, 4 - Take&delivery + clients',
  `os_follow_privacy_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=430 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_follow_files`
--

DROP TABLE IF EXISTS `os_follow_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_follow_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `os_follow_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_follow_privacies`
--

DROP TABLE IF EXISTS `os_follow_privacies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_follow_privacies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `colors` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `default` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_follows`
--

DROP TABLE IF EXISTS `os_follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_follows` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `os_number` bigint(20) DEFAULT NULL,
  `event` mediumtext DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` char(2) DEFAULT 'U' COMMENT 'U = User, S = Sistema',
  `user_id` bigint(20) DEFAULT 0,
  `privacy_id` bigint(20) DEFAULT 1 COMMENT '1 - public, 2 -Take&delivery, 3 - take&delivery + partners, 4 - Take&delivery + clients',
  `os_follow_privacy_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1455 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_import`
--

DROP TABLE IF EXISTS `os_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_import` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(255) DEFAULT NULL,
  `import_date` datetime DEFAULT NULL,
  `ocorrencias` text DEFAULT NULL,
  `os` varchar(100) NOT NULL,
  `data` varchar(255) DEFAULT NULL,
  `modelo` tinytext DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `data_compra` varchar(255) DEFAULT NULL,
  `revendedor` varchar(255) DEFAULT NULL,
  `nf` varchar(255) DEFAULT NULL,
  `valor` varchar(20) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `descricao_atendimento` text DEFAULT NULL,
  `cliente` varchar(255) DEFAULT NULL,
  `cpf` varchar(30) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `numero` varchar(100) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `contato` varchar(100) DEFAULT NULL,
  `ddd` varchar(10) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `defeito` varchar(255) DEFAULT NULL,
  `cep` varchar(15) DEFAULT NULL,
  `cep_verificado` varchar(1) DEFAULT 'N',
  `sintoma` text DEFAULT NULL,
  `posicao` varchar(20) DEFAULT NULL,
  `quantidade_1` varchar(11) DEFAULT NULL,
  `codigo_1` varchar(50) DEFAULT NULL,
  `quantidade_2` varchar(11) DEFAULT NULL,
  `codigo_2` varchar(50) DEFAULT NULL,
  `quantidade_3` varchar(11) DEFAULT NULL,
  `codigo_3` varchar(50) DEFAULT NULL,
  `quantidade_4` varchar(11) DEFAULT NULL,
  `codigo_4` varchar(50) DEFAULT NULL,
  `quantidade_5` varchar(11) DEFAULT NULL,
  `codigo_5` varchar(50) DEFAULT NULL,
  `ie` varchar(50) DEFAULT NULL,
  `e_mail` varchar(255) DEFAULT NULL,
  `line` varchar(11) DEFAULT NULL,
  `telefone_2` varchar(20) DEFAULT NULL,
  `telefone_3` varchar(20) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `nf_coleta` varchar(100) DEFAULT NULL,
  `data_nf_coleta` varchar(255) DEFAULT NULL,
  `numero_coleta` varchar(100) DEFAULT NULL,
  `data_coleta` varchar(255) DEFAULT NULL,
  `tipo_atendimento` varchar(100) DEFAULT NULL,
  `data_prevista_visita` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pre_os` varchar(255) DEFAULT NULL,
  `data_pre_os` varchar(255) DEFAULT NULL,
  `os_id_tpv` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_import_files`
--

DROP TABLE IF EXISTS `os_import_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_import_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'R',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_imports`
--

DROP TABLE IF EXISTS `os_imports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_imports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `os_import_files_id` bigint(20) unsigned NOT NULL,
  `ocorrencias` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `data` varchar(255) DEFAULT NULL,
  `modelo` varchar(255) DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `data_compra` varchar(255) DEFAULT NULL,
  `revendedor` varchar(255) DEFAULT NULL,
  `nf` varchar(255) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `descricao_atendimento` varchar(255) DEFAULT NULL,
  `cliente` varchar(255) DEFAULT NULL,
  `cpf` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `uf` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `contato` varchar(255) DEFAULT NULL,
  `ddd` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `defeito` varchar(255) DEFAULT NULL,
  `cep` varchar(255) DEFAULT NULL,
  `cep_verificado` varchar(255) DEFAULT NULL,
  `sintoma` varchar(255) DEFAULT NULL,
  `posicao` varchar(255) DEFAULT NULL,
  `quantidade_1` varchar(255) DEFAULT NULL,
  `codigo_1` varchar(255) DEFAULT NULL,
  `quantidade_2` varchar(255) DEFAULT NULL,
  `codigo_2` varchar(255) DEFAULT NULL,
  `quantidade_3` varchar(255) DEFAULT NULL,
  `codigo_3` varchar(255) DEFAULT NULL,
  `quantidade_4` varchar(255) DEFAULT NULL,
  `codigo_4` varchar(255) DEFAULT NULL,
  `quantidade_5` varchar(255) DEFAULT NULL,
  `codigo_5` varchar(255) DEFAULT NULL,
  `ie` varchar(255) DEFAULT NULL,
  `e_mail` varchar(255) DEFAULT NULL,
  `line` varchar(255) DEFAULT NULL,
  `telefone_2` varchar(255) DEFAULT NULL,
  `telefone_3` varchar(255) DEFAULT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  `nf_coleta` varchar(255) DEFAULT NULL,
  `data_nf_coleta` varchar(255) DEFAULT NULL,
  `numero_coleta` varchar(255) DEFAULT NULL,
  `data_coleta` varchar(255) DEFAULT NULL,
  `tipo_atendimento` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `data_pre_os` varchar(250) DEFAULT NULL,
  `data_prevista_visita` varchar(250) DEFAULT NULL,
  `pre_os` varchar(250) DEFAULT NULL,
  `endereco_original` varchar(250) DEFAULT NULL,
  `reingresso` varchar(250) DEFAULT NULL,
  `posto` varchar(50) DEFAULT NULL,
  `undefined` int(11) DEFAULT NULL,
  `tipo_produto` varchar(250) DEFAULT NULL,
  `tipo_reparo` varchar(250) DEFAULT NULL,
  `os_id_tpv` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `os_imports_os_import_files_id_foreign` (`os_import_files_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_invite_statuses`
--

DROP TABLE IF EXISTS `os_invite_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_invite_statuses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `colors` varchar(250) DEFAULT NULL,
  `icon` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_invites`
--

DROP TABLE IF EXISTS `os_invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_invites` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) DEFAULT NULL,
  `os_id` bigint(20) DEFAULT NULL,
  `os_invite_status_id` bigint(20) DEFAULT NULL COMMENT 'Null = stateless, A = Accepetd, R = Rejected, C = Cancelled, E = Expired',
  `rejection_reason` text DEFAULT NULL,
  `obs` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_parts`
--

DROP TABLE IF EXISTS `os_parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_os` varchar(10) DEFAULT NULL,
  `secao` varchar(10) DEFAULT NULL,
  `defeito` varchar(50) DEFAULT NULL,
  `solucao` varchar(50) DEFAULT NULL,
  `posicao` varchar(10) DEFAULT NULL,
  `part_code` varchar(20) DEFAULT NULL,
  `qtd` smallint(6) DEFAULT NULL,
  `gera_pedido` char(1) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `sintoma_codificado` varchar(255) DEFAULT NULL,
  `obs_solucao` varchar(255) DEFAULT NULL,
  `data_item` datetime DEFAULT NULL COMMENT 'Data do Item',
  `data_nf` datetime DEFAULT NULL COMMENT 'Data da NF',
  `data_pedido` datetime DEFAULT NULL COMMENT 'Data do Pedido',
  `numero_pedido` varchar(15) DEFAULT NULL COMMENT 'Numero do Pedido',
  `numero_nf` varchar(15) DEFAULT NULL COMMENT 'Numero da NF',
  `tipo` varchar(15) DEFAULT NULL,
  `valor` double DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL COMMENT 'unique id',
  `eticket` varchar(20) DEFAULT NULL,
  `data_envio` date DEFAULT NULL,
  `objeto_envio` varchar(20) DEFAULT NULL,
  `data_devolucao` date DEFAULT NULL,
  `objeto_devolucao` varchar(20) DEFAULT NULL,
  `obs_envio` varchar(512) DEFAULT NULL,
  `data_rec_cr` date DEFAULT NULL,
  `item_substituto` varchar(50) DEFAULT NULL,
  `recebido` char(1) DEFAULT '0',
  `data_recebido` datetime DEFAULT NULL,
  `aplicado` tinyint(4) NOT NULL DEFAULT 0,
  `data_aplicado` datetime DEFAULT NULL,
  `tipo_envio` tinyint(4) NOT NULL DEFAULT 0,
  `item_aprovado` tinyint(4) NOT NULL DEFAULT 0,
  `item_descricao` varchar(255) DEFAULT NULL,
  `item_posto` varchar(20) DEFAULT NULL,
  `motivo_pedido` mediumtext DEFAULT NULL,
  `motivo_recusa` mediumtext DEFAULT NULL,
  `motivo_aprovacao` mediumtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `tipo_solicitacao` enum('normal','especial') DEFAULT 'normal' COMMENT 'Tipo da solicitação: normal=primeira solicitação, especial=solicitações adicionais do mesmo item na OS',
  PRIMARY KEY (`id`),
  KEY `numero_os` (`numero_os`),
  KEY `codigo_item` (`part_code`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_schedule_statuses`
--

DROP TABLE IF EXISTS `os_schedule_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_schedule_statuses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `colors` varchar(250) DEFAULT NULL,
  `icon` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_schedules`
--

DROP TABLE IF EXISTS `os_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_schedules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `os_invite_id` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `status` bigint(20) DEFAULT 1 COMMENT 'S =  Scheduled, O = Optional, C = canceled ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_status_pedido_import`
--

DROP TABLE IF EXISTS `os_status_pedido_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_status_pedido_import` (
  `os_fabricante` int(11) DEFAULT NULL,
  `data_prevista` datetime DEFAULT NULL,
  `nf_td` varchar(20) DEFAULT NULL,
  `data_nf_td` datetime DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `data_entrega_nf_td` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_technical_support`
--

DROP TABLE IF EXISTS `os_technical_support`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_technical_support` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `os_id` bigint(20) DEFAULT NULL,
  `event` mediumtext DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` char(2) DEFAULT 'U' COMMENT 'U = User, S = Sistema',
  `origin` char(1) DEFAULT NULL COMMENT 'P = Posto, M = Manufactor',
  `status` char(1) DEFAULT 'O' COMMENT 'O = Open, C = Closed',
  `with_ball` char(1) DEFAULT 'M' COMMENT 'P = Posto, M = Manufactor',
  `user_id` bigint(20) DEFAULT 0,
  `privacy_id` bigint(20) DEFAULT 1 COMMENT '1 - public, 2 -EFL, 3 - EFL + partners, 4 - EFL + clients',
  `os_follow_privacy_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_technical_support_files`
--

DROP TABLE IF EXISTS `os_technical_support_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_technical_support_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `os_technical_support_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `os_types`
--

DROP TABLE IF EXISTS `os_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` tinyint(4) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `colors` varchar(100) DEFAULT NULL,
  `order_view` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `part_reserves`
--

DROP TABLE IF EXISTS `part_reserves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `part_reserves` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 - Reserved, 2- fulfilled - 3 - cancelled',
  `comment` text DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_item_id` bigint(20) DEFAULT NULL,
  `order_id` bigint(20) NOT NULL,
  `part_code` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `part_transactions`
--

DROP TABLE IF EXISTS `part_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `part_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `document_type_id` bigint(20) unsigned NOT NULL COMMENT 'Type document - NF,OS',
  `transaction_type_id` bigint(20) unsigned NOT NULL COMMENT 'Input, output, transfer',
  `part_code` varchar(50) NOT NULL,
  `qtd` tinyint(4) NOT NULL DEFAULT 0,
  `price` varchar(250) NOT NULL DEFAULT '0',
  `cost_price` varchar(250) NOT NULL DEFAULT '0',
  `document_number` varchar(30) DEFAULT NULL,
  `obs` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `part_transactions_transaction_type_id_foreign` (`transaction_type_id`),
  KEY `part_transactions_user_id_foreign` (`user_id`),
  KEY `part_transactions_warehouse_id_foreign` (`warehouse_id`),
  KEY `part_transactions_part_code_unique` (`part_code`),
  KEY `part_transactions_document_type_id_foreign` (`document_type_id`),
  CONSTRAINT `part_transactions_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`),
  CONSTRAINT `part_transactions_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`id`),
  CONSTRAINT `part_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `part_transactions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(15) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `ie` varchar(20) DEFAULT NULL,
  `ie_not_verified` tinyint(4) DEFAULT NULL,
  `isento` varchar(1) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `fantasia` varchar(255) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `numero_endereco` varchar(10) DEFAULT NULL,
  `endereco_compl` varchar(100) DEFAULT NULL,
  `Bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `zip` varchar(15) DEFAULT NULL,
  `zip5` varchar(5) DEFAULT NULL,
  `zip3` varchar(3) DEFAULT NULL,
  `ddd` varchar(5) DEFAULT NULL,
  `Telefone` varchar(255) DEFAULT NULL,
  `telefone2` varchar(30) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `fax2` varchar(15) DEFAULT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `email2` varchar(50) DEFAULT NULL,
  `classe` varchar(10) DEFAULT NULL,
  `lvl` varchar(1) DEFAULT NULL,
  `tipo` varchar(15) DEFAULT NULL,
  `tensao` varchar(10) DEFAULT NULL,
  `sap` varchar(10) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `marcas_autorizadas` varchar(10) DEFAULT NULL,
  `brands_line` varchar(50) DEFAULT NULL,
  `tipo_empresa` int(11) DEFAULT NULL,
  `regime_tributario` int(11) DEFAULT NULL,
  `tipo_pessoa` tinyint(4) DEFAULT NULL,
  `obs` text DEFAULT NULL,
  `numero_banco` varchar(20) DEFAULT NULL,
  `nome_banco` varchar(255) DEFAULT NULL,
  `agencia` varchar(50) DEFAULT NULL,
  `conta_corrente` varchar(50) DEFAULT NULL,
  `chave_pix` varchar(250) DEFAULT NULL,
  `tipo_chave_pix` smallint(6) NOT NULL DEFAULT 0,
  `tipo_conta` smallint(6) DEFAULT 0,
  `obs_dados_bancarios` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `syncronized_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_cnpj` (`cnpj`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parts`
--

DROP TABLE IF EXISTS `parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_bling` bigint(20) DEFAULT NULL,
  `part_code` varchar(60) NOT NULL,
  `description` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `ncm` varchar(10) DEFAULT NULL,
  `origin` tinyint(4) DEFAULT NULL,
  `price` varchar(50) DEFAULT '0',
  `observations` varchar(255) DEFAULT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'A',
  `current_stock` int(11) DEFAULT 0,
  `cost_price` varchar(50) DEFAULT '0',
  `manufacture_code` varchar(50) DEFAULT NULL,
  `provider_name` varchar(255) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `max_stock` int(11) DEFAULT NULL,
  `min_stock` int(11) DEFAULT NULL,
  `net_weight` varchar(255) DEFAULT NULL,
  `gross_weight` varchar(255) DEFAULT NULL,
  `ean` varchar(50) DEFAULT NULL,
  `ean_packaging` varchar(50) DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `depth` varchar(50) DEFAULT NULL,
  `expiration_date` varchar(20) DEFAULT NULL,
  `provider_description` varchar(255) DEFAULT NULL,
  `complementary_description` varchar(255) DEFAULT NULL,
  `items_per_box` tinyint(4) DEFAULT NULL,
  `production_type` varchar(1) DEFAULT NULL,
  `item_type` varchar(1) DEFAULT NULL,
  `product_group_type` varchar(50) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `cest` varchar(10) DEFAULT NULL,
  `volumes` tinyint(4) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `cross_docking` varchar(255) DEFAULT NULL,
  `external_link` varchar(255) DEFAULT NULL,
  `guaranty` tinyint(4) DEFAULT NULL,
  `condiction` varchar(50) DEFAULT NULL,
  `free_shipping` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `unit_measure` varchar(15) DEFAULT NULL,
  `category` varchar(15) DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  `manufacture_id` varchar(255) DEFAULT NULL,
  `type_shipment_id` varchar(2) DEFAULT NULL,
  `creator_user_id` varchar(255) DEFAULT NULL,
  `syncronized` datetime DEFAULT NULL,
  `library` longtext DEFAULT NULL,
  `is_display` int(11) NOT NULL DEFAULT 0,
  `last_date_item` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parts_code_unique` (`part_code`),
  KEY `parts_description_index` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parts_reversa`
--

DROP TABLE IF EXISTS `parts_reversa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `parts_reversa` (
  `id_bling` bigint(20) DEFAULT NULL,
  `part_code` varchar(24) DEFAULT NULL,
  `description` varchar(36) DEFAULT NULL,
  `unit` varchar(5) DEFAULT NULL,
  `ncm` varchar(10) DEFAULT NULL,
  `origin` int(11) DEFAULT NULL,
  `price` decimal(12,8) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `cost_price` decimal(9,8) DEFAULT NULL,
  `net_weight` varchar(6) DEFAULT NULL,
  `gross_weight` varchar(6) DEFAULT NULL,
  `width` varchar(6) DEFAULT NULL,
  `height` varchar(6) DEFAULT NULL,
  `depth` varchar(6) DEFAULT NULL,
  `Marca` int(11) DEFAULT NULL,
  `cest` varchar(9) DEFAULT NULL,
  `unit_measure` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  KEY `permission_role_permission_id_foreign` (`permission_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_team`
--

DROP TABLE IF EXISTS `permission_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_team` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_team_team_id_foreign` (`team_id`),
  KEY `permission_team_permission_id_foreign` (`permission_id`),
  CONSTRAINT `permission_team_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_team_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_user`
--

DROP TABLE IF EXISTS `permission_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_user_permission_id_foreign` (`permission_id`),
  KEY `permission_user_user_id_foreign` (`user_id`),
  CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_models`
--

DROP TABLE IF EXISTS `product_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_of_materials_id` bigint(20) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `manufacture_model` varchar(250) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `release` datetime DEFAULT NULL,
  `end_of_life` datetime DEFAULT NULL,
  `warranty` int(11) DEFAULT NULL COMMENT 'Month',
  `promotional_warranty` int(11) DEFAULT NULL COMMENT 'Month',
  `ean` varchar(50) DEFAULT NULL,
  `manufacture_id` bigint(20) DEFAULT NULL,
  `facelift` varchar(50) DEFAULT NULL,
  `Observations` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_types`
--

DROP TABLE IF EXISTS `product_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `colors` varchar(250) DEFAULT NULL,
  `icon` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `repair_types`
--

DROP TABLE IF EXISTS `repair_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `repair_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` tinyint(4) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `colors` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role_team`
--

DROP TABLE IF EXISTS `role_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_team` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_team_role_id_foreign` (`role_id`),
  KEY `role_team_team_id_foreign` (`team_id`),
  CONSTRAINT `role_team_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_team_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service_locations`
--

DROP TABLE IF EXISTS `service_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_locations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` tinyint(4) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `colors` varchar(100) DEFAULT NULL,
  `order_view` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service_order_invites`
--

DROP TABLE IF EXISTS `service_order_invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_order_invites` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `service_provider_id` bigint(20) DEFAULT NULL,
  `service_order_id` bigint(20) DEFAULT NULL,
  `status` char(1) DEFAULT NULL COMMENT 'Null = stateless, A = Accepetd, R = Rejected, C = Cancelled',
  `rejection_reason` text DEFAULT NULL,
  `obs` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service_order_schedules`
--

DROP TABLE IF EXISTS `service_order_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_order_schedules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `service_order_invite_id` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `status` char(1) DEFAULT 'O' COMMENT 'S =  Scheduled, O = Optional, C = canceled ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service_statuses`
--

DROP TABLE IF EXISTS `service_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_statuses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `colors` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service_types`
--

DROP TABLE IF EXISTS `service_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` tinyint(4) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `colors` varchar(100) DEFAULT NULL,
  `order_view` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shipping_company`
--

DROP TABLE IF EXISTS `shipping_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_company` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `cnpj` varchar(250) DEFAULT NULL,
  `ie` varchar(250) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `address_number` varchar(250) DEFAULT NULL,
  `address_complement` varchar(250) DEFAULT NULL,
  `district` varchar(250) DEFAULT NULL,
  `city` varchar(250) DEFAULT NULL,
  `uf` varchar(250) DEFAULT NULL,
  `supplier` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `cep` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `status_acompanhamento`
--

DROP TABLE IF EXISTS `status_acompanhamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_acompanhamento` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` tinyint(4) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subs`
--

DROP TABLE IF EXISTS `subs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subs` (
  `Codigo` varchar(19) DEFAULT NULL,
  `Opcional01` varchar(39) DEFAULT NULL,
  `Opcional02` varchar(20) DEFAULT NULL,
  `Opcional03` varchar(42) DEFAULT NULL,
  `Opcional04` varchar(19) DEFAULT NULL,
  `Opcional05` varchar(18) DEFAULT NULL,
  `G` varchar(10) DEFAULT NULL,
  `H` varchar(10) DEFAULT NULL,
  `I` varchar(10) DEFAULT NULL,
  `J` varchar(10) DEFAULT NULL,
  `K` varchar(10) DEFAULT NULL,
  `L` varchar(10) DEFAULT NULL,
  `M` varchar(10) DEFAULT NULL,
  `N` varchar(10) DEFAULT NULL,
  `O` varchar(10) DEFAULT NULL,
  `P` varchar(10) DEFAULT NULL,
  `Q` varchar(10) DEFAULT NULL,
  `R` varchar(10) DEFAULT NULL,
  `S` varchar(10) DEFAULT NULL,
  `T` varchar(10) DEFAULT NULL,
  `U` varchar(10) DEFAULT NULL,
  `V` varchar(10) DEFAULT NULL,
  `W` varchar(10) DEFAULT NULL,
  `X` varchar(10) DEFAULT NULL,
  `Y` varchar(10) DEFAULT NULL,
  `Z` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `team_user`
--

DROP TABLE IF EXISTS `team_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_user_user_id_foreign` (`user_id`),
  KEY `team_user_team_id_foreign` (`team_id`),
  CONSTRAINT `team_user_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipodocumento`
--

DROP TABLE IF EXISTS `tipodocumento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipodocumento` (
  `Cod_TipoDoc` int(11) NOT NULL AUTO_INCREMENT,
  `Documento` varchar(50) DEFAULT NULL,
  `Tipo_Doc` varchar(1) DEFAULT NULL,
  `Tipo_trans` int(11) DEFAULT NULL,
  PRIMARY KEY (`Cod_TipoDoc`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipores`
--

DROP TABLE IF EXISTS `tipores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipores` (
  `Cod_Res` int(11) NOT NULL AUTO_INCREMENT,
  `Desc_Res` varchar(50) DEFAULT NULL,
  `Obs` varchar(255) NOT NULL,
  PRIMARY KEY (`Cod_Res`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipotrans`
--

DROP TABLE IF EXISTS `tipotrans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipotrans` (
  `Cod_Trans` int(11) NOT NULL AUTO_INCREMENT,
  `Desc_Trans` varchar(50) DEFAULT NULL,
  `Tipo_Operacao` smallint(6) DEFAULT NULL,
  `Obs` varchar(255) NOT NULL,
  PRIMARY KEY (`Cod_Trans`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tracking_statuses`
--

DROP TABLE IF EXISTS `tracking_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tracking_statuses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` tinyint(4) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `colors` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transaction_types`
--

DROP TABLE IF EXISTS `transaction_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_types` (
  `id` bigint(20) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_types_type_unique` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ufs`
--

DROP TABLE IF EXISTS `ufs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ufs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uf` char(2) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `borders` set('RS','SC','PR','SP','RJ','ES','MG','BA','SE','AL','PE','PB','RN','CE','PI','MA','TO','PA','AP','RR','AM','AC','RO','MT','MS','GO','DF') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-borders` (`borders`),
  KEY `idx_uf` (`uf`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `is_posto` tinyint(1) NOT NULL DEFAULT 1,
  `is_test_user` tinyint(4) NOT NULL DEFAULT 0,
  `user_assignments` varchar(250) NOT NULL DEFAULT '''''',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `Ativo` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `idx_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6214 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouses` (
  `id` bigint(20) unsigned NOT NULL,
  `warehouse_id` bigint(20) unsigned NOT NULL,
  `warehouse_bling_id` bigint(20) DEFAULT NULL,
  `warehouse` varchar(255) NOT NULL,
  `warehouse_description` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `type` varchar(2) NOT NULL,
  `brand` tinyint(4) NOT NULL,
  `brand_default` tinyint(4) DEFAULT NULL,
  `id_fornecedor` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warehouses_itemloc_id_index` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-12-06 18:16:08
