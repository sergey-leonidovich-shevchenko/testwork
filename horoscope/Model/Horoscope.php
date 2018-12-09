<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 2018-12-09
 * Time: 05:11
 */

class Horoscope extends DB
{
    /**
     * Get all horoscope
     * @param array $fields
     * @param int $fetchStyle
     * @return array
     */
    public function getAll(array $fields, int $fetchStyle = PDO::FETCH_OBJ): array
    {
        $select = implode(', ', $fields);
        $query = "SELECT {$select} FROM `horoscope`";
        return $this->pdo->query($query)->fetchAll($fetchStyle);
    }
}