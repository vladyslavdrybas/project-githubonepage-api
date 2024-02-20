<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220110334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE smfn_api_call_log (id VARCHAR(64) NOT NULL, api_key_id VARCHAR(64) DEFAULT NULL, sender_id UUID DEFAULT NULL, recipient_id UUID DEFAULT NULL, taxer_id UUID DEFAULT NULL, cost_per_call INT NOT NULL, tax INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, endpoint VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B93FCAA68BE312B3 ON smfn_api_call_log (api_key_id)');
        $this->addSql('CREATE INDEX IDX_B93FCAA6F624B39D ON smfn_api_call_log (sender_id)');
        $this->addSql('CREATE INDEX IDX_B93FCAA6E92F8F78 ON smfn_api_call_log (recipient_id)');
        $this->addSql('CREATE INDEX IDX_B93FCAA68E6C04F6 ON smfn_api_call_log (taxer_id)');
        $this->addSql('COMMENT ON COLUMN smfn_api_call_log.sender_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN smfn_api_call_log.recipient_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN smfn_api_call_log.taxer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE smfn_ledger (owner_id UUID NOT NULL, balance INT DEFAULT 0 NOT NULL, PRIMARY KEY(owner_id))');
        $this->addSql('COMMENT ON COLUMN smfn_ledger.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smfn_api_call_log ADD CONSTRAINT FK_B93FCAA68BE312B3 FOREIGN KEY (api_key_id) REFERENCES smfn_api_key (api_key) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smfn_api_call_log ADD CONSTRAINT FK_B93FCAA6F624B39D FOREIGN KEY (sender_id) REFERENCES smfn_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smfn_api_call_log ADD CONSTRAINT FK_B93FCAA6E92F8F78 FOREIGN KEY (recipient_id) REFERENCES smfn_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smfn_api_call_log ADD CONSTRAINT FK_B93FCAA68E6C04F6 FOREIGN KEY (taxer_id) REFERENCES smfn_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smfn_ledger ADD CONSTRAINT FK_6662DC387E3C61F9 FOREIGN KEY (owner_id) REFERENCES smfn_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smfn_api_key ADD endDate TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE smfn_api_key ADD cost_per_call INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE smfn_api_key DROP is_subscription_active');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE smfn_api_call_log DROP CONSTRAINT FK_B93FCAA68BE312B3');
        $this->addSql('ALTER TABLE smfn_api_call_log DROP CONSTRAINT FK_B93FCAA6F624B39D');
        $this->addSql('ALTER TABLE smfn_api_call_log DROP CONSTRAINT FK_B93FCAA6E92F8F78');
        $this->addSql('ALTER TABLE smfn_api_call_log DROP CONSTRAINT FK_B93FCAA68E6C04F6');
        $this->addSql('ALTER TABLE smfn_ledger DROP CONSTRAINT FK_6662DC387E3C61F9');
        $this->addSql('DROP TABLE smfn_api_call_log');
        $this->addSql('DROP TABLE smfn_ledger');
        $this->addSql('ALTER TABLE smfn_api_key ADD is_subscription_active BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE smfn_api_key DROP endDate');
        $this->addSql('ALTER TABLE smfn_api_key DROP cost_per_call');
    }
}
