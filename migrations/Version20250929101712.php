<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929101712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE antcdent_assurance (id INT AUTO_INCREMENT NOT NULL, contrat_id INT DEFAULT NULL, assureur VARCHAR(255) DEFAULT NULL, etat_contrat VARCHAR(255) DEFAULT NULL, sinistres TINYINT(1) DEFAULT NULL, nbr_sinistre INT DEFAULT NULL, bris_glace VARCHAR(255) DEFAULT NULL, vol_incendie VARCHAR(255) DEFAULT NULL, crm_act VARCHAR(255) DEFAULT NULL, nbr_mois INT DEFAULT NULL, material INT DEFAULT NULL, corporel INT DEFAULT NULL, zeresponsable INT DEFAULT NULL, cent_responsable INT DEFAULT NULL, cinq_responsable INT DEFAULT NULL, INDEX IDX_9A1CD9BD1823061F (contrat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appel (id INT AUTO_INCREMENT NOT NULL, from_number VARCHAR(255) DEFAULT NULL, to_number VARCHAR(255) DEFAULT NULL, start_time DATETIME DEFAULT NULL, end_time DATETIME DEFAULT NULL, duration INT DEFAULT NULL, record_url VARCHAR(255) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, cmrcl_id INT DEFAULT NULL, prospect_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, creat_at DATETIME DEFAULT NULL, status SMALLINT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, is_modifie TINYINT(1) DEFAULT NULL, INDEX IDX_C7440455296CD8AE (team_id), INDEX IDX_C7440455A1AFA0DA (cmrcl_id), UNIQUE INDEX UNIQ_C7440455D182060A (prospect_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compartenaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, product_id INT DEFAULT NULL, compagnie_id INT DEFAULT NULL, partenaire_id INT DEFAULT NULL, client_id INT DEFAULT NULL, document_id INT DEFAULT NULL, payments_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, date_souscrpt DATETIME DEFAULT NULL, date_effet DATETIME DEFAULT NULL, type_contrat VARCHAR(255) DEFAULT NULL, activite VARCHAR(255) DEFAULT NULL, imatriclt VARCHAR(255) DEFAULT NULL, formule VARCHAR(255) DEFAULT NULL, fraction VARCHAR(255) DEFAULT NULL, frais NUMERIC(10, 2) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, type_conducteur VARCHAR(255) DEFAULT NULL, conducteur VARCHAR(255) DEFAULT NULL, date_permis DATETIME DEFAULT NULL, status SMALLINT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, cotisation NUMERIC(10, 2) DEFAULT NULL, acompte NUMERIC(10, 2) DEFAULT NULL, jour_prelvm VARCHAR(255) DEFAULT NULL, type_product VARCHAR(255) DEFAULT NULL, is_modif TINYINT(1) DEFAULT NULL, date_preleve_acompte DATETIME DEFAULT NULL, date_naissance DATE DEFAULT NULL, date_achat DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_mec DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', risque_usage VARCHAR(255) DEFAULT NULL, type_permis VARCHAR(255) DEFAULT NULL, suspension_permis TINYINT(1) DEFAULT NULL, date_suspension DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', motif_suspension VARCHAR(255) DEFAULT NULL, annulation_permis TINYINT(1) DEFAULT NULL, date_annulation DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', motif_annulation VARCHAR(255) DEFAULT NULL, crm_actuel VARCHAR(255) DEFAULT NULL, crm_retenu NUMERIC(10, 2) DEFAULT NULL, garanties VARCHAR(255) DEFAULT NULL, facilite TINYINT(1) DEFAULT NULL, nmbr_reglement VARCHAR(255) DEFAULT NULL, nmbr_assure VARCHAR(255) DEFAULT NULL, force_juridique VARCHAR(255) DEFAULT NULL, INDEX IDX_60349993A76ED395 (user_id), INDEX IDX_603499934584665A (product_id), INDEX IDX_6034999352FBE437 (compagnie_id), INDEX IDX_6034999398DE13AC (partenaire_id), INDEX IDX_6034999319EB6921 (client_id), UNIQUE INDEX UNIQ_60349993C33F7837 (document_id), UNIQUE INDEX UNIQ_60349993BBC61482 (payments_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, contrat_id INT DEFAULT NULL, bulletin_adhesion VARCHAR(255) DEFAULT NULL, mondat_sepa VARCHAR(255) DEFAULT NULL, condition_general VARCHAR(255) DEFAULT NULL, devoir_conseil VARCHAR(255) DEFAULT NULL, permis VARCHAR(255) DEFAULT NULL, kbis VARCHAR(255) DEFAULT NULL, releve_information VARCHAR(255) DEFAULT NULL, devi_signe VARCHAR(255) DEFAULT NULL, carte_grise_definitive VARCHAR(255) DEFAULT NULL, atest_non_assure VARCHAR(255) DEFAULT NULL, atest_non_sinist VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D8698A761823061F (contrat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, prospect_id INT DEFAULT NULL, team_id INT DEFAULT NULL, action_date DATETIME DEFAULT NULL, action_type VARCHAR(255) DEFAULT NULL, INDEX IDX_27BA704BD182060A (prospect_id), INDEX IDX_27BA704B296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, frais NUMERIC(10, 2) DEFAULT NULL, cotisation NUMERIC(10, 2) DEFAULT NULL, creat_at DATETIME DEFAULT NULL, contre_partie NUMERIC(10, 2) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, transaction VARCHAR(255) DEFAULT NULL, moyen VARCHAR(255) DEFAULT NULL, montant NUMERIC(10, 2) DEFAULT NULL, date_regelement DATETIME DEFAULT NULL, montant1 NUMERIC(10, 2) DEFAULT NULL, montant2 NUMERIC(10, 2) DEFAULT NULL, montant3 NUMERIC(10, 2) DEFAULT NULL, montant4 NUMERIC(10, 2) DEFAULT NULL, transaction1 VARCHAR(255) DEFAULT NULL, transaction2 VARCHAR(255) DEFAULT NULL, transaction3 VARCHAR(255) DEFAULT NULL, date_payment DATETIME DEFAULT NULL, date_payment1 DATETIME DEFAULT NULL, date_payment3 DATETIME DEFAULT NULL, date_payment2 DATETIME DEFAULT NULL, nmbr_reglement INT DEFAULT NULL, moyen1 VARCHAR(255) DEFAULT NULL, moyen2 VARCHAR(255) DEFAULT NULL, moyen3 VARCHAR(255) DEFAULT NULL, moyen_acompte VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prospect (id INT AUTO_INCREMENT NOT NULL, comrcl_id INT DEFAULT NULL, team_id INT DEFAULT NULL, product_id INT DEFAULT NULL, autor_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, brith_at DATETIME DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, type_prospect VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, code_post VARCHAR(255) DEFAULT NULL, gsm VARCHAR(255) DEFAULT NULL, assurer VARCHAR(255) DEFAULT NULL, last_assure VARCHAR(255) DEFAULT NULL, motif_resil VARCHAR(255) DEFAULT NULL, motif_saise VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, secd_email VARCHAR(255) DEFAULT NULL, creat_at DATETIME DEFAULT NULL, activites VARCHAR(255) DEFAULT NULL, relance VARCHAR(255) DEFAULT NULL, relance_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', affect_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', motif_affect VARCHAR(255) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_C9CE8C7D10C5C333 (comrcl_id), INDEX IDX_C9CE8C7D296CD8AE (team_id), INDEX IDX_C9CE8C7D4584665A (product_id), INDEX IDX_C9CE8C7D14D45BBE (autor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relance_history (id INT AUTO_INCREMENT NOT NULL, prospect_id INT DEFAULT NULL, motif_relance VARCHAR(255) DEFAULT NULL, relanced_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment VARCHAR(255) DEFAULT NULL, INDEX IDX_4E9F42A5D182060A (prospect_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relance_sav (id INT AUTO_INCREMENT NOT NULL, sav_id INT DEFAULT NULL, creat_at DATETIME DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, motif_relance VARCHAR(255) DEFAULT NULL, INDEX IDX_5873B21C4F726353 (sav_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sav (id INT AUTO_INCREMENT NOT NULL, contrat_id INT DEFAULT NULL, nature_demande VARCHAR(255) DEFAULT NULL, creat_at DATETIME DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, motif_relance VARCHAR(255) DEFAULT NULL, relance_at DATETIME DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, status SMALLINT DEFAULT NULL, comment_traiter VARCHAR(255) DEFAULT NULL, montant NUMERIC(10, 2) DEFAULT NULL, date_ajout DATETIME DEFAULT NULL, INDEX IDX_6C7681F41823061F (contrat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sav_user (sav_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A1D3B1D84F726353 (sav_id), INDEX IDX_A1D3B1D8A76ED395 (user_id), PRIMARY KEY(sav_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, comrcl_id INT DEFAULT NULL, transaction VARCHAR(255) DEFAULT NULL, commande VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, date_paiement DATE DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, debit NUMERIC(10, 2) DEFAULT NULL, credit NUMERIC(10, 2) DEFAULT NULL, moyen VARCHAR(255) DEFAULT NULL, INDEX IDX_723705D110C5C333 (comrcl_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite_travail (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite_travail_team (unite_travail_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_28F6696FE8CC3A92 (unite_travail_id), INDEX IDX_28F6696F296CD8AE (team_id), PRIMARY KEY(unite_travail_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, embuch_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', remuneration NUMERIC(10, 0) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, status SMALLINT NOT NULL, creat_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_permission (user_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_472E5446A76ED395 (user_id), INDEX IDX_472E5446FED90CCA (permission_id), PRIMARY KEY(user_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_product (user_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_8B471AA7A76ED395 (user_id), INDEX IDX_8B471AA74584665A (product_id), PRIMARY KEY(user_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_team (user_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_BE61EAD6A76ED395 (user_id), INDEX IDX_BE61EAD6296CD8AE (team_id), PRIMARY KEY(user_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_history (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, users_id INT DEFAULT NULL, affect_at DATETIME DEFAULT NULL, INDEX IDX_7FB76E41296CD8AE (team_id), INDEX IDX_7FB76E4167B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE antcdent_assurance ADD CONSTRAINT FK_9A1CD9BD1823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A1AFA0DA FOREIGN KEY (cmrcl_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455D182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_603499934584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_6034999352FBE437 FOREIGN KEY (compagnie_id) REFERENCES compartenaire (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_6034999398DE13AC FOREIGN KEY (partenaire_id) REFERENCES compartenaire (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_6034999319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993BBC61482 FOREIGN KEY (payments_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A761823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BD182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D10C5C333 FOREIGN KEY (comrcl_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D14D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relance_history ADD CONSTRAINT FK_4E9F42A5D182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id)');
        $this->addSql('ALTER TABLE relance_sav ADD CONSTRAINT FK_5873B21C4F726353 FOREIGN KEY (sav_id) REFERENCES sav (id)');
        $this->addSql('ALTER TABLE sav ADD CONSTRAINT FK_6C7681F41823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE sav_user ADD CONSTRAINT FK_A1D3B1D84F726353 FOREIGN KEY (sav_id) REFERENCES sav (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sav_user ADD CONSTRAINT FK_A1D3B1D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D110C5C333 FOREIGN KEY (comrcl_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE unite_travail_team ADD CONSTRAINT FK_28F6696FE8CC3A92 FOREIGN KEY (unite_travail_id) REFERENCES unite_travail (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE unite_travail_team ADD CONSTRAINT FK_28F6696F296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_472E5446A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_472E5446FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA74584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_team ADD CONSTRAINT FK_BE61EAD6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_team ADD CONSTRAINT FK_BE61EAD6296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_history ADD CONSTRAINT FK_7FB76E41296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE user_history ADD CONSTRAINT FK_7FB76E4167B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE antcdent_assurance DROP FOREIGN KEY FK_9A1CD9BD1823061F');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455296CD8AE');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A1AFA0DA');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455D182060A');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993A76ED395');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_603499934584665A');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_6034999352FBE437');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_6034999398DE13AC');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_6034999319EB6921');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993C33F7837');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993BBC61482');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A761823061F');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BD182060A');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B296CD8AE');
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D10C5C333');
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D296CD8AE');
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D4584665A');
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D14D45BBE');
        $this->addSql('ALTER TABLE relance_history DROP FOREIGN KEY FK_4E9F42A5D182060A');
        $this->addSql('ALTER TABLE relance_sav DROP FOREIGN KEY FK_5873B21C4F726353');
        $this->addSql('ALTER TABLE sav DROP FOREIGN KEY FK_6C7681F41823061F');
        $this->addSql('ALTER TABLE sav_user DROP FOREIGN KEY FK_A1D3B1D84F726353');
        $this->addSql('ALTER TABLE sav_user DROP FOREIGN KEY FK_A1D3B1D8A76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D110C5C333');
        $this->addSql('ALTER TABLE unite_travail_team DROP FOREIGN KEY FK_28F6696FE8CC3A92');
        $this->addSql('ALTER TABLE unite_travail_team DROP FOREIGN KEY FK_28F6696F296CD8AE');
        $this->addSql('ALTER TABLE user_permission DROP FOREIGN KEY FK_472E5446A76ED395');
        $this->addSql('ALTER TABLE user_permission DROP FOREIGN KEY FK_472E5446FED90CCA');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA7A76ED395');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA74584665A');
        $this->addSql('ALTER TABLE user_team DROP FOREIGN KEY FK_BE61EAD6A76ED395');
        $this->addSql('ALTER TABLE user_team DROP FOREIGN KEY FK_BE61EAD6296CD8AE');
        $this->addSql('ALTER TABLE user_history DROP FOREIGN KEY FK_7FB76E41296CD8AE');
        $this->addSql('ALTER TABLE user_history DROP FOREIGN KEY FK_7FB76E4167B3B43D');
        $this->addSql('DROP TABLE antcdent_assurance');
        $this->addSql('DROP TABLE appel');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE compartenaire');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE prospect');
        $this->addSql('DROP TABLE relance_history');
        $this->addSql('DROP TABLE relance_sav');
        $this->addSql('DROP TABLE sav');
        $this->addSql('DROP TABLE sav_user');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE unite_travail');
        $this->addSql('DROP TABLE unite_travail_team');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_permission');
        $this->addSql('DROP TABLE user_product');
        $this->addSql('DROP TABLE user_team');
        $this->addSql('DROP TABLE user_history');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
