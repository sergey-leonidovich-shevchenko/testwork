<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 2018-12-07
 * Time: 23:23
 */

/**
 * Class DB
 * @package Model
 */
class DB
{
    private const DB_DRIVER = 'mysql';
    private const DB_HOST = '127.0.0.1';
    private const DB_USER = 'root';
    private const DB_PASS = 'lancer52662699';
    private const DB_CHARSET = 'UTF8';
    private const DB_NAME = 'testwork';

    protected $pdo;

    /**
     * DB constructor.
     */
    public function __construct()
    {
        $dsn = sprintf(
            '%s:host=%s;dbname=%s;charset=%s',
            self::DB_DRIVER,
            self::DB_HOST,
            self::DB_NAME,
            self::DB_CHARSET
        );

        $this->pdo = new PDO($dsn, self::DB_USER, self::DB_PASS);
    }

    /**
     * DB destructor.
     */
    public function __destruct()
    {
        $this->pdo = null;
    }
}
