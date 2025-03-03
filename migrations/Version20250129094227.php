<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129094227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('unite_travail_team')) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE unite_travail_team (unite_travail_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_28F6696FE8CC3A92 (unite_travail_id), INDEX IDX_28F6696F296CD8AE (team_id), PRIMARY KEY(unite_travail_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE unite_travail_team ADD CONSTRAINT FK_28F6696FE8CC3A92 FOREIGN KEY (unite_travail_id) REFERENCES unite_travail (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE unite_travail_team ADD CONSTRAINT FK_28F6696F296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        }
    }
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unite_travail_team DROP FOREIGN KEY FK_28F6696FE8CC3A92');
        $this->addSql('ALTER TABLE unite_travail_team DROP FOREIGN KEY FK_28F6696F296CD8AE');
        $this->addSql('DROP TABLE unite_travail_team');
    }
}
