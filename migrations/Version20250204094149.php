<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204094149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_history (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, users_id INT DEFAULT NULL, affect_at DATETIME DEFAULT NULL, INDEX IDX_7FB76E41296CD8AE (team_id), INDEX IDX_7FB76E4167B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_history ADD CONSTRAINT FK_7FB76E41296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE user_history ADD CONSTRAINT FK_7FB76E4167B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_history DROP FOREIGN KEY FK_7FB76E41296CD8AE');
        $this->addSql('ALTER TABLE user_history DROP FOREIGN KEY FK_7FB76E4167B3B43D');
        $this->addSql('DROP TABLE user_history');
    }
}
