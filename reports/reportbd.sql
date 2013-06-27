SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `trc` ;
CREATE SCHEMA IF NOT EXISTS `trc` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `trc` ;

-- -----------------------------------------------------
-- Table `trc`.`artista`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trc`.`artista` ;

CREATE  TABLE IF NOT EXISTS `trc`.`artista` (
  `idartista` INT NOT NULL ,
  `nombre` VARCHAR(150) NULL ,
  PRIMARY KEY (`idartista`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trc`.`registroItunes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trc`.`registroItunes` ;

CREATE  TABLE IF NOT EXISTS `trc`.`registroItunes` (
  `idregistroItunes` INT NOT NULL AUTO_INCREMENT ,
  `labelStudioNetwork` VARCHAR(45) NULL ,
  `chanel` VARCHAR(150) NULL ,
  `countryCode` VARCHAR(3) NULL ,
  `upc` VARCHAR(45) NULL ,
  `isr` VARCHAR(45) NULL ,
  `artistShow` VARCHAR(500) NULL ,
  `registroItunescol` VARCHAR(500) NULL ,
  `title` VARCHAR(45) NULL ,
  `productTypeIdentifier` VARCHAR(45) NULL ,
  `units` INT NULL ,
  `orderId` VARCHAR(45) NULL ,
  `royaltyPrice` FLOAT NULL ,
  `postalCode` VARCHAR(6) NULL ,
  `reportDate` VARCHAR(45) NULL ,
  `saleReturn` VARCHAR(1) NULL ,
  `customerCurrency` VARCHAR(4) NULL ,
  `royaltyCurrency` VARCHAR(45) NULL ,
  `preOrder` VARCHAR(10) NULL ,
  `customerPrice` VARCHAR(45) NULL ,
  `cma` VARCHAR(45) NULL ,
  PRIMARY KEY (`idregistroItunes`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trc`.`registroSpotify`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trc`.`registroSpotify` ;

CREATE  TABLE IF NOT EXISTS `trc`.`registroSpotify` (
  `idregistroSpotify` INT NOT NULL AUTO_INCREMENT ,
  `startDate` DATE NULL ,
  `endDate` DATE NULL ,
  `label` VARCHAR(100) NULL ,
  `partner` VARCHAR(100) NULL ,
  `product` VARCHAR(500) NULL ,
  `country` VARCHAR(3) NULL ,
  `currency` VARCHAR(3) NULL ,
  `payableUsd` FLOAT NULL ,
  PRIMARY KEY (`idregistroSpotify`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trc`.`registroAmazon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trc`.`registroAmazon` ;

CREATE  TABLE IF NOT EXISTS `trc`.`registroAmazon` (
  `idregistroAmazon` INT NOT NULL AUTO_INCREMENT ,
  `saleDate` DATE NULL ,
  `titleName` VARCHAR(500) NULL ,
  `trackName` VARCHAR(500) NULL ,
  `productType` VARCHAR(45) NULL ,
  `salesChannel` VARCHAR(45) NULL ,
  `upcIsbn` VARCHAR(20) NULL ,
  `quantity` INT NULL ,
  `royalty` FLOAT NULL ,
  PRIMARY KEY (`idregistroAmazon`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trc`.`registroQobuz`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trc`.`registroQobuz` ;

CREATE  TABLE IF NOT EXISTS `trc`.`registroQobuz` (
  `idregistroQobuz` INT NOT NULL AUTO_INCREMENT ,
  `retailIdentifier` VARCHAR(45) NULL ,
  PRIMARY KEY (`idregistroQobuz`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trc`.`registroYouTube`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trc`.`registroYouTube` ;

CREATE  TABLE IF NOT EXISTS `trc`.`registroYouTube` (
  `idregistroYouTube` INT NOT NULL AUTO_INCREMENT ,
  `videoId` VARCHAR(45) NULL ,
  `day` DATE NULL ,
  `country` VARCHAR(45) NULL ,
  `contentType` VARCHAR(150) NULL ,
  `contentPolicy` VARCHAR(150) NULL ,
  `embedViews` INT NULL ,
  `watchViews` INT NULL ,
  `youtubeSoldRevenue` FLOAT NULL ,
  `partnerSoldRevenue` INT NULL ,
  `afvRevenue` INT NULL ,
  `amountPayable` FLOAT NULL ,
  `customId` VARCHAR(45) NULL ,
  PRIMARY KEY (`idregistroYouTube`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trc`.`registros`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trc`.`registros` ;

CREATE  TABLE IF NOT EXISTS `trc`.`registros` (
  `idregistros` INT NOT NULL AUTO_INCREMENT ,
  `tipoRegistro` VARCHAR(150) NOT NULL ,
  `Idreferencia` INT NULL COMMENT 'Este campo puede\nser upc-isrc-isbn ' ,
  `channel` VARCHAR(150) NULL ,
  `label` VARCHAR(500) NULL ,
  `country` VARCHAR(4) NULL ,
  `city` VARCHAR(45) NULL ,
  `artist` VARCHAR(500) NULL ,
  `album` VARCHAR(150) NULL ,
  `trackVideoTitle` VARCHAR(150) NULL ,
  `upc` VARCHAR(45) NULL ,
  `isrc` VARCHAR(45) NULL ,
  `reportDate` DATE NULL ,
  `units` INT NULL ,
  `saleReturn` VARCHAR(45) NULL ,
  `customerPrice` FLOAT NULL ,
  `cmaDiscount` FLOAT NULL ,
  `royaltyPrice` FLOAT NULL ,
  `royaltyCurrency` FLOAT NULL ,
  `royaltyEuros` FLOAT NULL ,
  `watchViews` INT NULL ,
  `embedViews` INT NULL ,
  PRIMARY KEY (`idregistros`) ,
  UNIQUE INDEX `Idreferencia_UNIQUE` (`Idreferencia` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
