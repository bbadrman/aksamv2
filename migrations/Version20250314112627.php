<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314112627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compartenaire (id INT AUTO_INCREMENT NOT NULL, compagnie VARCHAR(255) DEFAULT NULL, partenaire VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE antcdent_assurance CHANGE nbr_mois nbr_mois INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contrat CHANGE date_souscrpt date_souscrpt DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE compartenaire');
        $this->addSql('ALTER TABLE antcdent_assurance CHANGE nbr_mois nbr_mois NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE contrat CHANGE date_souscrpt date_souscrpt VARCHAR(255) DEFAULT NULL');
    }
}
