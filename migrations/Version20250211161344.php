<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211161344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospect ADD team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_C9CE8C7D296CD8AE ON prospect (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D296CD8AE');
        $this->addSql('DROP INDEX IDX_C9CE8C7D296CD8AE ON prospect');
        $this->addSql('ALTER TABLE prospect DROP team_id');
    }
}
