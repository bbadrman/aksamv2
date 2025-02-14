<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213161304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospect ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_C9CE8C7D4584665A ON prospect (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D4584665A');
        $this->addSql('DROP INDEX IDX_C9CE8C7D4584665A ON prospect');
        $this->addSql('ALTER TABLE prospect DROP product_id');
    }
}
