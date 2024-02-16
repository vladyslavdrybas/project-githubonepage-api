<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216102650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE smfn_api_key ADD owner_id UUID NOT NULL');
        $this->addSql('ALTER TABLE smfn_api_key ADD project_id UUID NOT NULL');
        $this->addSql('ALTER TABLE smfn_api_key DROP owner');
        $this->addSql('ALTER TABLE smfn_api_key DROP project');
        $this->addSql('COMMENT ON COLUMN smfn_api_key.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN smfn_api_key.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smfn_api_key ADD CONSTRAINT FK_2361B3DC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES smfn_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smfn_api_key ADD CONSTRAINT FK_2361B3DC166D1F9C FOREIGN KEY (project_id) REFERENCES smfn_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2361B3DC7E3C61F9 ON smfn_api_key (owner_id)');
        $this->addSql('CREATE INDEX IDX_2361B3DC166D1F9C ON smfn_api_key (project_id)');
        $this->addSql('DROP INDEX title_region_country');
        $this->addSql('CREATE UNIQUE INDEX title_region_country_period ON smfn_subscription_plan (title, region, country, period)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE smfn_api_key DROP CONSTRAINT FK_2361B3DC7E3C61F9');
        $this->addSql('ALTER TABLE smfn_api_key DROP CONSTRAINT FK_2361B3DC166D1F9C');
        $this->addSql('DROP INDEX IDX_2361B3DC7E3C61F9');
        $this->addSql('DROP INDEX IDX_2361B3DC166D1F9C');
        $this->addSql('ALTER TABLE smfn_api_key ADD owner VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE smfn_api_key ADD project VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE smfn_api_key DROP owner_id');
        $this->addSql('ALTER TABLE smfn_api_key DROP project_id');
        $this->addSql('DROP INDEX title_region_country_period');
        $this->addSql('CREATE UNIQUE INDEX title_region_country ON smfn_subscription_plan (title, region, country)');
    }
}
