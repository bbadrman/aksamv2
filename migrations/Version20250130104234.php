<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130104234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('user_product')) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE user_product (user_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_8B471AA7A76ED395 (user_id), INDEX IDX_8B471AA74584665A (product_id), PRIMARY KEY(user_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user_team (user_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_BE61EAD6A76ED395 (user_id), INDEX IDX_BE61EAD6296CD8AE (team_id), PRIMARY KEY(user_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA74584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE user_team ADD CONSTRAINT FK_BE61EAD6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE user_team ADD CONSTRAINT FK_BE61EAD6296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE contrat ADD user_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
            $this->addSql('CREATE INDEX IDX_60349993A76ED395 ON contrat (user_id)');
            $this->addSql('ALTER TABLE prospect ADD comrcl_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D10C5C333 FOREIGN KEY (comrcl_id) REFERENCES user (id)');
            $this->addSql('CREATE INDEX IDX_C9CE8C7D10C5C333 ON prospect (comrcl_id)');
        }
    }
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA7A76ED395');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA74584665A');
        $this->addSql('ALTER TABLE user_team DROP FOREIGN KEY FK_BE61EAD6A76ED395');
        $this->addSql('ALTER TABLE user_team DROP FOREIGN KEY FK_BE61EAD6296CD8AE');
        $this->addSql('DROP TABLE user_product');
        $this->addSql('DROP TABLE user_team');
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D10C5C333');
        $this->addSql('DROP INDEX IDX_C9CE8C7D10C5C333 ON prospect');
        $this->addSql('ALTER TABLE prospect DROP comrcl_id');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993A76ED395');
        $this->addSql('DROP INDEX IDX_60349993A76ED395 ON contrat');
        $this->addSql('ALTER TABLE contrat DROP user_id');
    }
}
