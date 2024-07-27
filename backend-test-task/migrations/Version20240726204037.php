<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240726204037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tax_country table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE tax_country_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tax_country (id INT NOT NULL, country VARCHAR(255) NOT NULL, tax NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE tax_country_id_seq CASCADE');
        $this->addSql('DROP TABLE tax_country');
    }
}
