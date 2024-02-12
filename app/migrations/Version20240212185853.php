<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212185853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE smfn_project (id UUID NOT NULL, owner_id UUID NOT NULL, title VARCHAR(36) NOT NULL, description VARCHAR(256) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5C08EAF7E3C61F9 ON smfn_project (owner_id)');
        $this->addSql('COMMENT ON COLUMN smfn_project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN smfn_project.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smfn_project ADD CONSTRAINT FK_C5C08EAF7E3C61F9 FOREIGN KEY (owner_id) REFERENCES smfn_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smfn_refresh_tokens ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN smfn_refresh_tokens.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smfn_user ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN smfn_user.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE smfn_project DROP CONSTRAINT FK_C5C08EAF7E3C61F9');
        $this->addSql('DROP TABLE smfn_project');
        $this->addSql('ALTER TABLE smfn_user ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN smfn_user.id IS NULL');
        $this->addSql('ALTER TABLE smfn_refresh_tokens ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN smfn_refresh_tokens.id IS NULL');
    }
}
