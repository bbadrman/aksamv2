<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422110218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document ADD contrat_id INT DEFAULT NULL, ADD devoir_conseil VARCHAR(255) DEFAULT NULL, ADD permis VARCHAR(255) DEFAULT NULL, ADD kbis VARCHAR(255) DEFAULT NULL, ADD releve_information VARCHAR(255) DEFAULT NULL, ADD devi_signe VARCHAR(255) DEFAULT NULL, ADD carte_grise_definitive VARCHAR(255) DEFAULT NULL, ADD atest_non_assure VARCHAR(255) DEFAULT NULL, ADD atest_non_sinist VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A761823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8698A761823061F ON document (contrat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A761823061F');
        $this->addSql('DROP INDEX UNIQ_D8698A761823061F ON document');
        $this->addSql('ALTER TABLE document DROP contrat_id, DROP devoir_conseil, DROP permis, DROP kbis, DROP releve_information, DROP devi_signe, DROP carte_grise_definitive, DROP atest_non_assure, DROP atest_non_sinist');
    }
}
