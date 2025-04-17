<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415101438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD contre_partie NUMERIC(10, 2) DEFAULT NULL, ADD type VARCHAR(255) DEFAULT NULL, ADD transaction VARCHAR(255) DEFAULT NULL, ADD moyen VARCHAR(255) DEFAULT NULL, ADD montant NUMERIC(10, 2) DEFAULT NULL, ADD date_regelement DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP contre_partie, DROP type, DROP transaction, DROP moyen, DROP montant, DROP date_regelement');
    }
}
