<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930135014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospect ADD revenu NUMERIC(10, 2) DEFAULT NULL, ADD regim_social VARCHAR(255) DEFAULT NULL, DROP gender, DROP gsm, DROP secd_email');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospect ADD gsm VARCHAR(255) DEFAULT NULL, ADD secd_email VARCHAR(255) DEFAULT NULL, DROP revenu, CHANGE regim_social gender VARCHAR(255) DEFAULT NULL');
    }
}
