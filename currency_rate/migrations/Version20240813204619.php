<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240813204619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the currency_rate table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE currency_rate_currency_code_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE currency_rate (currency_code VARCHAR(3) NOT NULL, rate NUMERIC(15, 6) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(currency_code))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE currency_rate_currency_code_seq CASCADE');
        $this->addSql('DROP TABLE currency_rate');
    }
}
