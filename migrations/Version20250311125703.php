<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311125703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE antcdent_assurance (id INT AUTO_INCREMENT NOT NULL, contrat_id INT DEFAULT NULL, assureur VARCHAR(255) DEFAULT NULL, nbr_mois NUMERIC(10, 2) DEFAULT NULL, etat_contrat VARCHAR(255) DEFAULT NULL, sinistres TINYINT(1) DEFAULT NULL, nbr_sinistre INT DEFAULT NULL, type_sinistre VARCHAR(255) DEFAULT NULL, responsable TINYINT(1) DEFAULT NULL, Ãpourcent_resp VARCHAR(255) DEFAULT NULL, bris_glace VARCHAR(255) DEFAULT NULL, vol_incendie VARCHAR(255) DEFAULT NULL, crm_act VARCHAR(255) DEFAULT NULL, INDEX IDX_9A1CD9BD1823061F (contrat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regelement (id INT AUTO_INCREMENT NOT NULL, contrat_id INT DEFAULT NULL, montant_reglement NUMERIC(10, 2) DEFAULT NULL, date_reglement DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', transaction INT DEFAULT NULL, moyen VARCHAR(255) DEFAULT NULL, INDEX IDX_1C0D1DD1823061F (contrat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE antcdent_assurance ADD CONSTRAINT FK_9A1CD9BD1823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE regelement ADD CONSTRAINT FK_1C0D1DD1823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE contrat ADD product_id INT DEFAULT NULL, ADD date_naissance DATE DEFAULT NULL, ADD date_achat DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD date_mec DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD risque_usage VARCHAR(255) DEFAULT NULL, ADD type_permis VARCHAR(255) DEFAULT NULL, ADD suspension_permis TINYINT(1) DEFAULT NULL, ADD date_suspension DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD motif_suspension VARCHAR(255) DEFAULT NULL, ADD annulation_permis TINYINT(1) DEFAULT NULL, ADD date_annulation DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD motif_annulation VARCHAR(255) DEFAULT NULL, ADD crm_actuel VARCHAR(255) DEFAULT NULL, ADD crm_retenu NUMERIC(10, 2) DEFAULT NULL, ADD garanties VARCHAR(255) DEFAULT NULL, ADD facilite TINYINT(1) DEFAULT NULL, ADD nmbr_reglement VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_603499934584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_603499934584665A ON contrat (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE antcdent_assurance DROP FOREIGN KEY FK_9A1CD9BD1823061F');
        $this->addSql('ALTER TABLE regelement DROP FOREIGN KEY FK_1C0D1DD1823061F');
        $this->addSql('DROP TABLE antcdent_assurance');
        $this->addSql('DROP TABLE regelement');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_603499934584665A');
        $this->addSql('DROP INDEX IDX_603499934584665A ON contrat');
        $this->addSql('ALTER TABLE contrat DROP product_id, DROP date_naissance, DROP date_achat, DROP date_mec, DROP risque_usage, DROP type_permis, DROP suspension_permis, DROP date_suspension, DROP motif_suspension, DROP annulation_permis, DROP date_annulation, DROP motif_annulation, DROP crm_actuel, DROP crm_retenu, DROP garanties, DROP facilite, DROP nmbr_reglement');
    }
}
