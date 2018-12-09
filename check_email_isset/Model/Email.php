<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 2018-12-07
 * Time: 23:23
 */

/**
 * Class Email
 * @package Model
 */
class Email extends DB
{
    /**
     * RegExp pattern to email
     * @var string
     */
    private $patternEmail = '/^[0-9A-z_.-]+@[0-9A-z_.-]+\.[0-9A-z_.-]+$/';
    private $patternDomain = '/[0-9A-z_.-]+\.[0-9A-z_.-]+$/';

    /**
     * Get generator with email list
     * @return false|PDOStatement
     */
    public function getEmail(): ?PDOStatement
    {
        $query = 'SELECT `i_id` AS `id`, `m_mail` AS `email` FROM email';
        return $this->pdo->query($query);
    }

    /**
     * Check valid email
     * @return bool
     */
    public function checkValidEmail(string $email): bool
    {
        return (bool)preg_match($this->patternEmail, $email);
    }

    /**
     * Get domain by email
     * @param $email
     * @return string|null
     */
    public function getDomainByEmail(string $email): ?string
    {
        preg_match($this->patternDomain, $email, $match);
        return $match[0];
    }
}