<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522114400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique ADD stagiaire_id INT DEFAULT NULL, ADD etablissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECBBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('CREATE INDEX IDX_EDBFD5ECBBA93DD6 ON historique (stagiaire_id)');
        $this->addSql('CREATE INDEX IDX_EDBFD5ECFF631228 ON historique (etablissement_id)');
        $this->addSql('ALTER TABLE stagiaire DROP historique');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECBBA93DD6');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECFF631228');
        $this->addSql('DROP INDEX IDX_EDBFD5ECBBA93DD6 ON historique');
        $this->addSql('DROP INDEX IDX_EDBFD5ECFF631228 ON historique');
        $this->addSql('ALTER TABLE historique DROP stagiaire_id, DROP etablissement_id');
        $this->addSql('ALTER TABLE stagiaire ADD historique VARCHAR(255) DEFAULT NULL');
    }
}
