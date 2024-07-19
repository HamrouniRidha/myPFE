<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603112851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, departement VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE encadrant (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, stagiaire_id INT DEFAULT NULL, etablissement_id INT DEFAULT NULL, annee VARCHAR(255) NOT NULL, certificat VARCHAR(255) NOT NULL, INDEX IDX_EDBFD5ECBBA93DD6 (stagiaire_id), INDEX IDX_EDBFD5ECFF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, encadrant_id INT DEFAULT NULL, historique_id INT DEFAULT NULL, departement_id INT DEFAULT NULL, sujet VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, document_file_name LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', note1 DOUBLE PRECISION DEFAULT NULL, note2 DOUBLE PRECISION DEFAULT NULL, note3 DOUBLE PRECISION DEFAULT NULL, note4 DOUBLE PRECISION DEFAULT NULL, note5 DOUBLE PRECISION DEFAULT NULL, moyenne DOUBLE PRECISION DEFAULT NULL, etat VARCHAR(20) DEFAULT NULL, INDEX IDX_C27C9369C54C8C93 (type_id), INDEX IDX_C27C9369FEF1BA4 (encadrant_id), INDEX IDX_C27C93696128735E (historique_id), INDEX IDX_C27C9369CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stagiaire (id INT AUTO_INCREMENT NOT NULL, cin VARCHAR(8) DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) DEFAULT NULL, tel VARCHAR(8) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8D93D64912B2DC9C (matricule), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECBBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369FEF1BA4 FOREIGN KEY (encadrant_id) REFERENCES encadrant (id)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C93696128735E FOREIGN KEY (historique_id) REFERENCES historique (id)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECBBA93DD6');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECFF631228');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369C54C8C93');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369FEF1BA4');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C93696128735E');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369CCF9E01E');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE encadrant');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE stagiaire');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
