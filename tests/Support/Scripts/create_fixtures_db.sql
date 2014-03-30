SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE IF NOT EXISTS `country` (
  `id_country` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `iso2_code` VARCHAR(2) NOT NULL,
  `iso3_code` VARCHAR(3) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_country`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `country_region` (
  `id_country_region` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_country` INT(10) UNSIGNED NOT NULL,
  `code` VARCHAR(32) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `sort` INT(10) DEFAULT NULL,
  PRIMARY KEY (`id_country_region`),
  KEY `fk_country` (`fk_country`),
  CONSTRAINT `country_region__fk_country` FOREIGN KEY (`fk_country`) REFERENCES `country` (`id_country`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `currency_conversion` (
  `id_currency_conversion` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `currency` VARCHAR(4) NOT NULL,
  `rate` DECIMAL(9,6) NOT NULL,
  PRIMARY KEY (`id_currency_conversion`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `customer_address_region_suburb` (
  `id_customer_address_region_suburb` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_customer_address_region` INT(10) UNSIGNED NOT NULL,
  `postcode` VARCHAR(4) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `delivery_office` VARCHAR(255) DEFAULT NULL,
  `parcel_zone` VARCHAR(255) DEFAULT NULL,
  `bsp_name` VARCHAR(255) DEFAULT NULL,
  `category` VARCHAR(255) DEFAULT NULL,
  `sort` INT(10) DEFAULT NULL,
  PRIMARY KEY (`id_customer_address_region_suburb`),
  KEY `fk_region_country_suburb` (`fk_customer_address_region`),
  CONSTRAINT `fk_region_country_suburb` FOREIGN KEY (`fk_customer_address_region`) REFERENCES `customer_address_region` (`id_customer_address_region`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE VIEW test_view AS SELECT * FROM country;
SET FOREIGN_KEY_CHECKS = 1;
