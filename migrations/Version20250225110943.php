<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225110943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospect ADD autor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D14D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C9CE8C7D14D45BBE ON prospect (autor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D14D45BBE');
        $this->addSql('DROP INDEX IDX_C9CE8C7D14D45BBE ON prospect');
        $this->addSql('ALTER TABLE prospect DROP autor_id');
    }
}
