<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128150329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, date_souscrpt VARCHAR(255) DEFAULT NULL, date_effet DATETIME DEFAULT NULL, type_contrat VARCHAR(255) DEFAULT NULL, activite VARCHAR(255) DEFAULT NULL, imatriclt VARCHAR(255) DEFAULT NULL, partenaire VARCHAR(255) DEFAULT NULL, compagnie VARCHAR(255) DEFAULT NULL, formule VARCHAR(255) DEFAULT NULL, fraction VARCHAR(255) DEFAULT NULL, frais NUMERIC(10, 2) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, type_conducteur VARCHAR(255) DEFAULT NULL, conducteur VARCHAR(255) DEFAULT NULL, date_permis DATETIME DEFAULT NULL, status SMALLINT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, cotisation NUMERIC(10, 2) DEFAULT NULL, acompte NUMERIC(10, 2) DEFAULT NULL, first_reglement NUMERIC(10, 2) NOT NULL, second_reglement NUMERIC(10, 2) DEFAULT NULL, jour_prelvm VARCHAR(255) DEFAULT NULL, type_product VARCHAR(255) DEFAULT NULL, is_modif TINYINT(1) DEFAULT NULL, date_preleve_acompte DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contrat');
    }
}
