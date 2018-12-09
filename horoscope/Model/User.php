<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 2018-12-09
 * Time: 05:11
 */

class User extends DB
{
    /**
     * Get users with horoscope Capricorn
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getUserWithHoroscopeCapricorn(int $limit = 20, int $offset = 0): array
    {
        $query = "
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
            )
            ORDER BY `u`.`id` ASC
            LIMIT {$offset}, {$limit};
        ";

        return $this->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get count users with horoscope Capricorn
     * @param int $limit
     * @param int $offset
     * @return int
     */
    public function getCountUserWithHoroscopeCapricorn(int $limit = 20, int $offset = 0): array
    {
        $query = "
            SELECT COUNT(`u`.`id`) AS count
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
            )
            LIMIT {$offset}, {$limit};
        ";

        return $this->pdo->query($query)->fetch(PDO::FETCH_ASSOC);
    }
}