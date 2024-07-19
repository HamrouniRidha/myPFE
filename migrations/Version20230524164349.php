<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524164349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stage ADD encadrant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369FEF1BA4 FOREIGN KEY (encadrant_id) REFERENCES encadrant (id)');
        $this->addSql('CREATE INDEX IDX_C27C9369FEF1BA4 ON stage (encadrant_id)');
        $this->addSql('ALTER TABLE stagiaire CHANGE tel tel BIGINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369FEF1BA4');
        $this->addSql('DROP INDEX IDX_C27C9369FEF1BA4 ON stage');
        $this->addSql('ALTER TABLE stage DROP encadrant_id');
        $this->addSql('ALTER TABLE stagiaire CHANGE tel tel VARCHAR(255) DEFAULT NULL');
    }
}
