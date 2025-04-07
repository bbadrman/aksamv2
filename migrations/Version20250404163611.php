<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404163611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, comrcl_id INT DEFAULT NULL, transaction VARCHAR(255) DEFAULT NULL, commande VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, date_paiement DATE DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, debit NUMERIC(10, 2) DEFAULT NULL, credit NUMERIC(10, 2) DEFAULT NULL, moyen VARCHAR(255) DEFAULT NULL, INDEX IDX_723705D110C5C333 (comrcl_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D110C5C333 FOREIGN KEY (comrcl_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D110C5C333');
        $this->addSql('DROP TABLE transaction');
    }
}
