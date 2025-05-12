<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424084405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat ADD payments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993BBC61482 FOREIGN KEY (payments_id) REFERENCES payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_60349993BBC61482 ON contrat (payments_id)');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D1823061F');
        $this->addSql('DROP INDEX IDX_6D28840D1823061F ON payment');
        $this->addSql('ALTER TABLE payment DROP contrat_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993BBC61482');
        $this->addSql('DROP INDEX UNIQ_60349993BBC61482 ON contrat');
        $this->addSql('ALTER TABLE contrat DROP payments_id');
        $this->addSql('ALTER TABLE payment ADD contrat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D1823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6D28840D1823061F ON payment (contrat_id)');
    }
}
