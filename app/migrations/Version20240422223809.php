<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422223809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE smfn_oauth_hash DROP CONSTRAINT fk_469d16687e3c61f9');
        $this->addSql('DROP INDEX idx_469d16687e3c61f9');
        $this->addSql('DROP INDEX uniq_469d1668d1b862b8');
        $this->addSql('ALTER TABLE smfn_oauth_hash DROP CONSTRAINT smfn_oauth_hash_pkey');
        $this->addSql('ALTER TABLE smfn_oauth_hash ADD email VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE smfn_oauth_hash DROP owner_id');
        $this->addSql('ALTER TABLE smfn_oauth_hash ADD PRIMARY KEY (hash)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX smfn_oauth_hash_pkey');
        $this->addSql('ALTER TABLE smfn_oauth_hash ADD owner_id UUID NOT NULL');
        $this->addSql('ALTER TABLE smfn_oauth_hash DROP email');
        $this->addSql('COMMENT ON COLUMN smfn_oauth_hash.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smfn_oauth_hash ADD CONSTRAINT fk_469d16687e3c61f9 FOREIGN KEY (owner_id) REFERENCES smfn_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_469d16687e3c61f9 ON smfn_oauth_hash (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_469d1668d1b862b8 ON smfn_oauth_hash (hash)');
        $this->addSql('ALTER TABLE smfn_oauth_hash ADD PRIMARY KEY (owner_id, hash)');
    }
}
