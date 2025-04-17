<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414104104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sav (id INT AUTO_INCREMENT NOT NULL, contrat_id INT DEFAULT NULL, nature_demande VARCHAR(255) DEFAULT NULL, creat_at DATETIME DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, motif_relance VARCHAR(255) DEFAULT NULL, relance_at DATETIME DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, status SMALLINT DEFAULT NULL, comment_traiter VARCHAR(255) DEFAULT NULL, montant NUMERIC(10, 2) DEFAULT NULL, date_ajout DATETIME DEFAULT NULL, INDEX IDX_6C7681F41823061F (contrat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sav_user (sav_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A1D3B1D84F726353 (sav_id), INDEX IDX_A1D3B1D8A76ED395 (user_id), PRIMARY KEY(sav_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sav ADD CONSTRAINT FK_6C7681F41823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE sav_user ADD CONSTRAINT FK_A1D3B1D84F726353 FOREIGN KEY (sav_id) REFERENCES sav (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sav_user ADD CONSTRAINT FK_A1D3B1D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sav DROP FOREIGN KEY FK_6C7681F41823061F');
        $this->addSql('ALTER TABLE sav_user DROP FOREIGN KEY FK_A1D3B1D84F726353');
        $this->addSql('ALTER TABLE sav_user DROP FOREIGN KEY FK_A1D3B1D8A76ED395');
        $this->addSql('DROP TABLE sav');
        $this->addSql('DROP TABLE sav_user');
    }
}
