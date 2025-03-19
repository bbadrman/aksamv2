<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318135942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE antcdent_assurance ADD material INT DEFAULT NULL, ADD corporel INT DEFAULT NULL, ADD zeresponsable INT DEFAULT NULL, ADD cent_responsable INT DEFAULT NULL, ADD cinq_responsable INT DEFAULT NULL, DROP type_sinistre, DROP responsable, DROP pourcent_resp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE antcdent_assurance ADD type_sinistre VARCHAR(255) DEFAULT NULL, ADD responsable TINYINT(1) DEFAULT NULL, ADD pourcent_resp VARCHAR(255) DEFAULT NULL, DROP material, DROP corporel, DROP zeresponsable, DROP cent_responsable, DROP cinq_responsable');
    }
}
