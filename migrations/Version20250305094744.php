<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305094744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD team_id INT DEFAULT NULL, ADD cmrcl_id INT DEFAULT NULL, ADD prospect_id INT DEFAULT NULL, ADD force_juridique VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A1AFA0DA FOREIGN KEY (cmrcl_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455D182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id)');
        $this->addSql('CREATE INDEX IDX_C7440455296CD8AE ON client (team_id)');
        $this->addSql('CREATE INDEX IDX_C7440455A1AFA0DA ON client (cmrcl_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455D182060A ON client (prospect_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455296CD8AE');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A1AFA0DA');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455D182060A');
        $this->addSql('DROP INDEX IDX_C7440455296CD8AE ON client');
        $this->addSql('DROP INDEX IDX_C7440455A1AFA0DA ON client');
        $this->addSql('DROP INDEX UNIQ_C7440455D182060A ON client');
        $this->addSql('ALTER TABLE client DROP team_id, DROP cmrcl_id, DROP prospect_id, DROP force_juridique');
    }
}
