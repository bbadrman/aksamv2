<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521094254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appel (id INT AUTO_INCREMENT NOT NULL, from_number VARCHAR(255) DEFAULT NULL, to_number VARCHAR(255) DEFAULT NULL, start_time DATETIME DEFAULT NULL, end_time DATETIME DEFAULT NULL, duration INT DEFAULT NULL, record_url VARCHAR(255) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE regelement DROP FOREIGN KEY FK_1C0D1DD1823061F');
        $this->addSql('DROP TABLE regelement');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE regelement (id INT AUTO_INCREMENT NOT NULL, contrat_id INT DEFAULT NULL, montant_reglement NUMERIC(10, 2) DEFAULT NULL, date_reglement DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', transaction INT DEFAULT NULL, moyen VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, frais NUMERIC(10, 2) DEFAULT NULL, cotisation NUMERIC(10, 2) DEFAULT NULL, contreparti NUMERIC(10, 2) DEFAULT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, montant_reglement1 NUMERIC(10, 2) DEFAULT NULL, montant_reglement2 NUMERIC(10, 2) DEFAULT NULL, montant_reglement3 NUMERIC(10, 2) DEFAULT NULL, date_reglement1 DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_reglement2 DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_reglement3 DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1C0D1DD1823061F (contrat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE regelement ADD CONSTRAINT FK_1C0D1DD1823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE appel');
    }
}
