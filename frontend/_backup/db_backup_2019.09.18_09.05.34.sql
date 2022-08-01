-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------
-- START BACKUP
-- -------------------------------------------
-- -------------------------------------------
-- TABLE `auth_assignment`
-- -------------------------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `auth_item`
-- -------------------------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `auth_item_child`
-- -------------------------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `auth_rule`
-- -------------------------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `migration`
-- -------------------------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_bank`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_bank`;
CREATE TABLE IF NOT EXISTS `tbl_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(255) NOT NULL,
  `bank_acc_name` varchar(255) NOT NULL,
  `bank_acc_number` varchar(255) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_brand`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_brand`;
CREATE TABLE IF NOT EXISTS `tbl_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL,
  `brand_type` enum('original','ppi') NOT NULL COMMENT 'original - merk dari sononya | ppi - merk buatan ppi sendiri',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_category`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_category`;
CREATE TABLE IF NOT EXISTS `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_category_tbl_brand` (`brand_id`),
  CONSTRAINT `FK_tbl_category_tbl_brand` FOREIGN KEY (`brand_id`) REFERENCES `tbl_brand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_comission_pay`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_comission_pay`;
CREATE TABLE IF NOT EXISTS `tbl_comission_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salesman_id` int(11) NOT NULL,
  `comission_pay_date` date NOT NULL,
  `comission_pay_exchange_rate` decimal(16,2) NOT NULL,
  `comission_pay_periode` enum('maret','juni','september','desember') NOT NULL,
  `comission_pay_value` decimal(16,2) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_comission_pay_tbl_employee` (`salesman_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_comission_pay_detail`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_comission_pay_detail`;
CREATE TABLE IF NOT EXISTS `tbl_comission_pay_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comission_pay_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `comission_pay_detail_percent` float NOT NULL,
  `comission_pay_detail_amount` decimal(16,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_comission_pay_detail_tbl_comission_pay` (`comission_pay_id`),
  KEY `FK_tbl_comission_pay_detail_tbl_invoice` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_comission_pay_rule`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_comission_pay_rule`;
CREATE TABLE IF NOT EXISTS `tbl_comission_pay_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comission_pay_rule_start_day` int(11) NOT NULL,
  `comission_pay_rule_end_day` int(11) NOT NULL,
  `comission_pay_rule_value` float NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_comission_type`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_comission_type`;
CREATE TABLE IF NOT EXISTS `tbl_comission_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comission_type_name` varchar(255) NOT NULL,
  `comission_type_desc` tinytext NOT NULL,
  `comission_type_value` float NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_customer`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_customer`;
CREATE TABLE IF NOT EXISTS `tbl_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_store_code` varchar(255) NOT NULL,
  `customer_store_name` varchar(255) NOT NULL,
  `customer_store_postal_code` varchar(255) NOT NULL,
  `customer_store_address` varchar(255) NOT NULL,
  `customer_zone` varchar(255) DEFAULT NULL,
  `customer_province` varchar(255) DEFAULT NULL,
  `customer_city` varchar(255) DEFAULT NULL,
  `customer_type` enum('agen','bigreseller','smallreseller','generalreseller','general','industry') DEFAULT 'general',
  `customer_has_tempo` tinyint(1) DEFAULT '0',
  `customer_tempo_limit` int(11) DEFAULT NULL COMMENT 'number of days',
  `customer_credit_limit` decimal(16,2) DEFAULT NULL,
  `customer_identity_card_number` varchar(255) DEFAULT NULL,
  `customer_identity_card_image` varchar(255) DEFAULT NULL,
  `customer_npwp` varchar(255) DEFAULT NULL,
  `customer_npwp_image` varchar(255) DEFAULT NULL,
  `customer_bank_name` varchar(255) DEFAULT NULL,
  `customer_bank_acc_number` varchar(255) DEFAULT NULL,
  `customer_bank_acc_name` varchar(255) DEFAULT NULL,
  `customer_has_ppn` tinyint(1) DEFAULT '0',
  `customer_status` enum('active','inactive') DEFAULT 'active',
  `customer_owner_name` varchar(255) DEFAULT NULL,
  `customer_birthday` date DEFAULT NULL,
  `customer_phone1` varchar(255) DEFAULT NULL,
  `customer_phone2` varchar(255) DEFAULT NULL,
  `customer_note` text,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_delivery`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_delivery`;
CREATE TABLE IF NOT EXISTS `tbl_delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `delivery_code` int(11) NOT NULL,
  `delivery_date` int(11) NOT NULL,
  `delivery_receiver_name` int(11) NOT NULL,
  `delivery_address` int(11) NOT NULL,
  `delivery_receiver_phone` int(11) NOT NULL,
  `delivery_total_price` decimal(16,2) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_delivery_tbl_sales_invoice` (`invoice_id`),
  CONSTRAINT `FK_tbl_delivery_tbl_sales_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `tbl_sales_invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_delivery_item`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_delivery_item`;
CREATE TABLE IF NOT EXISTS `tbl_delivery_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_id` int(11) NOT NULL,
  `invoice_item_id` int(11) NOT NULL,
  `delivery_item_qty` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_delivery_item_tbl_delivery` (`delivery_id`),
  KEY `FK_tbl_delivery_item_tbl_sales_invoice_item` (`invoice_item_id`),
  CONSTRAINT `FK_tbl_delivery_item_tbl_delivery` FOREIGN KEY (`delivery_id`) REFERENCES `tbl_delivery` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_delivery_item_tbl_sales_invoice_item` FOREIGN KEY (`invoice_item_id`) REFERENCES `tbl_sales_invoice_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_employee`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_employee`;
CREATE TABLE IF NOT EXISTS `tbl_employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_address` text,
  `employee_phone` varchar(20) DEFAULT NULL,
  `employee_mobile_phone` varchar(20) DEFAULT NULL,
  `employee_note` text,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_profile_tbl_user` (`user_id`),
  CONSTRAINT `FK_tbl_employee_tbl_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_factory`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_factory`;
CREATE TABLE IF NOT EXISTS `tbl_factory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factory_name` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_indent`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_indent`;
CREATE TABLE IF NOT EXISTS `tbl_indent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indent_date` date NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_indent_tbl_customer` (`customer_id`),
  CONSTRAINT `FK_tbl_indent_tbl_customer` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_indent_item`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_indent_item`;
CREATE TABLE IF NOT EXISTS `tbl_indent_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indent_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` float NOT NULL,
  `indent_item_is_complete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_indent_item_tbl_indent` (`indent_id`),
  KEY `FK_tbl_indent_item_tbl_product` (`product_id`),
  CONSTRAINT `FK_tbl_indent_item_tbl_indent` FOREIGN KEY (`indent_id`) REFERENCES `tbl_indent` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_indent_item_tbl_product` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_packaging`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_packaging`;
CREATE TABLE IF NOT EXISTS `tbl_packaging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `packaging_name` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_product`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_product`;
CREATE TABLE IF NOT EXISTS `tbl_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_material_code` varchar(255) DEFAULT NULL,
  `product_material_name` varchar(255) DEFAULT NULL,
  `product_code` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `factory_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_is_new` tinyint(1) NOT NULL DEFAULT '1',
  `original_brand_id` int(11) NOT NULL,
  `searah_id` int(11) NOT NULL,
  `product_gender` enum('m','f','neutral') NOT NULL,
  `product_cost_price` decimal(16,2) NOT NULL,
  `product_sell_price` decimal(16,2) NOT NULL,
  `product_web_image` text,
  `product_status` enum('active','inactive') NOT NULL,
  `product_type` enum('sample','new','bestseller','regular','slow','discontinue') NOT NULL DEFAULT 'sample',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_product_tbl_factory` (`factory_id`),
  KEY `FK_tbl_product_tbl_brand` (`brand_id`),
  KEY `FK_tbl_product_tbl_category` (`category_id`),
  KEY `FK_tbl_product_tbl_brand_original` (`original_brand_id`),
  KEY `FK_tbl_product_tbl_searah` (`searah_id`),
  CONSTRAINT `FK_tbl_product_tbl_brand` FOREIGN KEY (`brand_id`) REFERENCES `tbl_brand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_product_tbl_brand_original` FOREIGN KEY (`original_brand_id`) REFERENCES `tbl_brand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_product_tbl_category` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_product_tbl_factory` FOREIGN KEY (`factory_id`) REFERENCES `tbl_factory` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_product_tbl_searah` FOREIGN KEY (`searah_id`) REFERENCES `tbl_searah` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_purchase_order`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_purchase_order`;
CREATE TABLE IF NOT EXISTS `tbl_purchase_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `purchase_order_code` varchar(255) NOT NULL,
  `purchase_order_date` date NOT NULL,
  `purchase_order_status` enum('new','partial','close') NOT NULL DEFAULT 'new',
  `purchase_order_subtotal` decimal(16,2) NOT NULL,
  `purchase_order_disc_percent` float DEFAULT NULL,
  `purchase_order_disc_amount` decimal(16,2) DEFAULT NULL,
  `purchase_order_grand_total` decimal(16,2) NOT NULL,
  `purchase_order_notes` text,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_purchase_order_tbl_supplier` (`supplier_id`),
  CONSTRAINT `FK_tbl_purchase_order_tbl_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `tbl_supplier` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_purchase_order_item`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_purchase_order_item`;
CREATE TABLE IF NOT EXISTS `tbl_purchase_order_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_order_item_qty` float NOT NULL,
  `purchase_order_item_price` decimal(16,2) NOT NULL,
  `purchase_order_item_disc_percent` float DEFAULT NULL,
  `purchase_order_item_disc_amount` decimal(16,2) DEFAULT NULL,
  `purchase_order_item_row_total` decimal(16,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_purchase_order_item_tbl_purchase_order` (`purchase_order_id`),
  CONSTRAINT `FK_tbl_purchase_order_item_tbl_purchase_order` FOREIGN KEY (`purchase_order_id`) REFERENCES `tbl_purchase_order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_purchase_payment`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_purchase_payment`;
CREATE TABLE IF NOT EXISTS `tbl_purchase_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_payment_code` varchar(255) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `purchase_payment_date` date NOT NULL,
  `purchase_payment_total_amount` decimal(16,2) NOT NULL,
  `purchase_payment_method` enum('cash','banktransfer') DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_purchase_payment_tbl_supplier` (`supplier_id`),
  KEY `FK_tbl_purchase_payment_tbl_purchase_order` (`purchase_order_id`),
  CONSTRAINT `FK_tbl_purchase_payment_tbl_purchase_order` FOREIGN KEY (`purchase_order_id`) REFERENCES `tbl_purchase_order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_purchase_payment_tbl_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `tbl_supplier` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_purchase_payment_detail`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_purchase_payment_detail`;
CREATE TABLE IF NOT EXISTS `tbl_purchase_payment_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_payment_id` int(11) DEFAULT NULL,
  `purchase_payment_detail_amount` decimal(16,2) NOT NULL,
  `purchase_payment_detail_method` enum('cash','debitbca','debitmandiri','debitbri','debitbni','banktransfer','creditcardbca','creditcardmandiri','creditcardbri','creditcardbni') NOT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `purchase_payment_detail_creditcard_number` varchar(255) DEFAULT NULL,
  `purchase_payment_detail_debitcard_number` varchar(255) DEFAULT NULL,
  `purchase_payment_detail_bank_acc_number` varchar(255) DEFAULT NULL,
  `purchase_payment_detail_bank_acc_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_purchase_payment_detail_tbl_purchase_payment` (`purchase_payment_id`),
  KEY `FK_tbl_purchase_payment_detail_tbl_bank` (`bank_id`),
  CONSTRAINT `FK_tbl_purchase_payment_detail_tbl_bank` FOREIGN KEY (`bank_id`) REFERENCES `tbl_bank` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_purchase_payment_detail_tbl_purchase_payment` FOREIGN KEY (`purchase_payment_id`) REFERENCES `tbl_purchase_payment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_sales_invoice`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sales_invoice`;
CREATE TABLE IF NOT EXISTS `tbl_sales_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `salesman_id` int(11) NOT NULL,
  `comission_type_id` int(11) DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `invoice_code` varchar(255) NOT NULL,
  `invoice_subtotal` decimal(16,2) NOT NULL,
  `invoice_disc_amount` decimal(16,2) DEFAULT NULL,
  `invoice_disc_percent` float DEFAULT NULL,
  `invoice_tax_amount` decimal(16,2) DEFAULT NULL,
  `invoice_tax_percent` float DEFAULT NULL,
  `invoice_grand_total` decimal(16,2) NOT NULL,
  `invoice_outstanding_amount` decimal(16,2) NOT NULL,
  `invoice_status` enum('new','sent','close') NOT NULL DEFAULT 'new',
  `invoice_payment_status` enum('new','partial','paid') NOT NULL DEFAULT 'new',
  `invoice_exchange_rate` decimal(16,2) NOT NULL DEFAULT '1.00',
  `invoice_comission_value` decimal(16,2) DEFAULT NULL,
  `invoice_comission_pay_date` date DEFAULT NULL,
  `invoice_receiver` varchar(255) DEFAULT NULL,
  `invoice_destination_address` text,
  `invoice_postal_code` varchar(255) DEFAULT NULL,
  `invoice_destination_city` varchar(255) DEFAULT NULL,
  `invoice_destination_province` varchar(255) DEFAULT NULL,
  `invoice_comission_pay_percent` float DEFAULT NULL,
  `invoice_comission_pay_amount` decimal(16,2) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_sales_invoice_tbl_customer` (`customer_id`),
  KEY `FK_tbl_sales_invoice_tbl_comission_type` (`comission_type_id`),
  KEY `FK_tbl_sales_invoice_tbl_employee` (`salesman_id`),
  CONSTRAINT `FK_tbl_sales_invoice_tbl_comission_type` FOREIGN KEY (`comission_type_id`) REFERENCES `tbl_comission_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_sales_invoice_tbl_customer` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_sales_invoice_tbl_employee` FOREIGN KEY (`salesman_id`) REFERENCES `tbl_employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_sales_invoice_item`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sales_invoice_item`;
CREATE TABLE IF NOT EXISTS `tbl_sales_invoice_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `packaging_id` int(11) NOT NULL,
  `invoice_item_qty` float NOT NULL,
  `invoice_item_disc_amount` decimal(16,2) DEFAULT NULL,
  `invoice_item_disc_percent` float DEFAULT NULL,
  `invoice_item_price` decimal(16,2) NOT NULL,
  `invoice_item_currency` enum('rupiah','dolar') DEFAULT NULL,
  `invoice_item_row_total` decimal(16,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_sales_payment`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sales_payment`;
CREATE TABLE IF NOT EXISTS `tbl_sales_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_code` varchar(255) NOT NULL,
  `payment_date` date NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `payment_exchange_rate` decimal(16,2) NOT NULL DEFAULT '1.00',
  `payment_total_amount` decimal(16,2) NOT NULL,
  `payment_notes` text,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_sales_payment_tbl_sales_invoice` (`invoice_id`),
  KEY `FK_tbl_sales_payment_tbl_customer` (`customer_id`),
  CONSTRAINT `FK_tbl_sales_payment_tbl_customer` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_sales_payment_tbl_sales_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `tbl_sales_invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_sales_payment_detail`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sales_payment_detail`;
CREATE TABLE IF NOT EXISTS `tbl_sales_payment_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) NOT NULL,
  `payment_detail_amount` decimal(16,2) NOT NULL,
  `payment_detail_method` enum('cash','debitbca','debitmandiri','debitbri','debitbni','banktransfer','creditcardbca','creditcardmandiri','creditcardbri','creditcardbni') NOT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `payment_detail_creditcard_number` varchar(255) DEFAULT NULL,
  `payment_detail_debitcard_number` varchar(255) DEFAULT NULL,
  `payment_detail_bank_acc_number` varchar(255) DEFAULT NULL,
  `payment_detail_bank_acc_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_sales_payment_detail_tbl_sales_payment` (`payment_id`),
  KEY `FK_tbl_sales_payment_detail_tbl_bank` (`bank_id`),
  CONSTRAINT `FK_tbl_sales_payment_detail_tbl_bank` FOREIGN KEY (`bank_id`) REFERENCES `tbl_bank` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tbl_sales_payment_detail_tbl_sales_payment` FOREIGN KEY (`payment_id`) REFERENCES `tbl_sales_payment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_searah`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_searah`;
CREATE TABLE IF NOT EXISTS `tbl_searah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `searah_name` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_setting`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_setting`;
CREATE TABLE IF NOT EXISTS `tbl_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_category_id` int(11) NOT NULL,
  `setting_label` varchar(255) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` text NOT NULL,
  `setting_desc` varchar(255) DEFAULT NULL,
  `setting_input_type` enum('text','textarea','dropdown','file') NOT NULL,
  `setting_input_size` enum('mini','small','medium','large','xlarge','xxlarge') NOT NULL,
  `setting_dropdown_options` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_setting_tbl_setting_category` (`setting_category_id`),
  CONSTRAINT `FK_tbl_setting_tbl_setting_category` FOREIGN KEY (`setting_category_id`) REFERENCES `tbl_setting_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_setting_category`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_setting_category`;
CREATE TABLE IF NOT EXISTS `tbl_setting_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `tbl_supplier`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_supplier`;
CREATE TABLE IF NOT EXISTS `tbl_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_code` varchar(255) DEFAULT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_address` text,
  `supplier_phone1` varchar(255) DEFAULT NULL,
  `supplier_phone2` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------------------------------------
-- TABLE `user`
-- -------------------------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE DATA auth_assignment
-- -------------------------------------------
INSERT INTO `auth_assignment` (`item_name`,`user_id`,`created_at`) VALUES
('Super Administrator','1','1557467938');



-- -------------------------------------------
-- TABLE DATA auth_item
-- -------------------------------------------
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES
('Super Administrator','1','MASTER ADMIN','','','','1557910055');



-- -------------------------------------------
-- TABLE DATA migration
-- -------------------------------------------
INSERT INTO `migration` (`version`,`apply_time`) VALUES
('m000000_000000_base','1563866593');
INSERT INTO `migration` (`version`,`apply_time`) VALUES
('m130524_201442_init','1563866595');
INSERT INTO `migration` (`version`,`apply_time`) VALUES
('m190124_110200_add_verification_token_column_to_user_table','1563866595');
INSERT INTO `migration` (`version`,`apply_time`) VALUES
('m190805_085748_drop_foreign_key_FK_category_to_brand','1564995561');
INSERT INTO `migration` (`version`,`apply_time`) VALUES
('m190805_090047_drop_foreign_key_FK_product_to_brand','1564995721');
INSERT INTO `migration` (`version`,`apply_time`) VALUES
('m190805_090308_drop_foreign_key_FK_product_to_original_brand','1564995857');



-- -------------------------------------------
-- TABLE DATA tbl_brand
-- -------------------------------------------
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('247','Senses','ppi','1','','2019-08-07 15:38:08','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('248','Adidas','original','1','','2019-08-07 15:38:08','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('249','Aramis','original','1','','2019-08-07 15:38:08','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('250','Azzaro','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('251','Body Shop','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('252','Brut Parfums','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('253','Burberry','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('254','Bvlgari','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('255','Cacharel','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('256','Calvin Klein','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('257','Carolina Herrera','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('258','Christian Dior','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('259','D&G','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('260','Davidoff','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('261','Dunhill','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('262','Elisabeth Arden','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('263','Ermenegildo Zegna','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('264','Estee Lauder','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('265','Ferrari','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('266','Giorgio Armani','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('267','Givenchy','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('268','Gucci','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('269','Guy Laroche','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('270','Hermes','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('271','Hugo Boss','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('272','Issey Miyake','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('273','Jaguar','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('274','Justin Bieber','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('275','Katty Perry','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('276','Taylor Swift','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('277','Kenneth Cole','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('278','Kenzo','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('279','Lacoste','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('280','Lancome','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('281','Le Labo','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('282','Montblanc','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('283','Moschino','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('284','Playboy','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('285','Salvatore Ferragamo','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('286','Etienne Aigner','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('287','GCF','ppi','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('288','Anna Sui','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('289','Bond no. 9','original','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('290','Cartier','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('291','Chacarel','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('292','Chanel','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('293','Creed','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('294','David Beckham','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('295','DKNY','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('296','Dolce & Gabbana','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('297','Dupont','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('298','Ellie Saab','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('299','Escada','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('300','Guerlain','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('301','J Lo','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('302','Jummy Choo','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('303','Lolita Lempicka','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('304','Louis Vuitton','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('305','Mercedez Bens','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('306','Paco Rabanne','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('307','Parfums de Marly','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('308','Miu Miu','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('309','Prada','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('310','Ralph Lauren','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('311','Sarah Jessica Parker','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('312','Stella McCartney','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('313','Tom Ford','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('314','Versace','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('315','Victoria Secret','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('316','YSL','original','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('317','Zara','original','1','','2019-08-07 15:38:11','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('318','Chloe','original','1','','2019-08-07 15:38:11','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('319','Diesel','original','1','','2019-08-07 15:38:11','','0');
INSERT INTO `tbl_brand` (`id`,`brand_name`,`brand_type`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('320','Jo Malone','original','1','','2019-08-07 15:38:11','','0');



-- -------------------------------------------
-- TABLE DATA tbl_category
-- -------------------------------------------
INSERT INTO `tbl_category` (`id`,`category_name`,`brand_id`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('41','Red','247','2019-08-07 15:38:08','','1','','0');
INSERT INTO `tbl_category` (`id`,`category_name`,`brand_id`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('42','Gold','247','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_category` (`id`,`category_name`,`brand_id`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('43','Black','247','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_category` (`id`,`category_name`,`brand_id`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('44','Project Black','247','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_category` (`id`,`category_name`,`brand_id`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('45','Premium','287','2019-08-07 15:38:09','','1','','0');



-- -------------------------------------------
-- TABLE DATA tbl_comission_pay_rule
-- -------------------------------------------
INSERT INTO `tbl_comission_pay_rule` (`id`,`comission_pay_rule_start_day`,`comission_pay_rule_end_day`,`comission_pay_rule_value`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('1','1','2','5','','','','','0');
INSERT INTO `tbl_comission_pay_rule` (`id`,`comission_pay_rule_start_day`,`comission_pay_rule_end_day`,`comission_pay_rule_value`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('2','2','3','4','','','','','1');



-- -------------------------------------------
-- TABLE DATA tbl_comission_type
-- -------------------------------------------
INSERT INTO `tbl_comission_type` (`id`,`comission_type_name`,`comission_type_desc`,`comission_type_value`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1','angkut','asasasasas','10','','2019-09-03 08:59:10','','1','0');
INSERT INTO `tbl_comission_type` (`id`,`comission_type_name`,`comission_type_desc`,`comission_type_value`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('2','sales','online','30','','2019-09-03 08:59:20','','1','0');
INSERT INTO `tbl_comission_type` (`id`,`comission_type_name`,`comission_type_desc`,`comission_type_value`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('3','sdsd','dsd','1000','','','','','1');
INSERT INTO `tbl_comission_type` (`id`,`comission_type_name`,`comission_type_desc`,`comission_type_value`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('4','sdsd','dsd','232','','','','','1');



-- -------------------------------------------
-- TABLE DATA tbl_customer
-- -------------------------------------------
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('4','aksjas','askjksa','','','asas','sas','sasa','','1','2','2.00','wqwwq','7973963825d43f4b90b1dd.jpg','qwq','10729172245d43f4b90b5c5.jpg','wqwqw','wqwq','wqw','1','','qwq','2019-08-02','2323','3232','','2019-08-02 15:30:49','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('7','BB','asas','','','sasa','sas','sas','','1','2','2.00','wew','11678794615d43fa5d7a7b1.jpg','wewe','16336587855d43fa5d7ab99.png','assa','sasa','sa','1','','sasa','2019-08-02','sasa','sas','','2019-08-02 15:54:53','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('8','DD','asas','','','sas','sas','sas','general','1','2','2.00','343434','17122034515d43fbc9635f9.png','asas','17905048215d43fbc9639e2.jpg','sasa','sas','sa','1','','sa','2019-08-02','sasa','sass','','2019-08-02 16:00:57','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('9','S00001','Aneka Wangi Surabaya','','Panggung No. 159   
','','Jawa Timur','Surabaya','agen','1','30','','','','','','','','','0','active','Bpk. Usman','','031-3523802','0813-31599990','','2019-09-04 11:46:10','2019-09-10 13:17:31','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('10','S00002','Laksa Wangi','','Bongkaran','','Jawa Timur','Surabaya','smallreseller','1','30','5000000.00','','','','','','','','0','active','Hansel','','031-3550543','','','2019-09-04 11:56:55','2019-09-10 13:17:41','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('11','S00003','Bas Parfum','','Sultan Iskandar Muda Spg Punge Blang Cut
','','Sumatera','Banda Aceh','agen','1','30','0.00','','','','','','','','0','active','','','085210278920','','','2019-09-06 13:46:27','2019-09-10 13:17:55','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('12','S00004','El-Azzam Parfum / Blossom Parfum','','Halat No.41 Medan
','','Sumatera Utara','Medan','agen','1','30','0.00','','','','','','','','0','active','Bpk. Muhlis Harahap','','081322211169','','','2019-09-06 13:56:22','2019-09-10 13:18:08','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('13','S00005','King\'s Perfume Lampung','','Kamboja No. 78 Enggal - Tj. Karang Pusat','','Sumatera','Bandar Lampung','agen','1','30','0.00','','','','','','','','0','active','Bpk. Fariz','','081279640382','','','2019-09-06 13:58:24','2019-09-10 13:18:18','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('14','S00006','Lisa Wangi Jakarta','','Dewi Sartika No. 15 C Cililitan
','','Jawa Barat','Jakarta','agen','1','30','0.00','','','','','','','','0','active','Ibu Zaenab','','021-80876617','','','2019-09-06 13:59:59','2019-09-10 13:18:27','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('15','S00007','Yahya Hasan','','Lontar 10 A (Kh. Mas Mansyur)
','','Jawa Barat','Jakarta','agen','1','30','0.00','','','','','','','','0','active','Bpk. Yahya Hasan','','08121064002','','','2019-09-06 14:01:29','2019-09-10 13:18:53','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('16','S00008','Inama Wangi','','Kh Mansyur 38B Tanah Abang Jakarta Pusat
','','Jawa Barat','Jakarta','agen','1','30','0.00','','','','','','','','0','active','Ibu Lisa','','082120501743','021-3911951','','2019-09-06 14:04:03','2019-09-10 13:19:02','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('17','S00009','Tania Parfum','','Raya Cihaleut Pakuan (Depan Univ. Pakuan), Tegalega
','','Jawa Barat','Bogor','agen','1','30','0.00','','','','','','','','0','active','Bpk. Apep','','081382631253','','','2019-09-06 14:05:53','2019-09-10 13:19:10','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('18','S00010','Go Parfum','','Hos Cokroaminoto No. 40 Larangan Utara
','','Banten','Tangerang','agen','1','30','0.00','','','','','','','','0','active','Bpk. Herman','','081213400236','','','2019-09-06 14:07:48','2019-09-10 13:19:21','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('19','S00011','Villa Parfum','','Kompleks Istana Mekar Wangi Mekar Utama 108
','','Jawa Barat','Bandung','agen','1','0','0.00','','','','','','','','0','active','Bpk. Hendry','','022-61082820','','','2019-09-06 14:09:35','2019-09-10 13:19:30','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('20','S00012','Kowani Parfum','','Pasir Kaliki No. 150G
','','Jawa Barat','Bandung','agen','1','30','0.00','','','','','','','','0','active','Bpk. Hadi','','0224-208675','','','2019-09-06 14:10:50','2019-09-10 13:19:41','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('21','S00013','Lisa Wangi Bandung','','Raya Bkr No. 15 (Ruko 4&5)
','','Jawa Barat','Bandung','agen','1','30','0.00','','','','','','','','0','active','Ibu Jasmine','','022-7318637','','','2019-09-06 14:12:00','2019-09-10 13:19:51','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('22','S00014','Nur Aroma','','Bkr No. 202
','','Jawa Barat','Bandung','agen','1','30','0.00','','','','','','','','0','active','Ibu Nur','','0813-82001445','','','2019-09-06 14:13:25','2019-09-10 13:20:17','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('23','S00015','Big Three','','Margacinta No.99 Komp. Ruko Mustika Hegar Regency Rt.G02
','','Jawa Barat','Bandung','agen','1','30','0.00','','','','','','','','0','active','Bpk. Jo','','0813-20112099','','','2019-09-06 14:14:54','2019-09-10 13:20:27','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('24','S00016','Collector Parfum','','Paledang No. 58
','','Jawa Barat','Bandung ','agen','1','30','0.00','','','','','','','','0','active','Ibu Iryani','','0878-21146777','','','2019-09-06 14:15:53','2019-09-10 13:20:39','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('25','S00017','In Parfum','','Kh. Ahmad Dahlan ( Banteng) No. 75
','','Jawa Barat','Bandung ','agen','1','30','0.00','','','','','','','','0','active','Bpk. Indra','','022-7307063','0878-21234100','','2019-09-06 14:17:00','2019-09-10 13:20:49','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('26','S00018','Paris Parfum Bandung','','Lengkong Besar 27
','','Jawa Barat','Bandung','agen','1','30','0.00','','','','','','','','0','active','Bpk. Agil','','0821-30019070','','','2019-09-06 14:18:48','2019-09-10 13:21:05','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('27','S00019','Riana Wangi','','Tuparev 162
','','Jawa Barat','Cirebon','agen','1','30','0.00','','','','','','','','0','active','Bpk. Fariz','','0878-28809753','','','2019-09-06 14:21:14','2019-09-10 13:21:19','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('28','S00020','Ngabean Parfum','1','Kh. Ahmad Dahlan No. 111
','','Yogyakarta','Yogyakarta','agen','1','30','0.00','','','','','','','','0','active','Bpk. Adink','','O82242249990 ','0274-370180','','2019-09-06 14:44:22','2019-09-10 11:41:42','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('29','S00021','Rasa Abadi','1','Nologater 189 B Ambarukmo','','Yogyakarta','Yogyakarta','agen','1','30','0.00','','','','','','','','0','active','Bpk. Onyx','','081-804005111 ','0274-487398','','2019-09-10 11:41:22','2019-09-10 11:42:51','1','1','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('30','S00022','Copagabana','1','Tridharma No.3 
','','Jawa Timur','Gresik','agen','1','30','0.00','','','','','','','','0','active','Bpk. Udin','','0812-3566594','','','2019-09-10 11:45:20','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('31','S00023','Alif Haqiqi','1','Tridharma No.3 
','','Jawa Timur','Gresik','agen','0','','','','','','','','','','0','active','Ibu Alif','','0856-07297860','','','2019-09-10 11:46:30','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('32','S00024','Ari Parfum','1','Ade Irma Suryani No. 1-30, Kauman, Klojen
','','Jawa Timur','Malang','agen','1','30','0.00','','','','','','','','0','active','Bpk. Ari','','0822-36910007','','','2019-09-10 11:47:22','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('33','S00025','Depo Aroma','1','Yulius Usman No.7A Kota Malang
','','Jawa Timur','Malang','agen','1','30','0.00','','','','','','','','0','active','Bpk. Hasan Ahmad','','0878-59127779','','','2019-09-10 11:48:45','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('34','S00026','Sari Wangi Kediri','1','Hos Cokroaminoto No. 1
','','Jawa Timur','Kediri','agen','1','30','0.00','','','','','','','','0','active','Bpk. Ziad','','0896-78606789','0857-35955959','','2019-09-10 11:49:43','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('35','S00027','Aneka Wangi Probolinggo','1','Dr. Wahidin No. 50
','','Jawa Timur','Probolinggo','agen','1','30','0.00','','','','','','','','0','active','Bpk. Faris','','0335-7617837','0813-36600115','','2019-09-10 11:52:51','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('36','S00028','Minyak Wangi Asli','1','Imam Bonjol No. 34
','','Bali','Denpasar','agen','1','30','0.00','','','','','','','','0','active','Bpk. Zein','','036-148011','081-999123454','','2019-09-10 13:02:33','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('37','S00029','Original Parfum','1','Imam Bonjol 391
','','Bali','Denpasar','agen','1','30','0.00','','','','','','','','0','active','Bpk. Farouq','','0817-0665420','','','2019-09-10 13:04:25','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('38','S00030','Emirat Parfum','1','Belitung Laut , Simpang 4 (Lampu Merah S. Parman)
','','Kalimantan Selatan','Banjarmasin','agen','1','30','0.00','','','','','','','','0','active','Bpk. H. Basyir','','0511-3266109','','','2019-09-10 13:05:37','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('39','S00031','Ami Ali','1','Brigjen Hasan Basry Ruko Kayu Tangi No. B8 (Sebelah Bank BTN Banjarmasin)','','Kalimantan Selatan','Banjarmasin','agen','1','30','0.00','','','','','','','','0','active','','','0821-49477555','','','2019-09-10 13:14:56','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('40','S00032','Ami Awad','1','Sulawesi No. 6
','','Kalimantan Selatan','Banjarmasin','agen','1','30','0.00','','','','','','','','0','active','Bpk. H. Rafiq','','0812-50388450','','','2019-09-10 13:16:07','','1','','0');
INSERT INTO `tbl_customer` (`id`,`customer_store_code`,`customer_store_name`,`customer_store_postal_code`,`customer_store_address`,`customer_zone`,`customer_province`,`customer_city`,`customer_type`,`customer_has_tempo`,`customer_tempo_limit`,`customer_credit_limit`,`customer_identity_card_number`,`customer_identity_card_image`,`customer_npwp`,`customer_npwp_image`,`customer_bank_name`,`customer_bank_acc_number`,`customer_bank_acc_name`,`customer_has_ppn`,`customer_status`,`customer_owner_name`,`customer_birthday`,`customer_phone1`,`customer_phone2`,`customer_note`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('41','S00033','Raja Wangi','','Sunu No. 24A
','','Sulawesi Selatan','Makassar','agen','1','30','0.00','','','','','','','','0','active','Bpk. Agil','','0411-453601','','','2019-09-10 13:17:13','','1','','0');



-- -------------------------------------------
-- TABLE DATA tbl_employee
-- -------------------------------------------
INSERT INTO `tbl_employee` (`id`,`user_id`,`employee_name`,`employee_address`,`employee_phone`,`employee_mobile_phone`,`employee_note`) VALUES
('1','1','Super Administrator','Gading Pantai I/34','','','');



-- -------------------------------------------
-- TABLE DATA tbl_factory
-- -------------------------------------------
INSERT INTO `tbl_factory` (`id`,`factory_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('48','Senses','1','','2019-08-07 15:38:08','','0');
INSERT INTO `tbl_factory` (`id`,`factory_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('49','LD','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_factory` (`id`,`factory_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('50','JN','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_factory` (`id`,`factory_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('51','ESP','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_factory` (`id`,`factory_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('52','EIP','1','','2019-08-07 15:38:10','','0');



-- -------------------------------------------
-- TABLE DATA tbl_packaging
-- -------------------------------------------
INSERT INTO `tbl_packaging` (`id`,`packaging_name`,`is_deleted`) VALUES
('1','Botol','0');
INSERT INTO `tbl_packaging` (`id`,`packaging_name`,`is_deleted`) VALUES
('2','jerigen','0');



-- -------------------------------------------
-- TABLE DATA tbl_product
-- -------------------------------------------
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('919','','','SJA 10651','Force Black','48','247','41','0','248','778','m','0.00','47.00','','inactive','sample','2019-08-07 15:38:08','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('920','','','SJA 10650','Man In Charge','48','247','41','0','248','779','m','0.00','47.00','','inactive','sample','2019-08-07 15:38:08','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('921','','','SJA 10656','Haramis','48','247','41','0','249','780','m','0.00','47.00','https://www.fragrantica.com/perfume/Aramis/Aramis-113.html','inactive','sample','2019-08-07 15:38:08','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('922','','','SJA 10648','Chrome United','48','247','41','0','250','781','m','0.00','47.00','https://www.fragrantica.com/perfume/Azzaro/Chrome-United-18963.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('923','','','SJA 10657','Hazzaro','48','247','41','0','250','782','m','0.00','47.00','https://www.fragrantica.com/perfume/Azzaro/Azzaro-pour-Homme-829.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('924','','','SJA 10674','Juba Oriental','48','247','41','0','251','783','f','0.00','47.00','https://www.fragrantica.com/perfume/The-Body-Shop/Juba-Perfume-Oil-3626.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('925','','','SJA 10677','Smoky Poppy','48','247','41','0','251','784','f','0.00','47.00','https://www.fragrantica.com/perfume/The-Body-Shop/Smoky-Poppy-29316.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('926','','','SJA 10676','Smoky Rose','48','247','41','0','251','785','f','0.00','47.00','https://www.fragrantica.com/perfume/The-Body-Shop/White-Musk-Smoky-Rose-20526.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('927','','','SJA 10672','Snow Musk','48','247','41','0','251','786','f','0.00','47.00','https://www.fragrantica.com/perfume/The-Body-Shop/White-Musk-2432.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('928','','','SJA 11042','Brut','48','247','41','0','252','787','m','0.00','47.00','https://www.fragrantica.com/perfume/Brut-Parfums-Prestige/Brut-15303.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('929','','','SJA 11043','Brut Musk','48','247','41','0','252','788','m','0.00','47.00','https://www.fragrantica.com/perfume/Brut-Parfums-Prestige/Brut-Musk-15313.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('930','','','SJA 10671','My Burberries','48','247','41','0','253','789','f','0.00','47.00','https://www.fragrantica.com/perfume/Burberry/My-Burberry-25836.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('931','','','SJA 10861','Weekend Girl','48','247','41','0','253','790','f','0.00','47.00','https://www.fragrantica.com/perfume/Burberry/Weekend-for-Women-1000.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('932','','','SJA 10862','B Extreme','48','247','41','0','254','791','m','0.00','47.00','https://www.fragrantica.com/perfume/Bvlgari/Bvlgari-Extreme-157.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('933','','','SJA 10664','B Rose','48','247','41','0','254','792','f','0.00','47.00','https://www.fragrantica.com/perfume/Bvlgari/Bvlgari-Rose-Essentielle-Eau-De-Toilette-Rosee-1543.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('934','','','SJA 10670','Red Coral','48','247','41','0','254','793','f','0.00','47.00','https://www.fragrantica.com/perfume/Bvlgari/Omnia-Coral-14297.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('935','','','SJA 11540','Yellow Gold','48','247','41','0','254','794','f','0.00','47.00','https://www.fragrantica.com/perfume/Bvlgari/Goldea-31538.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('936','','','SJA 10868','Amor','48','247','41','0','255','795','f','0.00','47.00','https://www.fragrantica.com/perfume/Cacharel/Amor-Amor-238.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('937','','','SJA 10892','CKB','48','247','41','0','256','796','neutral','0.00','47.00','https://www.fragrantica.com/perfume/Calvin-Klein/CK-be-275.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('938','','','SJA 10907','Code One','48','247','41','0','256','797','neutral','0.00','47.00','https://www.fragrantica.com/perfume/Calvin-Klein/CK-One-276.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('939','','','SJA 10894','Contradiction','48','247','41','0','256','798','f','0.00','47.00','https://www.fragrantica.com/perfume/Calvin-Klein/Contradiction-255.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('940','','','SJA 10896','Escapade Women','48','247','41','0','256','799','f','0.00','47.00','https://www.fragrantica.com/perfume/Calvin-Klein/Escape-271.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('941','','','SJA 10900','Eternal Men','48','247','41','0','256','800','m','0.00','47.00','https://www.fragrantica.com/perfume/Calvin-Klein/Eternity-For-Men-258.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('942','','','SJA 10899','Eternal Women','48','247','41','0','256','801','f','0.00','47.00','https://www.fragrantica.com/perfume/Calvin-Klein/Eternity-257.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('943','','','SJA 10902','Forbidden Euphoric','48','247','41','0','256','802','f','0.00','47.00','https://www.fragrantica.com/perfume/Calvin-Klein/Forbidden-Euphoria-12815.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('944','','','SJA 10871','CHNY Men','48','247','41','0','257','803','m','0.00','47.00','https://www.fragrantica.com/perfume/Carolina-Herrera/212-Men-297.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('945','','','SJA 10873','Sexy Man','48','247','41','0','257','804','m','0.00','47.00','https://www.fragrantica.com/perfume/Carolina-Herrera/212-Sexy-Men-1054.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('946','','','SJA 10874','Sexy Women','48','247','41','0','257','805','f','0.00','47.00','https://www.fragrantica.com/perfume/Carolina-Herrera/212-Sexy-306.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('947','','','SJA 10888','Adore','48','247','41','0','258','806','f','0.00','47.00','https://www.fragrantica.com/perfume/Christian-Dior/J-adore-210.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('948','','','SJA 10887','Fahrenheit','48','247','41','0','258','807','m','0.00','47.00','https://www.fragrantica.com/perfume/Christian-Dior/Fahrenheit-228.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('949','','','SJA 10884','Sand Dune','48','247','41','0','258','808','f','0.00','47.00','https://www.fragrantica.com/perfume/Christian-Dior/Dune-221.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('950','','','SJA 11025','Intenso','48','247','41','0','259','809','m','0.00','47.00','https://www.fragrantica.com/perfume/Dolce-Gabbana/Dolce-Gabbana-Pour-Homme-Intenso-28935.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('951','','','SJA 11281','Sassy','48','247','41','0','259','810','f','0.00','47.00','https://www.fragrantica.com/perfume/Dolce-Gabbana/D-G-Anthology-L-Imperatrice-3-6086.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('952','','','SJA 11015','Echo Men','48','247','41','0','260','811','m','0.00','47.00','https://www.fragrantica.com/perfume/Davidoff/Echo-587.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('953','','','SJA 11028','Desire Man','48','247','41','0','261','812','m','0.00','47.00','https://www.fragrantica.com/perfume/Alfred-Dunhill/Desire-for-a-Man-1934.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('954','','','SJA 11029','Men’s Edition','48','247','41','0','261','813','m','0.00','47.00','https://www.fragrantica.com/perfume/Alfred-Dunhill/Dunhill-Edition-10491.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('955','','','SJA 11041','Fifth Avenue / Lady Avenue','48','247','41','0','262','814','f','0.00','47.00','https://www.fragrantica.com/perfume/Elizabeth-Arden/5th-Avenue-81.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('956','','','SJA 11039','Green Tea','48','247','41','0','262','815','f','0.00','47.00','https://www.fragrantica.com/perfume/Elizabeth-Arden/Green-Tea-83.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('957','','','SJA 11038','Grey Uomo','48','247','41','0','263','816','m','0.00','47.00','https://www.fragrantica.com/perfume/Ermenegildo-Zegna/Uomo-17125.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('958','','','SJA 11034','Intuition','48','247','41','0','264','817','f','0.00','47.00','https://www.fragrantica.com/perfume/Est-e-Lauder/Intuition-541.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('959','','','SJA 11032','White Linen','48','247','41','0','264','818','f','0.00','47.00','https://www.fragrantica.com/perfume/Est-e-Lauder/White-Linen-542.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('960','','','SJA 11044','Sports Uomo','48','247','41','0','265','819','m','0.00','47.00','https://www.fragrantica.com/perfume/Ferrari/Ferrari-Uomo-5888.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('961','','','SJA 11246','Giomani Black','48','247','41','0','266','820','m','0.00','47.00','https://www.fragrantica.com/perfume/Giorgio-Armani/Armani-Code-Sport-11649.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('962','','','SJA 11248','Gentlemen Club','48','247','41','0','267','821','m','0.00','47.00','https://www.fragrantica.com/perfume/Givenchy/Gentlemen-Only-17259.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('963','','','SJA 11249','Organica','48','247','41','0','267','822','f','0.00','47.00','https://www.fragrantica.com/perfume/Givenchy/Organza-4.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('964','','','SJA 11253','Envy Me','48','247','41','0','268','823','f','0.00','47.00','https://www.fragrantica.com/perfume/Gucci/Envy-Me-682.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('965','','','SJA 11255','Guilty Women','48','247','41','0','268','824','f','0.00','47.00','https://www.fragrantica.com/perfume/Gucci/Gucci-Guilty-9677.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('966','','','SJA 11257','Premiere','48','247','41','0','268','825','f','0.00','47.00','https://www.fragrantica.com/perfume/Gucci/Gucci-Premiere-15449.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('967','','','SJA 11251','Zucci Women','48','247','41','0','268','826','f','0.00','47.00','https://www.fragrantica.com/perfume/Gucci/Eau-de-Gucci-690.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('968','','','SJA 11254','Zuzzi Flora','48','247','41','0','268','827','f','0.00','47.00','https://www.fragrantica.com/perfume/Gucci/Flora-by-Gucci-Eau-de-Parfum-7610.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('969','','','SJA 11259','Drakkus','48','247','41','0','269','828','m','0.00','47.00','https://www.fragrantica.com/perfume/Guy-Laroche/Drakkar-Noir-2069.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('970','','','SJA 11261','D’Terry','48','247','41','0','270','829','m','0.00','47.00','https://www.fragrantica.com/perfume/Herm-s/Terre-d-Hermes-17.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('971','','','SJA 11263','Bossy Blue','48','247','41','0','271','830','m','0.00','47.00','https://www.fragrantica.com/perfume/Hugo-Boss/Hugo-Dark-Blue-564.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('972','','','SJA 11266','Bossy Bottle','48','247','41','0','271','831','m','0.00','47.00','https://www.fragrantica.com/perfume/Hugo-Boss/Boss-Bottled-383.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('973','','','SJA 12312','Unlimited','48','247','41','0','271','832','m','0.00','47.00','https://www.fragrantica.com/perfume/Hugo-Boss/Boss-Bottled-Unlimited-22528.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('974','','','SJA 11269','Bossy Motion','48','247','41','0','271','833','m','0.00','47.00','https://www.fragrantica.com/perfume/Hugo-Boss/Boss-in-Motion-382.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('975','','','SJA 11267','Bossy Red','48','247','41','0','271','834','m','0.00','47.00','https://www.fragrantica.com/perfume/Hugo-Boss/Hugo-Red-17157.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('976','','','SJA 11270','Bossy Women','48','247','41','0','271','835','f','0.00','47.00','https://www.fragrantica.com/perfume/Hugo-Boss/Hugo-Woman-380.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('977','','','SJA 11272','Hessy Men','48','247','41','0','272','836','m','0.00','47.00','https://www.fragrantica.com/perfume/Issey-Miyake/L-Eau-d-Issey-Pour-Homme-721.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('978','','','SJA 11273','Hessy Pleats','48','247','41','0','272','837','f','0.00','47.00','https://www.fragrantica.com/perfume/Issey-Miyake/Pleats-Please-15464.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('979','','','SJA 12122','Black Panther','48','247','41','0','273','838','m','0.00','47.00','https://www.fragrantica.com/perfume/Jaguar/Classic-Black-11793.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('980','','','SJA 11611','Someday','48','247','41','0','274','839','f','0.00','47.00','https://www.fragrantica.com/perfume/Justin-Bieber/Someday-12365.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('981','','','SJA 11514','Killer Queen','48','247','41','0','275','840','f','0.00','47.00','https://www.fragrantica.com/perfume/Katy-Perry/Killer-Queen-18318.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('982','','','SJA 12326','Swift','48','247','41','0','276','841','f','0.00','47.00','https://www.fragrantica.com/perfume/Taylor-Swift/Taylor-18489.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('983','','','SJA 11517','Cold Reaction','48','247','41','0','277','842','m','0.00','47.00','https://www.fragrantica.com/perfume/Kenneth-Cole/Connected-Kenneth-Cole-Reaction-12941.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('984','','','SJA 11520','Pak Kenzo','48','247','41','0','278','843','m','0.00','47.00','https://www.fragrantica.com/perfume/Kenzo/L-Eau-par-Kenzo-pour-Homme-79.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('985','','','SJA 11523','1212 Red','48','247','41','0','279','844','m','0.00','47.00','https://www.fragrantica.com/perfume/Lacoste-Fragrances/Eau-de-Lacoste-L-12-12-Red-14183.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('986','','','SJA 11527','Midnight Rose','48','247','41','0','280','845','f','0.00','47.00','https://www.fragrantica.com/perfume/Lancome/Tresor-Midnight-Rose-11721.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('987','','','SJA 10910','Bergamot Cologne','48','247','41','0','281','846','neutral','0.00','47.00','https://www.fragrantica.com/perfume/Le-Labo/Bergamote-22-6327.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('988','','','SJA 11532','Legendary','48','247','41','0','282','847','m','0.00','47.00','https://www.fragrantica.com/perfume/Montblanc/Legend-11784.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('989','','','SJA 11533','Toy Bear','48','247','41','0','283','848','neutral','0.00','47.00','https://www.fragrantica.com/perfume/Moschino/Toy-28805.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('990','','','SJA 12123','Playboy','48','247','41','0','284','849','m','0.00','47.00','https://www.fragrantica.com/perfume/Playboy/London-12522.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('991','','','SJA 11690','Casual Life','48','247','41','0','285','850','m','0.00','47.00','https://www.fragrantica.com/perfume/Salvatore-Ferragamo/Uomo-Salvatore-Ferragamo-Casual-Life-44863.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('992','','','SJA 11222','Malaki Muzaffar','48','247','42','0','247','851','neutral','0.00','57.00','','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('993','','','SJA 11236','Al Madinah','48','247','42','0','247','851','neutral','0.00','57.00','','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('994','','','SJA 11233','Bakhoor','48','247','42','0','247','851','neutral','0.00','57.00','','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('995','','','SJA 11229','Seribu Bunga','48','247','42','0','247','851','neutral','0.00','57.00','','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('996','','','SJA 11227','Kasturi Putih','48','247','42','0','247','851','neutral','0.00','57.00','','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('997','','','SJA 11230','Sweet Malaya','48','247','42','0','247','851','neutral','0.00','57.00','','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('998','','','SJA 11652','Blue Motion','48','247','43','0','286','852','m','0.00','77.00','https://www.fragrantica.com/perfume/Etienne-Aigner/Aigner-pour-Homme-Blue-Emotion-3312.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('999','','','SJA 12663','Salvage','48','247','43','0','258','853','m','0.00','77.00','https://www.fragrantica.com/perfume/Christian-Dior/Sauvage-31861.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1000','','',' SJA 11297','Black Diamond','48','247','44','0','247','854','m','0.00','77.00','','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1001','','',' SJA 11300','Cubar Cigar','48','247','44','0','247','854','m','0.00','77.00','','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1002','','','99108010','Lady Fancy','49','287','45','0','288','855','f','0.00','90.00','https://www.fragrantica.com/perfume/Anna-Sui/Flight-Of-Fancy-2030.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1003','','','99108808','Lady Rock','49','287','45','0','288','856','f','0.00','72.00','https://www.fragrantica.com/perfume/Anna-Sui/Rock-Me--6887.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1004','','','641804002','Comma Pure','50','287','','0','250','857','m','0.00','62.00','https://www.fragrantica.com/perfume/Azzaro/Chrome-Pure-43131.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1005','','','641604013','Azalea Elle','50','287','','0','250','858','f','0.00','60.00','https://www.fragrantica.com/perfume/Azzaro/Azzaro-Pour-Elle-29476.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1006','','','641609040','Gun Man','50','287','','0','250','859','m','0.00','62.00','https://www.fragrantica.com/perfume/Azzaro/Wanted-38686.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1007','','','641609044','Bondi Beach','50','287','45','0','289','860','f','0.00','87.00','https://www.fragrantica.com/perfume/Bond-No-9/The-Scent-of-Peace-for-Him-20551.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1008','','','641609038','Mr. B','50','287','','0','253','861','m','0.00','62.00','https://www.fragrantica.com/perfume/Burberry/Mr-Burberry-32565.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1009','','','50248','Bali','51','287','','0','253','862','f','0.00','62.00','https://www.fragrantica.com/perfume/Burberry/Burberry-Women-818.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1010','','','5426047/B','Bestmen','51','287','','0','253','863','m','0.00','62.00','https://www.fragrantica.com/perfume/Burberry/Burberry-Brit-For-Him-Limited-Edition-43548.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1011','','','651506019','My Lady','51','287','','0','253','789','f','0.00','57.00','https://www.fragrantica.com/perfume/Burberry/My-Burberry-25836.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1012','','','11576/4','Mado','51','287','45','0','253','789','f','0.00','87.00','https://www.fragrantica.com/perfume/Burberry/My-Burberry-25836.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1013','','','651410008','Amarant','50','287','','0','254','864','m','0.00','44.00','https://www.fragrantica.com/perfume/Bvlgari/Aqva-Amara-22675.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1014','','','R.140612.7','Bulle Amer','51','287','45','0','254','864','m','0.00','78.00','https://www.fragrantica.com/perfume/Bvlgari/Aqva-Amara-22675.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1015','','','640713050','ALG Super','51','287','45','0','254','865','m','0.00','85.00','https://www.fragrantica.com/perfume/Bvlgari/Aqva-Pour-Homme-153.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1016','','','10179/4/L','Marina Bay','51','287','','0','254','866','f','0.00','67.00','https://www.fragrantica.com/perfume/Bvlgari/Aqva-Pour-Homme-Marine-1742.html','inactive','sample','2019-08-07 15:38:09','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1017','','','641710016','Atlantika','50','287','','0','254','867','m','0.00','62.00','https://www.fragrantica.com/perfume/Bvlgari/Aqva-Pour-Homme-Atlantiqve-42856.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1018','','','641511023','Lady Divina','50','287','','0','254','868','f','0.00','62.00','https://www.fragrantica.com/perfume/Bvlgari/Aqva-Divina-29442.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1019','','','5526054/B','Excellence','52','287','','0','254','791','m','0.00','45.00','https://www.fragrantica.com/perfume/Bvlgari/Bvlgari-Extreme-157.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1020','','','99108002','Extremely','49','287','45','0','254','791','m','0.00','87.00','https://www.fragrantica.com/perfume/Bvlgari/Bvlgari-Extreme-157.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1021','','','610710002','Black Jasmin','51','287','','0','254','869','f','0.00','62.00','https://www.fragrantica.com/perfume/Bvlgari/Jasmin-Noir-3750.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1022','','','50315','Obi','51','287','','0','254','870','f','0.00','62.00','https://www.fragrantica.com/perfume/Bvlgari/Omnia-151.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1023','','','641710018','All White','50','287','','0','256','871','neutral','0.00','62.00','https://www.fragrantica.com/perfume/Calvin-Klein/CK-All-42944.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1024','','','610513036','Escadine','51','287','','0','256','799','f','0.00','68.00','https://www.fragrantica.com/perfume/Calvin-Klein/Escape-271.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1025','','','610513035','Menetry B','51','287','','0','256','800','m','0.00','67.00','https://www.fragrantica.com/perfume/Calvin-Klein/Eternity-For-Men-258.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1026','','','2125','Eternal','51','287','45','0','256','800','m','0.00','127.00','https://www.fragrantica.com/perfume/Calvin-Klein/Eternity-For-Men-258.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1027','','','99108011','Hitman','49','287','45','0','257','803','m','0.00','92.00','https://www.fragrantica.com/perfume/Carolina-Herrera/212-Men-297.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1028','','','610212005','Vivaldi','51','287','45','0','257','872','m','0.00','87.00','https://www.fragrantica.com/perfume/Carolina-Herrera/212-VIP-Men-12865.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1029','','','651410007','Rose VIP','50','287','','0','257','873','f','0.00','57.00','https://www.fragrantica.com/perfume/Carolina-Herrera/212-VIP-Ros--22857.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1030','','','R.140612.2','Cent VIP','50','287','45','0','257','873','f','0.00','83.00','https://www.fragrantica.com/perfume/Carolina-Herrera/212-VIP-Ros--22857.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1031','','','12030/4','Grazia','51','287','45','0','257','874','f','0.00','85.00','https://www.fragrantica.com/perfume/Carolina-Herrera/Good-Girl-39681.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1032','','','641704011','Pasha Noir','51','287','','0','290','875','m','0.00','62.00','https://www.fragrantica.com/perfume/Cartier/Pasha-de-Cartier-Edition-Noire-Sport-34282.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1033','','','50370','Aida','51','287','45','0','291','795','f','0.00','72.00','https://www.fragrantica.com/perfume/Cacharel/Amor-Amor-238.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1034','','','610411008','Bleuish','51','287','45','0','292','876','m','0.00','92.00','https://www.fragrantica.com/perfume/Chanel/Bleu-de-Chanel-9099.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1035','','','651702001','Enchanted 5','51','287','','0','292','877','f','0.00','64.00','https://www.fragrantica.com/perfume/Chanel/Chanel-No-5-L-Eau-38543.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1036','','','12009/4','Carat W','51','287','45','0','292','877','f','0.00','90.00','https://www.fragrantica.com/perfume/Chanel/Chanel-No-5-L-Eau-38543.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1037','','','641710017','Gaby','50','287','','0','292','878','f','0.00','62.00','https://www.fragrantica.com/perfume/Chanel/Gabrielle-43718.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1038','','','651410010','Bloom Fleur','51','287','','0','258','879','f','0.00','62.00','https://www.fragrantica.com/perfume/Christian-Dior/Miss-Dior-Blooming-Bouquet-23280.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1039','','','11474/4','Belina','51','287','45','0','258','879','f','0.00','87.00','https://www.fragrantica.com/perfume/Christian-Dior/Miss-Dior-Blooming-Bouquet-23280.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1040','','','641609048','Adore','50','287','45','0','258','880','f','0.00','87.00','https://www.fragrantica.com/perfume/Christian-Dior/J-adore-Lumiere-Eau-de-Toilette-36495.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1041','','','50365','Hiffi – E','51','287','45','0','258','881','m','0.00','72.00','https://www.fragrantica.com/perfume/Christian-Dior/Higher-Energy-234.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1042','','','611907033','Farandole','51','287','45','0','258','807','m','0.00','100.00','https://www.fragrantica.com/perfume/Christian-Dior/Fahrenheit-228.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1043','','','641511026','Savage','50','287','','0','258','853','m','0.00','62.00','https://www.fragrantica.com/perfume/Christian-Dior/Sauvage-31861.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1044','','','611907031','Salvation','51','287','45','0','258','853','m','0.00','88.00','https://www.fragrantica.com/perfume/Christian-Dior/Sauvage-31861.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1045','','','12212/4','Alesia','51','287','45','0','293','882','neutral','0.00','85.00','https://www.fragrantica.com/perfume/Creed/Asian-Green-Tea-24780.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1046','','','651503014','Silver C','51','287','','0','293','883','m','0.00','62.00','https://www.fragrantica.com/perfume/Creed/Aventus-9828.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1047','','','10817/6','Alix','51','287','45','0','293','883','m','0.00','95.00','https://www.fragrantica.com/perfume/Creed/Aventus-9828.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1048','','','641609043','Aventadora','50','287','45','0','293','884','f','0.00','82.00','https://www.fragrantica.com/perfume/Creed/Aventus-for-Her-38497.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1049','','','641711028','Legend Thor','50','287','','0','293','885','m','0.00','62.00','https://www.fragrantica.com/perfume/Creed/Viking-41698.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1050','','','611907032','Greenish','50','287','45','0','293','886','m','0.00','95.00','https://www.fragrantica.com/perfume/Creed/Green-Irish-Tweed-474.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1051','','','641511028','White Love','50','287','45','0','293','887','f','0.00','72.00','https://www.fragrantica.com/perfume/Creed/Love-in-White-4262.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1052','','','640713051/','DVB Essensial','50','287','','0','294','888','m','0.00','61.00','https://www.fragrantica.com/perfume/David-Beckham/The-Essence-15956.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1053','','','641604009','DVB Aqua','50','287','','0','294','889','m','0.00','58.00','https://www.fragrantica.com/perfume/David-Beckham/Aqua-Classic-35019.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1054','','','10185/2/M','Signal','51','287','','0','294','890','f','0.00','62.00','https://www.fragrantica.com/perfume/David-Beckham/Signature-for-Her-3795.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1055','','','1775','Cool Man','51','287','','0','260','891','m','0.00','57.00','https://www.fragrantica.com/perfume/Davidoff/Cool-Water-507.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1056','','','1754','Cool Man','51','287','45','0','260','891','m','0.00','82.00','https://www.fragrantica.com/perfume/Davidoff/Cool-Water-507.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1057','','','641511024','NY Rooftop','50','287','','0','295','892','f','0.00','57.00','https://www.fragrantica.com/perfume/Donna-Karan/DKNY-Be-Delicious-City-Blossom-Rooftop-Peony-22978.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1058','','','611111001','Imperium ','51','287','45','0','296','810','f','0.00','87.00','https://www.fragrantica.com/perfume/Dolce-Gabbana/D-G-Anthology-L-Imperatrice-3-6086.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1059','','','651410003','Just Dolce','51','287','','0','296','893','f','0.00','62.00','https://www.fragrantica.com/perfume/Dolce-Gabbana/Dolce-22955.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1060','','','11467/4','Doneta','51','287','45','0','296','893','f','0.00','82.00','https://www.fragrantica.com/perfume/Dolce-Gabbana/Dolce-22955.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1061','','','610712030','Uno Sport','51','287','45','0','296','894','m','0.00','87.00','https://www.fragrantica.com/perfume/Dolce-Gabbana/The-One-Sport-14225.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1062','','','99139014','Desire Man','49','287','45','0','261','895','m','0.00','79.00','https://www.fragrantica.com/perfume/Alfred-Dunhill/Desire-Blue-1937.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1063','','','651503016','Dunning Black','51','287','','0','261','896','m','0.00','62.00','https://www.fragrantica.com/perfume/Alfred-Dunhill/Desire-Black-24926.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1064','','','11542/2','Double Black','51','287','45','0','261','896','m','0.00','86.00','https://www.fragrantica.com/perfume/Alfred-Dunhill/Desire-Black-24926.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1065','','','641711027','Djanggo Elite','50','287','','0','261','897','m','0.00','62.00','https://www.fragrantica.com/perfume/Alfred-Dunhill/Icon-Elite-38982.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1066','','','50201','Doudou','51','287','','0','297','898','m','0.00','70.00','https://www.fragrantica.com/perfume/S-T-Dupont/S-T-Dupont-pour-Homme-3996.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1067','','','641711023','Elisa Resort','50','287','','0','298','899','f','0.00','62.00','https://www.fragrantica.com/perfume/Elie-Saab/Le-Parfum-Resort-Collection-29164.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1068','','','610513038','Cherry Girl','51','287','45','0','299','900','f','0.00','90.00','https://www.fragrantica.com/perfume/Escada/Cherry-in-the-Air-17129.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1069','','','641604012','Escale Sun','50','287','','0','299','901','f','0.00','60.00','https://www.fragrantica.com/perfume/Escada/Agua-del-Sol-32741.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1070','','','641704009','Lady Fiesta','50','287','','0','299','902','f','0.00','62.00','https://www.fragrantica.com/perfume/Escada/Fiesta-Carioca-41897.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1071','','','50375','Margot','51','287','','0','299','903','f','0.00','62.00','https://www.fragrantica.com/perfume/Escada/Escada-Magnetism-1036.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1072','','','10042/P','Moonlight','51','287','','0','299','904','f','0.00','57.00','https://www.fragrantica.com/perfume/Escada/Escada-Moon-Sparkle-1887.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1073','','','99108007','Lady Sexy','49','287','','0','299','905','f','0.00','47.00','https://www.fragrantica.com/perfume/Escada/Escada-Sexy-Graffiti-2011-14010.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1074','','','651609047','Red Muse','51','287','','0','264','906','f','0.00','62.00','https://www.fragrantica.com/perfume/Est-e-Lauder/Modern-Muse-Le-Rouge-31293.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1075','','','11861/4','Madie LR','51','287','45','0','264','906','f','0.00','87.00','https://www.fragrantica.com/perfume/Est-e-Lauder/Modern-Muse-Le-Rouge-31293.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1076','','','610412027','Mystic Lady','51','287','45','0','264','907','neutral','0.00','82.00','https://www.fragrantica.com/perfume/Est-e-Lauder/Wood-Mystique-13636.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1077','','','881403001','Blue Motion','48','287','','0','286','852','m','0.00','70.00','https://www.fragrantica.com/perfume/Etienne-Aigner/Aigner-pour-Homme-Blue-Emotion-3312.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1078','','','99108004','Emotional','49','287','45','0','286','852','m','0.00','82.00','https://www.fragrantica.com/perfume/Etienne-Aigner/Aigner-pour-Homme-Blue-Emotion-3312.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1079','','','99139010','Aquaman','49','287','','0','266','908','m','0.00','70.00','https://www.fragrantica.com/perfume/Giorgio-Armani/Acqua-di-Gio-410.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1080','','','641511020','Aquaman Intense','50','287','','0','266','909','m','0.00','62.00','https://www.fragrantica.com/perfume/Giorgio-Armani/Acqua-di-Gio-Profumo-29727.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1081','','','641804001','Stranger Man','50','287','','0','266','910','m','0.00','67.00','https://www.fragrantica.com/perfume/Giorgio-Armani/Emporio-Armani-Stronger-With-You-45258.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1082','','','641711026','Only You','50','287','','0','266','911','f','0.00','62.00','https://www.fragrantica.com/perfume/Giorgio-Armani/Emporio-Armani-Because-It-s-You-45257.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1083','','','651406001','Rosa Arabia','51','287','','0','266','912','f','0.00','67.00','https://www.fragrantica.com/perfume/Giorgio-Armani/Armani-Priv-Rose-d-Arabie-10886.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1084','','','651410006','Sicilia','50','287','','0','266','913','f','0.00','57.00','https://www.fragrantica.com/perfume/Giorgio-Armani/Si-18453.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1085','','','R.140612.5','Flamme Sol','50','287','45','0','266','913','f','0.00','82.00','https://www.fragrantica.com/perfume/Giorgio-Armani/Si-18453.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1086','','','641907026','ABSOLUT AQUA','50','287','','0','266','914','m','0.00','70.00','https://www.fragrantica.com/perfume/Giorgio-Armani/Acqua-Di-Gio-Absolu-48138.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1087','','','610712029','Player Sport','51','287','45','0','267','915','m','0.00','87.00','https://www.fragrantica.com/perfume/Givenchy/Play-Sport-14596.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1088','','','641907025','Deep Intrigue','50','287','45','0','267','916','m','0.00','78.00','https://www.fragrantica.com/perfume/Givenchy/L-Interdit-2018--51488.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1089','','','641511022','Lady Bamboo','50','287','','0','268','917','f','0.00','62.00','https://www.fragrantica.com/perfume/Gucci/Gucci-Bamboo-30815.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1090','','','610411009','Guido','51','287','45','0','268','918','m','0.00','87.00','https://www.fragrantica.com/perfume/Gucci/Guilty-Pour-Homme-11037.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1091','','','610411011','Gauda','51','287','45','0','268','824','f','0.00','87.00','https://www.fragrantica.com/perfume/Gucci/Gucci-Guilty-9677.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1092','','','611907030','Black Guilt','51','287','45','0','268','919','m','0.00','95.00','https://www.fragrantica.com/perfume/Gucci/Gucci-Guilty-Black-Pour-Homme-17322.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1093','','','651410005','Homme Idealist','51','287','','0','300','920','m','0.00','64.00','https://www.fragrantica.com/perfume/Guerlain/L-Homme-Ideal-25780.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1094','','','11524/5','Hong','51','287','45','0','300','920','m','0.00','87.00','https://www.fragrantica.com/perfume/Guerlain/L-Homme-Ideal-25780.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1095','','','641711022','Sweet Jolie','50','287','','0','300','921','f','0.00','62.00','https://www.fragrantica.com/perfume/Guerlain/Mon-Guerlain-43297.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1096','','','50164','Bingo','51','287','','0','271','922','m','0.00','64.00','https://www.fragrantica.com/perfume/Hugo-Boss/Boss-in-Motion-382.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1097','','','50549','Banco','51','287','45','0','271','923','m','0.00','80.00','https://www.fragrantica.com/perfume/Hugo-Boss/Boss-in-Motion-edition-IV-1171.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1098','','','651410004','Bossy Night','51','287','','0','271','924','f','0.00','67.00','https://www.fragrantica.com/perfume/Hugo-Boss/Boss-Nuit-Pour-Femme-Intense-24083.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1099','','','11478/4','Naisa I','51','287','45','0','271','924','f','0.00','87.00','https://www.fragrantica.com/perfume/Hugo-Boss/Boss-Nuit-Pour-Femme-Intense-24083.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1100','','','610411013','Orange Bos','51','287','45','0','271','925','m','0.00','87.00','https://www.fragrantica.com/perfume/Hugo-Boss/Boss-Orange-for-Men-11070.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1101','','','9456/3','Etienne','51','287','45','0','271','926','m','0.00','77.00','https://www.fragrantica.com/perfume/Hugo-Boss/Hugo-Energise-569.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1102','','','641609039','Helgos Extreme','50','287','','0','271','927','m','0.00','62.00','https://www.fragrantica.com/perfume/Hugo-Boss/Hugo-Extreme-34871.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1103','','','610513039','Man In Red','51','287','45','0','271','834','m','0.00','87.00','https://www.fragrantica.com/perfume/Hugo-Boss/Hugo-Red-17157.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1104','','','641704010','Frozen','50','287','','0','271','928','m','0.00','62.00','https://www.fragrantica.com/perfume/Hugo-Boss/Hugo-Iced-42484.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1105','','','611111002','Difrensis','51','287','45','0','271','929','m','0.00','90.00','https://www.fragrantica.com/perfume/Hugo-Boss/Hugo-Just-Different-12311.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1106','','','651503015','Odyssey Noir','51','287','','0','272','930','m','0.00','62.00','https://www.fragrantica.com/perfume/Issey-Miyake/Nuit-d-Issey-25514.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1107','','','11564/4','Odilon','51','287','45','0','272','930','m','0.00','87.00','https://www.fragrantica.com/perfume/Issey-Miyake/Nuit-d-Issey-25514.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1108','','','50199','Opaline','51','287','','0','272','931','f','0.00','62.00','https://www.fragrantica.com/perfume/Issey-Miyake/L-eau-d-Issey-720.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1109','','','991808017','Still Love','49','287','45','0','301','932','f','0.00','81.00','https://www.fragrantica.com/perfume/Jennifer-Lopez/Still-868.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1110','','','50654','Justin','51','287','45','0','273','933','m','0.00','77.00','https://www.fragrantica.com/perfume/Jaguar/Jaguar-6809.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1111','','','12052/4','Jason','51','287','45','0','302','934','m','0.00','85.00','https://www.fragrantica.com/perfume/Jimmy-Choo/Jimmy-Choo-Man-Intense-39467.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1112','','','610712028','Koala Sport','51','287','45','0','278','935','m','0.00','87.00','https://www.fragrantica.com/perfume/Kenzo/Kenzo-Homme-Sport-14735.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1113','','','3646','Zenko','51','287','','0','278','936','m','0.00','57.00','https://www.fragrantica.com/perfume/Kenzo/Kenzo-pour-Homme-77.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1114','','','3774','Zenko','51','287','','0','278','936','m','0.00','67.00','https://www.fragrantica.com/perfume/Kenzo/Kenzo-pour-Homme-77.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1115','','','7576051/B','Lavish','52','287','','0','278','843','m','0.00','62.00','https://www.fragrantica.com/perfume/Kenzo/L-Eau-par-Kenzo-pour-Homme-79.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1116','','','5566050/B','Legacy','52','287','','0','278','937','f','0.00','64.00','https://www.fragrantica.com/perfume/Kenzo/L-Eau-par-Kenzo-78.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1117','','','641907027','Aqua Kenzo','50','287','45','0','278','938','m','0.00','78.00','https://www.fragrantica.com/perfume/Kenzo/Aqua-Kenzo-pour-Homme-49290.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1118','','','651702002','Wonderland','51','287','','0','278','939','m','0.00','62.00','https://www.fragrantica.com/perfume/Kenzo/Kenzo-World-40278.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1119','','','11993/4','Windy','51','287','45','0','278','939','m','0.00','87.00','https://www.fragrantica.com/perfume/Kenzo/Kenzo-World-40278.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1120','','','610411010','Joya Pink','51','287','45','0','279','940','f','0.00','87.00','https://www.fragrantica.com/perfume/Lacoste-Fragrances/Joy-of-Pink-10492.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1121','','','641511021','Croco Man 21','50','287','','0','279','941','m','0.00','60.00','https://www.fragrantica.com/perfume/Lacoste-Fragrances/Eau-de-Lacoste-L-12-12-Yellow-Jaune--28608.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1122','','','641609049','Croco Energy','50','287','','0','279','942','m','0.00','62.00','https://www.fragrantica.com/perfume/Lacoste-Fragrances/Lacoste-L-12-12-Energized-38163.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1123','','','10210/2/N','Magnificient','51','287','','0','280','943','f','0.00','62.00','https://www.fragrantica.com/perfume/Lancome/Magnifique-3746.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1124','','','610212004','Tresnoir','51','287','45','0','280','845','f','0.00','87.00','https://www.fragrantica.com/perfume/Lancome/Tresor-Midnight-Rose-11721.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1125','','','641604011','Lola Girl','50','287','45','0','303','944','f','0.00','82.00','https://www.fragrantica.com/perfume/Lolita-Lempicka/Lolita-Lempicka-456.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1126','','','641704008','Luvinton','50','287','','0','304','945','f','0.00','62.00','https://www.fragrantica.com/perfume/Louis-Vuitton/Turbulences-40499.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1127','','','641603006','Berlin Man','50','287','','0','305','946','m','0.00','62.00','https://www.fragrantica.com/perfume/Mercedes-Benz/Mercedes-Benz-13496.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1128','','','651410011','Emblemont','51','287','','0','282','947','m','0.00','62.00','https://www.fragrantica.com/perfume/Montblanc/Emblem-23711.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1129','','','11518/4','Eden','51','287','45','0','282','947','m','0.00','87.00','https://www.fragrantica.com/perfume/Montblanc/Emblem-23711.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1130','','','610513037','Legendary','51','287','45','0','282','948','f','0.00','87.00','https://www.fragrantica.com/perfume/Montblanc/Legend-Pour-Femme-16059.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1131','','','641604010','Sparta Legend','50','287','','0','282','949','m','0.00','62.00','https://www.fragrantica.com/perfume/Montblanc/Legend-Spirit-33443.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1132','','','610412026','Man in Black','51','287','45','0','306','950','m','0.00','87.00','https://www.fragrantica.com/perfume/Paco-Rabanne/Black-XS-L-Exces-for-Him-13805.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1133','','','641609041','Black Lax','50','287','','0','306','951','m','0.00','62.00','https://www.fragrantica.com/perfume/Paco-Rabanne/Black-XS-Los-Angeles-for-Him-35254.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1134','','','641609042','Lady Lax','50','287','','0','306','952','f','0.00','62.00','https://www.fragrantica.com/perfume/Paco-Rabanne/Black-XS-Los-Angeles-for-Her-35253.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1135','','','651503017','Miamy Beauty','51','287','','0','306','953','f','0.00','62.00','https://www.fragrantica.com/perfume/Paco-Rabanne/Lady-Million-Eau-My-Gold--24913.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1136','','','11548/4','Miami EG','51','287','45','0','306','953','f','0.00','87.00','https://www.fragrantica.com/perfume/Paco-Rabanne/Lady-Million-Eau-My-Gold--24913.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1137','','','641609037','Victor Fresh','50','287','','0','306','954','m','0.00','62.00','https://www.fragrantica.com/perfume/Paco-Rabanne/Invictus-Aqua-2018--48760.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1138','','','641511025','Olympus Queen','50','287','','0','306','955','f','0.00','57.00','https://www.fragrantica.com/perfume/Paco-Rabanne/Olymp-a-31666.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1139','','','12192/4','SAM P','51','287','45','0','306','956','m','0.00','87.00','https://www.fragrantica.com/perfume/Paco-Rabanne/Pure-XS-46038.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1140','','','651609045','Marley','51','287','','0','307','957','m','0.00','62.00','https://www.fragrantica.com/perfume/Parfums-de-Marly/Ispazon-11770.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1141','','','11907/4','Itom','51','287','45','0','307','957','m','0.00','87.00','https://www.fragrantica.com/perfume/Parfums-de-Marly/Ispazon-11770.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1142','','','641603002','Meow Meow','50','287','','0','308','958','f','0.00','57.00','https://www.fragrantica.com/perfume/Miu-Miu/Miu-Miu-31468.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1143','','','641711021','Prado CO2','50','287','','0','309','959','m','0.00','62.00','https://www.fragrantica.com/perfume/Prada/Luna-Rossa-Carbon-43402.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1144','','','651609046','Sporty Luna','51','287','','0','309','960','m','0.00','60.00','https://www.fragrantica.com/perfume/Prada/Luna-Rossa-Sport-31116.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1145','','','11889/4','Lary S','51','287','45','0','309','960','m','0.00','87.00','https://www.fragrantica.com/perfume/Prada/Luna-Rossa-Sport-31116.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1146','','','8980/4/P','Blue Magic','51','287','','0','310','961','m','0.00','44.00','https://www.fragrantica.com/perfume/Ralph-Lauren/Polo-Blue-1198.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1147','','','50177','Pluton','51','287','','0','310','962','m','0.00','45.00','https://www.fragrantica.com/perfume/Ralph-Lauren/Polo-Sport-894.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1148','','','881403000','Sexy Love','48','287','','0','311','963','f','0.00','57.00','https://www.fragrantica.com/perfume/Sarah-Jessica-Parker/Lovely-993.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1149','','','99108003','Sexy City','49','287','','0','311','963','f','0.00','67.00','https://www.fragrantica.com/perfume/Sarah-Jessica-Parker/Lovely-993.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1150','','','12073/4','Paige','51','287','45','0','312','964','f','0.00','85.00','https://www.fragrantica.com/perfume/Stella-McCartney/Pop-36671.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1151','','','651410002','Velvet Ford','51','287','','0','313','965','f','0.00','67.00','https://www.fragrantica.com/perfume/Tom-Ford/Velvet-Orchid-22963.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1152','','','11490/4','Verone','51','287','45','0','313','965','f','0.00','87.00','https://www.fragrantica.com/perfume/Tom-Ford/Velvet-Orchid-22963.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1153','','','641604014','Medusa Sun','50','287','','0','314','966','f','0.00','62.00','https://www.fragrantica.com/perfume/Versace/Eros-Pour-Femme-28958.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1154','','','651702003','Lady VS','49','287','','0','315','967','f','0.00','70.00','https://www.fragrantica.com/perfume/Victoria-s-Secret/Bombshell-10190.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1155','','','991906021','Lady Scandal','49','287','45','0','315','968','f','0.00','87.00','https://www.fragrantica.com/perfume/Victoria-s-Secret/Scandalous-28256.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1156','','','641907024','Parisian Secret','50','287','','0','315','969','f','0.00','78.00','https://www.fragrantica.com/perfume/Victoria-s-Secret/Victoria-s-Secret-Paris-42343.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1157','','','641804003','Why Man','50','287','','0','316','970','m','0.00','62.00','https://www.fragrantica.com/perfume/Yves-Saint-Laurent/Y-Eau-de-Parfum-50757.html','inactive','sample','2019-08-07 15:38:10','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1158','','','11958/5','Maeva','51','287','45','0','316','971','f','0.00','87.00','https://www.fragrantica.com/perfume/Yves-Saint-Laurent/Mon-Paris-38914.html','inactive','sample','2019-08-07 15:38:11','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1159','','','611907034','Black Opus','51','287','45','0','316','972','f','0.00','88.00','https://www.fragrantica.com/perfume/Yves-Saint-Laurent/Black-Opium-25324.html','inactive','sample','2019-08-07 15:38:11','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1160','','','651410009','Wild Sara','51','287','','0','317','973','f','0.00','62.00','https://www.fragrantica.com/perfume/Zara/Wild-Rose-21465.html','inactive','sample','2019-08-07 15:38:11','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1161','','','11470/2','Wanda','51','287','45','0','317','973','f','0.00','87.00','https://www.fragrantica.com/perfume/Zara/Wild-Rose-21465.html','inactive','sample','2019-08-07 15:38:11','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1162','','','641907035','Chole Nomade','50','287','45','0','318','974','f','0.00','85.00','https://www.fragrantica.com/perfume/Chlo-/Nomade-48434.html','inactive','sample','2019-08-07 15:38:11','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1163','','','641907028','Street Fighter','50','287','45','0','319','975','m','0.00','78.00','https://www.fragrantica.com/perfume/Diesel/Only-The-Brave-Street-50761.html','inactive','sample','2019-08-07 15:38:11','','1','','0');
INSERT INTO `tbl_product` (`id`,`product_material_code`,`product_material_name`,`product_code`,`product_name`,`factory_id`,`brand_id`,`category_id`,`product_is_new`,`original_brand_id`,`searah_id`,`product_gender`,`product_cost_price`,`product_sell_price`,`product_web_image`,`product_status`,`product_type`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1164','','','641907029','Mylone Green ','50','287','','0','320','976','neutral','0.00','78.00','https://www.fragrantica.com/perfume/Jo-Malone-London/Green-Almond-Redcurrant-46197.html','inactive','sample','2019-08-07 15:38:11','','1','','0');



-- -------------------------------------------
-- TABLE DATA tbl_searah
-- -------------------------------------------
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('778','Team Force Black for men','1','','2019-08-07 15:38:08','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('779','Man in Charge for men','1','','2019-08-07 15:38:08','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('780','Aramis for men ','1','','2019-08-07 15:38:08','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('781','Chrome United for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('782','Azzaro Pour Homme for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('783','Juba Perfume Oil for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('784','Smoky Poppy for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('785','White Musk Smoky Rose for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('786','White Musk for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('787','Brut Faberge for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('788','Brut Musk for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('789','My Burberry for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('790','Weekend for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('791','Bvlgari Extreme for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('792','Bvlgari Rose Essentielle for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('793','Bvlgari Omnia Coral for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('794','Bvlgari Goldea for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('795','Amor -  Amor for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('796','CK Be for women and men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('797','CK One for women and men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('798','Contradiction for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('799','Escape for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('800','Eternity for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('801','Eternity for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('802','Forbidden Euphoria for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('803','212 Men ','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('804','212 Sexy CH for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('805','212 Sexy CH for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('806','J’adore for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('807','Fahrenheit for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('808','Dune for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('809','D&G Pour Homme Intenso for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('810','D&G Anthology L’imperatrice 3 for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('811','Echo Davidoff for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('812','Desire for a Man','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('813','Dunhill Edition for Men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('814','5th Avenue for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('815','Green Tea for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('816','Uomo Ermenegildo Zegna for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('817','Intuition for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('818','White Linen for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('819','Ferrari Uomo','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('820','Armani Code Sport for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('821','Gentlemen Only  for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('822','Organza for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('823','Envy Me for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('824','Guilty for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('825','Gucci Premiere for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('826','Eau De Gucci for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('827','Gucci Flora for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('828','Drakkar Noir for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('829','Terre D’Hermes for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('830','Hugo Dark Blue for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('831','Boss Bottled for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('832','Boss Bottled Unlimited for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('833','Boss in Motion for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('834','Hugo Red for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('835','Hugo Woman','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('836','L\'Eau d\'Issey Pour Homme for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('837','Pleats Please for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('838','Classic Black for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('839','Someday Justin Bieber for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('840','Killer Queen for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('841','Taylor for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('842','Connected Kenneth Cole Reaction for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('843','L’Eau Par Kenzo for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('844','Eau de Lacoste L.12.12 Red for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('845','Tresor Midnight Rose for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('846','Bergamote 22 for women and men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('847','Legend Montblanc for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('848','Toy Moschino for women and men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('849','Playboy London for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('850','Uomo Salvatore Ferragamo Casual Life for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('851','Middle East','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('852','Blue Emotion for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('853','Sauvage for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('854','Creation','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('855','Flight Of Fancy for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('856','Rock Me for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('857','Azzaro Chrome Pure for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('858','Azzaro Pour Elle for women','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('859','Azzaro Wanted for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('860','Bond no.9 The Scent of Peace for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('861','Burberry Mr. Burberry','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('862','Burberry’s for women (Classic 1995)','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('863','Burberry Brit for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('864','Aqva Amara for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('865','Aqva Pour Homme for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('866','Aqva Pour Homme Marine for men','1','','2019-08-07 15:38:09','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('867','Aqva Pour Homme Atlantiqve for Men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('868','Aqva Divina for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('869','Jasmine Noir for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('870','Omnia for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('871','CK All for women and men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('872','212 VIP Men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('873','212 VIP Rose for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('874','Good Girl for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('875','Pasha de Cartier  Edition Noire Sport for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('876','Bleu de Chanel for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('877','Chanel no 5 L\'Eau for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('878','Gabrielle for Women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('879','Miss Dior Blooming Bouquet for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('880','J’adore Lumiere for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('881','Higher Energy for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('882','Asian Green Tea for women and men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('883','Aventus for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('884','Aventus for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('885','Viking for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('886','Green Irish Tweed for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('887','Love in White for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('888','The Essence for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('889','Aqua Classic for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('890','Signature for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('891','Cool Water for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('892','Be Delicious - City Blossom - Rooftop Peony for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('893','Dolce for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('894','The One Sport for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('895','Desire Blue for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('896','Desire Black for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('897','Icon Elite for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('898','ST. Dupont Pour Homme for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('899','Elie Saab Le Parfum Resort Collection (2018) for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('900','Cherry In The Air for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('901','Agua Del Sol for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('902','Fiesta Carioca for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('903','Magnetisn for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('904','Moon Sparkle for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('905','Sexy Graffity for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('906','Modern Muse le Rouge for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('907','Woody Mystique for women and men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('908','Acqua di Gio for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('909','Acqua di Gio Profumo for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('910','Emporio Armani Stronger With You for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('911','Emporio Armani Because It\'s You for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('912','Armani Prive Rose d\'Arabie for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('913','Si Armani for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('914','Acqua di Gio Absolu for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('915','Play Sport for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('916','Le Interdit for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('917','Bamboo for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('918','Guilty for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('919','Guilty Black Pour Homme for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('920','L\'Homme Ideal for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('921','Mon for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('922','Bos in Motion for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('923','Boss in Motion Edition IV for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('924','Boss Nuit Intense Pour Femme for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('925','Boss Orange for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('926','Hugo Energise for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('927','Hugo Extreme for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('928','Hugo Iced for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('929','Hugo Just Different for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('930','Nuit D’Issey for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('931','L’eau d Issey for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('932','Still for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('933','Jaguar for men (botol chrome)','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('934','Man Intense for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('935','Kenzo Homme Sport for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('936','Kenzo Pour Homme for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('937','L’Eau Par Kenzo for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('938','Aqua Kenzo Pour Homme for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('939','Kenzo World for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('940','Joy of Pink for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('941','Eau de Lacoste L.12.12 Yellow for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('942','Lacoste L.12.12 Energized for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('943','Magnifique for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('944','Lolita Lempicka for Women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('945','Turbulences for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('946','Mercedes Benz for Men (Bulat)','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('947','Emblem for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('948','Legend Pour Femme for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('949','Legend Spirit for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('950','Black XS L’Excess for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('951','Black XS Los Angeles for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('952','Black XS Los Angeles for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('953','Lady Million Eau My Gold for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('954','Paco Rabanne Invictus Aqua (2018) for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('955','Paco Rabanne Olympea for Women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('956','Pure XS for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('957','Ispazon Parfums de Marly for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('958','Miu Miu for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('959','Luna Rossa Carbon for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('960','Luna Rossa Sport for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('961','Polo Blue for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('962','Polo Sport for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('963','Lovely Sarah Jesica Parker for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('964','Pop Stella McCartney for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('965','Velvet Orchid for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('966','Eros Pour Femme for women ','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('967','Bombshell for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('968','Scandalous for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('969','Paris for women','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('970','Y eau de parfum for men','1','','2019-08-07 15:38:10','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('971','Mon Paris for women','1','','2019-08-07 15:38:11','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('972','Black Opium for women','1','','2019-08-07 15:38:11','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('973','Wild Rose for women','1','','2019-08-07 15:38:11','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('974','Nomade for women','1','','2019-08-07 15:38:11','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('975','Only the Brave Street for men','1','','2019-08-07 15:38:11','','0');
INSERT INTO `tbl_searah` (`id`,`searah_name`,`created_by`,`updated_by`,`created_on`,`updated_on`,`is_deleted`) VALUES
('976','Green Almond and Red Currant for women and men','1','','2019-08-07 15:38:11','','0');



-- -------------------------------------------
-- TABLE DATA tbl_setting
-- -------------------------------------------
INSERT INTO `tbl_setting` (`id`,`setting_category_id`,`setting_label`,`setting_name`,`setting_value`,`setting_desc`,`setting_input_type`,`setting_input_size`,`setting_dropdown_options`) VALUES
('1','1','Banyak data per halaman','record_per_page','50','','text','small','');



-- -------------------------------------------
-- TABLE DATA tbl_setting_category
-- -------------------------------------------
INSERT INTO `tbl_setting_category` (`id`,`category_name`,`created_on`,`updated_on`,`created_by`,`updated_by`,`is_deleted`) VALUES
('1','Umum','2019-02-16 14:45:23','','1','','0');



-- -------------------------------------------
-- TABLE DATA user
-- -------------------------------------------
INSERT INTO `user` (`id`,`username`,`auth_key`,`password_hash`,`password_reset_token`,`email`,`status`,`created_at`,`updated_at`,`verification_token`) VALUES
('1','admin','2LfJWVbq31GjnTy6t5rxzUChHljBLCDW','$2y$13$uyuLD/wEKb3OwTz1YYpu9u8cUC/MWoUIUAG2pMMDbKtllscAyN0HO','','henry.gunawanasd.1986@gmail.com','10','1471087102','1559964721','');



-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
