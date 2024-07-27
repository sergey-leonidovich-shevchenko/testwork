<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240726204036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create coupon table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE coupon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE coupon (id INT NOT NULL, code VARCHAR(255) NOT NULL, discount_amount NUMERIC(10, 2) DEFAULT NULL, discount_percentage NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE coupon_id_seq CASCADE');
        $this->addSql('DROP TABLE coupon');
    }
}
