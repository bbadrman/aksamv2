<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530103107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE relance_sav (id INT AUTO_INCREMENT NOT NULL, sav_id INT DEFAULT NULL, creat_at DATETIME DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, motif_relance VARCHAR(255) DEFAULT NULL, INDEX IDX_5873B21C4F726353 (sav_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE relance_sav ADD CONSTRAINT FK_5873B21C4F726353 FOREIGN KEY (sav_id) REFERENCES sav (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE relance_sav DROP FOREIGN KEY FK_5873B21C4F726353');
        $this->addSql('DROP TABLE relance_sav');
    }
}
