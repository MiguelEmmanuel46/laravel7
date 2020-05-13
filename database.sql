
CREATE SCHEMA IF NOT EXISTS `casa` DEFAULT CHARACTER SET utf8 ;
USE `casa` ;




CREATE TABLE IF NOT EXISTS `casa`.`inventario` (
  `id_producto` INT NOT NULL AUTO_INCREMENT,
  `nombre_producto` VARCHAR(255) NULL,
  `precio_compra` DECIMAL(18,2) NULL,
  `precio_venta` DECIMAL(18,2) NULL,
  `existencia` INT NULL,
  PRIMARY KEY (`id_producto`))
ENGINE = InnoDB;





CREATE TABLE IF NOT EXISTS `casa`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NULL,
  `surname` VARCHAR(100) NULL,
   `role` VARCHAR(20) NULL,
  `email` VARCHAR(255) NULL,
  `password` VARCHAR(255) NULL,
  `description` TEXT NULL,
   `image` VARCHAR(255) NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `remember_token` VARCHAR(255) NULL, 
  PRIMARY KEY (`id`))
ENGINE = InnoDB;





CREATE TABLE IF NOT EXISTS `casa`.`ventas` (
  `idventas` INT NOT NULL AUTO_INCREMENT,
  `id_producto` INT NULL,
  `cantidad` INT NULL,
  `total` DECIMAL(18,2) NULL,
  `fecha` DATE NULL,
  `momento_venta` TIMESTAMP NULL,
  `id_usuario` INT NULL,
  PRIMARY KEY (`idventas`),
    FOREIGN KEY (`id_producto`)  REFERENCES `casa`.`inventario` (`id_producto`),
    FOREIGN KEY (`id_usuario`)  REFERENCES `casa`.`users` (`id`))
ENGINE = InnoDB;






CREATE TABLE IF NOT EXISTS `casa`.`caja` (
  `idcaja` INT NOT NULL AUTO_INCREMENT,
  `monto_inicial` DECIMAL(18,2) NULL,
  `total_venta` DECIMAL(18,2) NULL,
  `ganacia` DECIMAL(18,2) NULL,
  `fecha` DATETIME NULL,
  `hora_registro` TIMESTAMP NULL,
  `id_usuario` INT NULL,
  PRIMARY KEY (`idcaja`),
     FOREIGN KEY (`id_usuario`)
    REFERENCES `casa`.`users` (`id`)
   )
ENGINE = InnoDB;






CREATE TABLE IF NOT EXISTS `casa`.`dinero_diario` (
  `id_dinero_diario` INT NOT NULL AUTO_INCREMENT,
  `total_dinero_diario` DECIMAL(18,2) NULL,
  `fecha` DATE NULL,
  `fecha_captura` TIMESTAMP NULL,
  `id_usuario` INT NULL,
  PRIMARY KEY (`id_dinero_diario`),
  
    FOREIGN KEY (`id_usuario`)
    REFERENCES `casa`.`users` (`id`)
    )
ENGINE = InnoDB;

