<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221082610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, prospect_id INT DEFAULT NULL, team_id INT DEFAULT NULL, action_date DATETIME DEFAULT NULL, INDEX IDX_27BA704BD182060A (prospect_id), INDEX IDX_27BA704B296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relance_history (id INT AUTO_INCREMENT NOT NULL, prospect_id INT DEFAULT NULL, motif_relance VARCHAR(255) DEFAULT NULL, relanced_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment VARCHAR(255) DEFAULT NULL, INDEX IDX_4E9F42A5D182060A (prospect_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BD182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE relance_history ADD CONSTRAINT FK_4E9F42A5D182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BD182060A');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B296CD8AE');
        $this->addSql('ALTER TABLE relance_history DROP FOREIGN KEY FK_4E9F42A5D182060A');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE relance_history');
    }
}
