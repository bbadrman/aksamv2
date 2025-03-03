<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205085216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('client')) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, creat_at DATETIME DEFAULT NULL, status SMALLINT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, is_modifie TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, date_souscrpt VARCHAR(255) DEFAULT NULL, date_effet DATETIME DEFAULT NULL, type_contrat VARCHAR(255) DEFAULT NULL, activite VARCHAR(255) DEFAULT NULL, imatriclt VARCHAR(255) DEFAULT NULL, partenaire VARCHAR(255) DEFAULT NULL, compagnie VARCHAR(255) DEFAULT NULL, formule VARCHAR(255) DEFAULT NULL, fraction VARCHAR(255) DEFAULT NULL, frais NUMERIC(10, 2) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, type_conducteur VARCHAR(255) DEFAULT NULL, conducteur VARCHAR(255) DEFAULT NULL, date_permis DATETIME DEFAULT NULL, status SMALLINT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, cotisation NUMERIC(10, 2) DEFAULT NULL, acompte NUMERIC(10, 2) DEFAULT NULL, first_reglement NUMERIC(10, 2) NOT NULL, second_reglement NUMERIC(10, 2) DEFAULT NULL, jour_prelvm VARCHAR(255) DEFAULT NULL, type_product VARCHAR(255) DEFAULT NULL, is_modif TINYINT(1) DEFAULT NULL, date_preleve_acompte DATETIME DEFAULT NULL, INDEX IDX_60349993A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE prospect (id INT AUTO_INCREMENT NOT NULL, comrcl_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, brith_at DATETIME DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, type_prospect VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, code_post VARCHAR(255) DEFAULT NULL, gsm VARCHAR(255) DEFAULT NULL, assurer VARCHAR(255) DEFAULT NULL, last_assure VARCHAR(255) DEFAULT NULL, motif_resil VARCHAR(255) DEFAULT NULL, motif_saise VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, secd_email VARCHAR(255) DEFAULT NULL, creat_at DATETIME DEFAULT NULL, activites VARCHAR(255) DEFAULT NULL, INDEX IDX_C9CE8C7D10C5C333 (comrcl_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE unite_travail (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE unite_travail_team (unite_travail_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_28F6696FE8CC3A92 (unite_travail_id), INDEX IDX_28F6696F296CD8AE (team_id), PRIMARY KEY(unite_travail_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, embuch_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', remuneration NUMERIC(10, 0) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, status SMALLINT NOT NULL, creat_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user_permission (user_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_472E5446A76ED395 (user_id), INDEX IDX_472E5446FED90CCA (permission_id), PRIMARY KEY(user_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user_product (user_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_8B471AA7A76ED395 (user_id), INDEX IDX_8B471AA74584665A (product_id), PRIMARY KEY(user_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user_team (user_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_BE61EAD6A76ED395 (user_id), INDEX IDX_BE61EAD6296CD8AE (team_id), PRIMARY KEY(user_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user_history (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, users_id INT DEFAULT NULL, affect_at DATETIME DEFAULT NULL, INDEX IDX_7FB76E41296CD8AE (team_id), INDEX IDX_7FB76E4167B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
            $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7D10C5C333 FOREIGN KEY (comrcl_id) REFERENCES user (id)');
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
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993A76ED395');
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7D10C5C333');
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
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE prospect');
        $this->addSql('DROP TABLE team');
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
