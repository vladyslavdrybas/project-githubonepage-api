<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214005358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE smfn_offer (id UUID NOT NULL, title VARCHAR(36) NOT NULL, description VARCHAR(256) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN smfn_offer.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE smfn_subscription (id UUID NOT NULL, title VARCHAR(36) NOT NULL, description VARCHAR(256) DEFAULT NULL, region VARCHAR(3) DEFAULT \'ALL\' NOT NULL, country VARCHAR(3) DEFAULT \'ALL\' NOT NULL, currency VARCHAR(3) DEFAULT \'USD\' NOT NULL, price INT NOT NULL, period VARCHAR(7) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN smfn_subscription.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE smfn_subscription_offer (subscription_id UUID NOT NULL, offer_id UUID NOT NULL, PRIMARY KEY(subscription_id, offer_id))');
        $this->addSql('CREATE INDEX IDX_B8CB30209A1887DC ON smfn_subscription_offer (subscription_id)');
        $this->addSql('CREATE INDEX IDX_B8CB302053C674EE ON smfn_subscription_offer (offer_id)');
        $this->addSql('COMMENT ON COLUMN smfn_subscription_offer.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN smfn_subscription_offer.offer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smfn_subscription_offer ADD CONSTRAINT FK_B8CB30209A1887DC FOREIGN KEY (subscription_id) REFERENCES smfn_subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE smfn_subscription_offer ADD CONSTRAINT FK_B8CB302053C674EE FOREIGN KEY (offer_id) REFERENCES smfn_offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE smfn_subscription_offer DROP CONSTRAINT FK_B8CB30209A1887DC');
        $this->addSql('ALTER TABLE smfn_subscription_offer DROP CONSTRAINT FK_B8CB302053C674EE');
        $this->addSql('DROP TABLE smfn_offer');
        $this->addSql('DROP TABLE smfn_subscription');
        $this->addSql('DROP TABLE smfn_subscription_offer');
    }
}
