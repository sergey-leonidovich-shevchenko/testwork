# Предположим, что у Вас есть база пользователей с заполненными данными в 3-х таблицах вида:
# - user (i_id int auto increment, s_login varchar(255))
# - user_field (i_id int auto increment, s_field_path varchar(255))
# - user_field_value (i_fld_id int, i_user_id int, t_value text) primary key (i_fld_id, i_user_id)
# Допустим в i_fld_id=1 хранится дата рождения в формате yyyy-mm-dd (например: 1980-12-26, 1920-07-16 и т.д.)
# Требуется отфильтровать пользователей со знаком зодиака «козерог»:

# Create database structure
CREATE DATABASE IF NOT EXISTS `testwork`;
USE `testwork`;

CREATE TABLE IF NOT EXISTS `horoscope` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `date_start` VARCHAR(5),
    `date_end` VARCHAR(5)
);
CREATE INDEX `horoscope_idx_1` ON `horoscope`(`date_start`, `date_end`);

CREATE TABLE IF NOT EXISTS `user` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `birthday` DATE NOT NULL
);

### YAGNI mode disabled =)
# Insert horoscope in table
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Aries', '03-21', '04-20');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Taurus', '04-21', '05-20');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Gemini', '05-22', '06-21');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Cancer', '06-22', '07-22');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Leo', '07-23', '08-23');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Virgin', '08-24', '09-22');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Libra', '08-23', '10-22');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Scorpio', '10-23', '11-21');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Sagittarius', '11-22', '12-21');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Capricorn', '12-22', '01-20');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Aquarius', '01-21', '02-19');
INSERT INTO `horoscope` (`name`, `date_start`, `date_end`) VALUES ('Pisces', '02-20', '03-20');


# Insert random user in table
DROP PROCEDURE IF EXISTS `add_user`;
CREATE PROCEDURE `add_user`(IN `count_user` INT)
    LANGUAGE SQL
    DETERMINISTIC
    SQL SECURITY DEFINER
    COMMENT 'A procedure for inserting random user'

BEGIN
    DECLARE i INT DEFAULT (
        SELECT `id`
        FROM `user`
        ORDER BY `id` DESC
        LIMIT 1
    );
    IF i IS NULL
        THEN SET i = 1;
    END IF;

    SET `count_user` = `count_user` + i;

    WHILE i <= `count_user` DO
        SET @`name` = CONCAT('user_', i);
        SET @`user_birth` = '1980-01-01' + INTERVAL (RAND() * 365 * 20) DAY;

        INSERT INTO `user` (`name`, `birthday`) VALUES (@`name`, @`user_birth`);

        SET i = i + 1;
    END WHILE;
END;
CALL `add_user`(1000);
DROP PROCEDURE IF EXISTS `add_user`;
### YAGNI mode enabled =)

# Only Capricorn
#EXPLAIN
SELECT `u`.`id`, `u`.`name`, `u`.`birthday`
FROM `user` AS `u`
WHERE (
    DATE_FORMAT(`u`.`birthday`, '%m%d') >= (
        SELECT
            CONCAT(LEFT(`h`.`date_start`, 2), RIGHT(`h`.`date_start`, 2))
        FROM `horoscope` AS `h`
        WHERE `h`.`name` = 'Capricorn'
    ) AND DATE_FORMAT(`u`.`birthday`, '%m%d') <= 1231
) OR (
    DATE_FORMAT(`u`.`birthday`, '%m%d') >= 101 AND DATE_FORMAT(`u`.`birthday`, '%m%d') <= (
        SELECT
            CONCAT(LEFT(`h`.`date_end`, 2), RIGHT(`h`.`date_end`, 2))
        FROM `horoscope` AS `h`
        WHERE `h`.`name` = 'Capricorn'
    )
);

# Optimized query

ALTER TABLE `user`
    ADD COLUMN `birthmonthday` VARCHAR(5) AS (substring(`birthday`, 6, 5));
ALTER TABLE `user`
    ADD KEY (`birthmonthday`);
#EXPLAIN
SELECT `user`.`id`, `user`.`name`, `user`.`birthday`
FROM `horoscope` `h`
    INNER JOIN `user` ON `birthmonthday` BETWEEN `date_start` AND `date_end`
        OR (`date_start` > `date_end` AND `birthmonthday` BETWEEN `date_start` AND '12-31')
        OR (`date_start` > `date_end` AND `birthmonthday` BETWEEN '01-01' AND `date_end`)
WHERE `h`.`name` = 'Capricorn';
