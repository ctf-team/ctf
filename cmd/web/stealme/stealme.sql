CREATE SCHEMA `stealme` ;
CREATE TABLE `stealme`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NULL,
  `password` VARCHAR(100) NULL,
  PRIMARY KEY (`id`));
CREATE TABLE `stealme`.`logins` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `ip` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `stealme_users_id_logins_user_id_idx` (`user_id` ASC),
  CONSTRAINT `stealme_users_id_logins_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `stealme`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE TABLE `stealme`.`tokens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(45) NOT NULL,
  `user_id` INT NOT NULL,
  `ip` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `stealme_users_id_tokens_user_id_idx` (`user_id` ASC),
  INDEX `stealme_logins_id_tokens_ip_id_idx` (`ip` ASC),
  CONSTRAINT `stealme_users_id_tokens_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `stealme`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `stealme_logins_id_tokens_ip_id`
    FOREIGN KEY (`ip`)
    REFERENCES `stealme`.`logins` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
