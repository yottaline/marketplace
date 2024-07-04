CREATE TABLE IF NOT EXISTS
  `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_code` VARCHAR(8) NOT NULL,
    `user_name` VARCHAR(120) NOT NULL,
    `user_email` VARCHAR(120) NOT NULL,
    `user_password` VARCHAR(255) NOT NULL,
    `user_type` INT UNSIGNED NOT NULL COMMENT '1 : admin, 2 : retailer, 3 : customer',
    `user_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE (`user_code`),
    UNIQUE (`user_email`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `admins` (
    `admin_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `admin_user` INT UNSIGNED NOT NULL,
    `admin_status` BOOLEAN NOT NULL DEFAULT '1',
    PRIMARY KEY (`admin_id`),
    FOREIGN KEY (`admin_user`) REFERENCES `users` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `retailers` (
    `retailer_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `retailer_user` INT UNSIGNED NOT NULL,
    `retailer_phone` VARCHAR(24) NOT NULL,
    `retailer_store` VARCHAR(120) NULL,
    `retailer_loge` VARCHAR(64) NULL,
    `retailer_mobile` VARCHAR(24) NULL,
    `retailer_note` VARCHAR(1024) DEFAULT NULL,
    `retailer_address` VARCHAR(255) NOT NULL,
    `retailer_vat`BOOLEAN NOT NULL DEFAULT '0',
    `retailer_status` BOOLEAN NOT NULL DEFAULT '1',
    `retailer_approved` DATETIME DEFAULT NULL,
    `retailer_approved_by` INT UNSIGNED DEFAULT NULL,
    `retailer_login` DATETIME DEFAULT NULL,
    PRIMARY KEY (`retailer_id`),
    FOREIGN KEY (`retailer_user`) REFERENCES `users` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
  
-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `customers` (
    `customer_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `customer_user` INT UNSIGNED NOT NULL,
    `customer_phone` VARCHAR(24) NOT NULL,
    `customer_note` VARCHAR(1024) DEFAULT NULL,
    `customer_address` VARCHAR(255) NOT NULL,
    `customer_status` BOOLEAN NOT NULL DEFAULT '1',
    PRIMARY KEY (`customer_id`),
    FOREIGN KEY (`customer_user`) REFERENCES `users` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
  
-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `brands` (
    `brand_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `brand_name` VARCHAR(120) NOT NULL,
    `brand_status` BOOLEAN NOT NULL DEFAULT '1',
    PRIMARY KEY (`brand_id`),
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
  
 -- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `categories` (
    `category_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_name` VARCHAR(120) NOT NULL,
    `category_brand` INT UNSIGNED NOT NULL,
    `category_status` BOOLEAN NOT NULL DEFAULT '1',
    PRIMARY KEY (`category_id`),
    FOREIGN KEY (`category_brand`) REFERENCES `brands` (`brand_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
  
-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `subcategories` (
    `subcategory_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `subcategory_name` VARCHAR(120) NOT NULL,
    `subcategory_category` INT UNSIGNED NOT NULL,
    `subcategory_status` BOOLEAN NOT NULL DEFAULT '1',
    PRIMARY KEY (`subcategory_id`),
    FOREIGN KEY (`subcategory_category`) REFERENCES `categories` (`category_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
  
-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `sizes` (
    `size_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `size_name` VARCHAR(120) NOT NULL,
    `size_subcategory` INT UNSIGNED NOT NULL,
    `size_sign` VARCHAR(12) DEFAULT  NULL,
    `size_status` BOOLEAN NOT NULL DEFAULT '1',
    PRIMARY KEY (`brand_id`),
    FOREIGN KEY (`size_subcategory`) REFERENCES `subcategories` (`subcategory_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
  
 -- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `products` (
    `product_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_code` VARCHAR(15) NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `product_desc` VARCHAR(1024) DEFAULT NULL,
    `product_category` INT UNSIGNED NOT NULL,
    `product_subcategory` INT UNSIGNED NOT NULL,
    `product_created_by` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`product_id`),
    UNIQUE (`product_code`),
    FOREIGN KEY (`product_category`) REFERENCES `categories` (`category_id`),
    FOREIGN KEY (`product_subcategory`) REFERENCES `subcategories` (`subcategory_id`),
    FOREIGN KEY (`product_created_by`) REFERENCES `retailers` (`retailer_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------
CREATE TABLE IF NOT EXISTS
  `products_color` (
    `prodcolor_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `prodcolor_product` INT UNSIGNED NOT NULL,
    `prodcolor_name` VARCHAR(120) DEFAULT NULL,
    `prodcolor_code` VARCHAR(8) NOT NULL,
    `prodcolor_media` VARCHAR(64) NULL,
    `prodcolor_minqty` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'MIN ORDER QUANTITY',
    `prodcolor_maxqty` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'MAX ORDER QUANTITY',
    `prodcolor_status` BOOLEAN NOT NULL DEFAULT '1'
    PRIMARY KEY (`prodcolor_id`),
    FOREIGN KEY (`prodcolor_product`) REFERENCES `products` (`product_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

  -- --------------------------------------
  CREATE TABLE IF NOT EXISTS
  `products_sizes` (
    `prodsize_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `prodsize_product` INT UNSIGNED NOT NULL,
    `prodsize_size` INT UNSIGNED NOT NULL,
    `prodsize_code` VARCHAR(8) NOT NULL,
    `prodsize_cost` DECIMAL(9, 2) DEFAULT '0.00',
    `prodsize_sellprice` DECIMAL(9, 2) DEFAULT '0.00',
    `prodsize_price` DECIMAL(9, 2) DEFAULT '0.00',
    `prodsize_qty` INT UNSIGNED NOT NULL DEFAULT '0',
    `prodsize_discount` TINYINT UNSIGNED NOT NULL DEFAULT '0',
    `prodsize_discount_start` DATETIME NULL DEFAULT,
    `prodsize_discount_end` DATETIME NULL DEFAULTl
    `prodsize_status` BOOLEAN NOT NULL DEFAULT '1'
    `prodsize_created_by` INT UNSIGNED NOT NULL,
    `prodsize_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`prodsize_id`),
    FOREIGN KEY (`prodsize_product`) REFERENCES `products` (`product_id`),
    FOREIGN KEY (`prodsize_created_by`) REFERENCES `retailers` (`retailer_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

  -- --------------------------------------
  CREATE TABLE IF NOT EXISTS
 `orders` (
    `order_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_code` VARCHAR(12) NOT NULL,
    `order_customer` INT UNSIGNED NOT NULL,
    `order_tax` DECIMAL(9, 2) NOT NULL DEFAULT '0.00',
    `order_subtotal` DECIMAL(9, 2) NOT NULL,
    `order_discount` TINYINT UNSIGNED NOT NULL DEFAULT '0',
    `order_total` DECIMAL(9, 2) NOT NULL,
    `order_status` TINYINT UNSIGNED NOT NULL DEFAULT '1' COMMENT '1:DRAFT, 2:CANCELED, 3:PLACED, 4:APPROVED, 5:DELIVERED',
    `order_note` VARCHAR(1024) DEFAULT NULL,
    `order_modified` DATETIME DEFAULT NULL,
    `order_exec` DATETIME DEFAULT NULL,
    `order_approved` DATETIME DEFAULT NULL,
    `order_delivered` DATETIME DEFAULT NULL,
    `order_create_by` INT UNSIGNED DEFAULT NULL,
    `order_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`order_id`),
    UNIQUE (`order_code`),
    FOREIGN KEY (`order_customer`) REFERENCES `customers` (`customer_id`),
    FOREIGN KEY (`order_create_by`) REFERENCES `retailers` (`retailer_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

  -- --------------------------------------

CREATE TABLE `order_items` (
  `orderItem_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `orderItem_order` INT UNSIGNED NOT NULL,
  `orderItem_product` INT UNSIGNED NOT NULL,
  `orderItem_size` INT UNSIGNED NOT NULL,
  `orderItem_productPrice` DECIMAL(12,2) NOT NULL COMMENT 'product price',
  `orderItem_subtotal` DECIMAL(12, 2) NOT NULL,
  `orderItem_qty` INT UNSIGNED NOT NULL,
  `orderItem_disc` DECIMAL(6,2) NOT NULL,
  `orderItem_total` DECIMAL(12, 2) NOT NULL,
  PRIMARY KEY (`orderItem_id`),
  FOREIGN KEY (`orderItem_order`) REFERENCES `orders` (`orders_id`),
  FOREIGN KEY (`orderItem_product`) REFERENCES `products` (`product_id`),
  FOREIGN KEY (`orderItem_size`) REFERENCES `products_sizes` (`prodsize_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;