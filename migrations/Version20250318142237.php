<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318142237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sinistre DROP FOREIGN KEY FK_F5AC7A67EF3C8AF0');
        $this->addSql('DROP TABLE sinistre');
        $this->addSql('ALTER TABLE compartenaire ADD nom VARCHAR(255) DEFAULT NULL, DROP compagnie, DROP partenaire');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sinistre (id INT AUTO_INCREMENT NOT NULL, antcdent_assurance_id INT DEFAULT NULL, type_sinistre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, responsable VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, pourcentresp VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_F5AC7A67EF3C8AF0 (antcdent_assurance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sinistre ADD CONSTRAINT FK_F5AC7A67EF3C8AF0 FOREIGN KEY (antcdent_assurance_id) REFERENCES antcdent_assurance (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE compartenaire ADD partenaire VARCHAR(255) DEFAULT NULL, CHANGE nom compagnie VARCHAR(255) DEFAULT NULL');
    }
}
