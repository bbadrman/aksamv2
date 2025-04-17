<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416081124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD transaction1 VARCHAR(255) DEFAULT NULL, ADD transaction2 VARCHAR(255) DEFAULT NULL, ADD transaction3 VARCHAR(255) DEFAULT NULL, ADD date_payment DATETIME DEFAULT NULL, ADD date_payment1 DATETIME DEFAULT NULL, ADD dateÂpayment2 DATETIME DEFAULT NULL, ADD date_payment3 DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP transaction1, DROP transaction2, DROP transaction3, DROP date_payment, DROP date_payment1, DROP dateÂpayment2, DROP date_payment3');
    }
}
