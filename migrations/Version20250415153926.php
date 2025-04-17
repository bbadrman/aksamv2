<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415153926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE regelement ADD montant_reglement1 NUMERIC(10, 2) DEFAULT NULL, ADD montant_reglement2 NUMERIC(10, 2) DEFAULT NULL, ADD montant_reglement3 NUMERIC(10, 2) DEFAULT NULL, ADD date_reglement1 DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD date_reglement2 DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD date_reglement3 DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE regelement DROP montant_reglement1, DROP montant_reglement2, DROP montant_reglement3, DROP date_reglement1, DROP date_reglement2, DROP date_reglement3');
    }
}
