<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220173011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE smfn_api_call_log DROP CONSTRAINT fk_b93fcaa68e6c04f6');
        $this->addSql('DROP INDEX idx_b93fcaa68e6c04f6');
        $this->addSql('ALTER TABLE smfn_api_call_log DROP taxer_id');
        $this->addSql('ALTER TABLE smfn_api_call_log DROP tax');
        $this->addSql('ALTER TABLE smfn_ledger ALTER balance TYPE DOUBLE PRECISION');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE smfn_ledger ALTER balance TYPE INT');
        $this->addSql('ALTER TABLE smfn_api_call_log ADD taxer_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE smfn_api_call_log ADD tax INT NOT NULL');
        $this->addSql('COMMENT ON COLUMN smfn_api_call_log.taxer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smfn_api_call_log ADD CONSTRAINT fk_b93fcaa68e6c04f6 FOREIGN KEY (taxer_id) REFERENCES smfn_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b93fcaa68e6c04f6 ON smfn_api_call_log (taxer_id)');
    }
}
