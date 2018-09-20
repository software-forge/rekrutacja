CREATE DATABASE `portal`;

CREATE TABLE `portal`.`users`
            ( 
                `user_id` INT NOT NULL AUTO_INCREMENT ,
                `nick` TEXT NOT NULL ,
                `password_hash` TEXT NOT NULL ,
                `email` TEXT NOT NULL ,
                `is_active` BOOLEAN NOT NULL ,
                PRIMARY KEY (`user_id`)
            ) ENGINE = InnoDB;