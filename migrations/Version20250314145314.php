<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314145314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat ADD compagnie_id INT DEFAULT NULL, ADD partenaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_6034999352FBE437 FOREIGN KEY (compagnie_id) REFERENCES compartenaire (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_6034999398DE13AC FOREIGN KEY (partenaire_id) REFERENCES compartenaire (id)');
        $this->addSql('CREATE INDEX IDX_6034999352FBE437 ON contrat (compagnie_id)');
        $this->addSql('CREATE INDEX IDX_6034999398DE13AC ON contrat (partenaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_6034999352FBE437');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_6034999398DE13AC');
        $this->addSql('DROP INDEX IDX_6034999352FBE437 ON contrat');
        $this->addSql('DROP INDEX IDX_6034999398DE13AC ON contrat');
        $this->addSql('ALTER TABLE contrat DROP compagnie_id, DROP partenaire_id');
    }
}
