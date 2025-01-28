<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128132921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prospect (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, brith_at DATETIME DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, type_prospect VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, code_post VARCHAR(255) DEFAULT NULL, gsm VARCHAR(255) DEFAULT NULL, assurer VARCHAR(255) DEFAULT NULL, last_assure VARCHAR(255) DEFAULT NULL, motif_resil VARCHAR(255) DEFAULT NULL, motif_saise VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, secd_email VARCHAR(255) DEFAULT NULL, creat_at DATETIME DEFAULT NULL, activites VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE prospect');
    }
}
