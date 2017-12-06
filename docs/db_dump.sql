-- ----------------------------------------------------------------------------
-- MySQL Workbench Migration
-- Migrated Schemata: app_db
-- Source Schemata: app_db
-- Created: Sun Nov 12 03:55:27 2017
-- Workbench Version: 6.3.6
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- Schema app_db
-- ----------------------------------------------------------------------------
DROP SCHEMA IF EXISTS `app_db` ;
CREATE SCHEMA IF NOT EXISTS `app_db` ;

-- ----------------------------------------------------------------------------
-- Table app_db.addresses
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_db`.`addresses` (
  `fk_business_id` INT(11) NOT NULL,
  `street` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `province` VARCHAR(45) NOT NULL,
  INDEX `fk_addresses_1_idx` (`fk_business_id` ASC),
  CONSTRAINT `fk_addresses_1`
    FOREIGN KEY (`fk_business_id`)
    REFERENCES `app_db`.`businesses` (`business_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- ----------------------------------------------------------------------------
-- Table app_db.businesses
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_db`.`businesses` (
  `business_id` INT(11) NOT NULL AUTO_INCREMENT,
  `fk_user_id` INT(11) NOT NULL,
  `business_name` VARCHAR(50) NOT NULL,
  `is_verified` ENUM('YES', 'NO') NOT NULL DEFAULT 'NO',
  `date_created` DATETIME NOT NULL,
  `business_logo` VARCHAR(255) NULL DEFAULT NULL,
  `business_description` TEXT NULL DEFAULT NULL,
  `publish_business` ENUM('YES', 'NO') NOT NULL,
  `contact_qrcode` TEXT NOT NULL,
  PRIMARY KEY (`business_id`, `business_name`),
  INDEX `fk_businesses_1_idx` (`fk_user_id` ASC),
  CONSTRAINT `fk_businesses_1`
    FOREIGN KEY (`fk_user_id`)
    REFERENCES `app_db`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 24
DEFAULT CHARACTER SET = utf8mb4;

-- ----------------------------------------------------------------------------
-- Table app_db.contact_information
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_db`.`contact_information` (
  `fk_business_id` INT(11) NOT NULL,
  `contact_number_1` VARCHAR(20) NOT NULL,
  `contact_number_2` VARCHAR(20) NULL DEFAULT NULL,
  `contact_email` VARCHAR(255) NULL DEFAULT NULL,
  `website` VARCHAR(255) NULL DEFAULT NULL,
  `fax` VARCHAR(20) NULL DEFAULT NULL,
  INDEX `fk_contact_information_1_idx` (`fk_business_id` ASC),
  CONSTRAINT `fk_contact_information_1`
    FOREIGN KEY (`fk_business_id`)
    REFERENCES `app_db`.`businesses` (`business_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- ----------------------------------------------------------------------------
-- Table app_db.offering_categories
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_db`.`offering_categories` (
  `offering_category_id` INT(11) NOT NULL,
  `offering_category` VARCHAR(70) NOT NULL,
  `fk_offering_type` INT(11) NOT NULL,
  PRIMARY KEY (`offering_category_id`),
  INDEX `fk_offering_categories_1_idx` (`fk_offering_type` ASC),
  CONSTRAINT `fk_offering_categories_1`
    FOREIGN KEY (`fk_offering_type`)
    REFERENCES `app_db`.`offering_types` (`offering_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- ----------------------------------------------------------------------------
-- Table app_db.offering_images
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_db`.`offering_images` (
  `offering_images_id` INT(11) NOT NULL AUTO_INCREMENT,
  `offering_image` TEXT NOT NULL,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_offering_id` INT(11) NOT NULL,
  PRIMARY KEY (`offering_images_id`),
  INDEX `fk_offering_images_1_idx` (`fk_offering_id` ASC),
  CONSTRAINT `fk_offering_images_1`
    FOREIGN KEY (`fk_offering_id`)
    REFERENCES `app_db`.`offerings` (`offering_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- ----------------------------------------------------------------------------
-- Table app_db.offering_types
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_db`.`offering_types` (
  `offering_type_id` INT(11) NOT NULL,
  `offering_type` VARCHAR(70) NOT NULL,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`offering_type_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- ----------------------------------------------------------------------------
-- Table app_db.offerings
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_db`.`offerings` (
  `offering_id` INT(11) NOT NULL,
  `fk_business_id` INT(11) NULL DEFAULT NULL,
  `featured_image` VARCHAR(255) NULL DEFAULT NULL,
  `offering_name` VARCHAR(45) NULL DEFAULT NULL,
  `offering_cost` DOUBLE NULL DEFAULT NULL,
  `offering_description` TEXT NULL DEFAULT NULL,
  `tags` TEXT NULL DEFAULT NULL,
  `fk_offering_category_id` INT(11) NOT NULL,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`offering_id`),
  INDEX `fk_offerings_1_idx` (`fk_offering_category_id` ASC),
  INDEX `fk_offerings_2_idx` (`fk_business_id` ASC),
  CONSTRAINT `fk_offerings_1`
    FOREIGN KEY (`fk_offering_category_id`)
    REFERENCES `app_db`.`offering_categories` (`offering_category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_offerings_2`
    FOREIGN KEY (`fk_business_id`)
    REFERENCES `app_db`.`businesses` (`business_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- ----------------------------------------------------------------------------
-- Table app_db.users
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_db`.`users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `salt` VARCHAR(255) NOT NULL,
  `user_type` ENUM('ADMIN', 'BUSINESS') NOT NULL,
  `date_created` DATETIME NOT NULL,
  `account_verified` ENUM('YES', 'NO') NULL DEFAULT 'NO',
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `gender` ENUM('Male', 'Female') NOT NULL,
  PRIMARY KEY (`user_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4;
SET FOREIGN_KEY_CHECKS = 1;