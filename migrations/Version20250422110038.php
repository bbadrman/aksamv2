<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422110038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document ADD devoir_conseil VARCHAR(255) DEFAULT NULL, ADD permis VARCHAR(255) DEFAULT NULL, ADD kbis VARCHAR(255) DEFAULT NULL, ADD releve_information VARCHAR(255) DEFAULT NULL, ADD devi_signe VARCHAR(255) DEFAULT NULL, ADD carte_grise_definitive VARCHAR(255) DEFAULT NULL, ADD atest_non_assure VARCHAR(255) DEFAULT NULL, ADD atest_non_sinist VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP devoir_conseil, DROP permis, DROP kbis, DROP releve_information, DROP devi_signe, DROP carte_grise_definitive, DROP atest_non_assure, DROP atest_non_sinist');
    }
}
