# Create database structure
CREATE DATABASE IF NOT EXISTS `testwork`;
USE `testwork`;
CREATE TABLE IF NOT EXISTS `email`
(
    `i_id`   INT AUTO_INCREMENT,
    `m_mail` VARCHAR(255) NULL,
    CONSTRAINT `email_pk` PRIMARY KEY (`i_id`)
);
CREATE UNIQUE INDEX `email_m_mail_uidx_1` ON `email` (`m_mail`);

### YAGNI mode disabled =)
# Insert random emails in table
DROP PROCEDURE IF EXISTS `add_email`;
CREATE PROCEDURE `add_email`(IN count_email INT)
    LANGUAGE SQL
    DETERMINISTIC
    SQL SECURITY DEFINER
    COMMENT 'A procedure for inserting random email'

BEGIN
    DECLARE i INT DEFAULT (
        SELECT `i_id`
        FROM `email`
        ORDER BY `i_id` DESC
        LIMIT 1
    );
    IF i IS NULL
        THEN SET i = 0;
    END IF;

    SET count_email = count_email + i;

    WHILE i <= count_email DO
        SET @email = 'email_';
        SET @domen = '@email.ru'; # TODO: Make a list of existing and non-existing email. Enable random selection
        SET @random_str = substring(
            'АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ',
            rand(@seed:=round(rand() * 4294967296)) * 36 + 1, 3);

        IF i % 2 = 0
            THEN SET @email = CONCAT(@email, i, @random_str, '_');
        END IF;
        SET @email = CONCAT(@email, i, @domen);

        INSERT INTO `email` (`m_mail`) VALUES (@email);

        SET i = i + 1;
    END WHILE;
END;
CALL `add_email`(10000);
DROP PROCEDURE IF EXISTS `add_email`;
