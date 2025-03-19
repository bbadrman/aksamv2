<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250317151621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sinistre (id INT AUTO_INCREMENT NOT NULL, antcdent_assurance_id INT DEFAULT NULL, type_sinistre VARCHAR(255) DEFAULT NULL, responsable VARCHAR(255) DEFAULT NULL, pourcentresp VARCHAR(255) DEFAULT NULL, INDEX IDX_F5AC7A67EF3C8AF0 (antcdent_assurance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sinistre ADD CONSTRAINT FK_F5AC7A67EF3C8AF0 FOREIGN KEY (antcdent_assurance_id) REFERENCES antcdent_assurance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sinistre DROP FOREIGN KEY FK_F5AC7A67EF3C8AF0');
        $this->addSql('DROP TABLE sinistre');
    }
}
