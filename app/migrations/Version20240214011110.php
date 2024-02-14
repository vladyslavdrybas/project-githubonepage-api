<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214011110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE smfn_user ADD subscription_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN smfn_user.subscription_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE smfn_user ADD CONSTRAINT FK_A8C5186E9A1887DC FOREIGN KEY (subscription_id) REFERENCES smfn_subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A8C5186E9A1887DC ON smfn_user (subscription_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE smfn_user DROP CONSTRAINT FK_A8C5186E9A1887DC');
        $this->addSql('DROP INDEX IDX_A8C5186E9A1887DC');
        $this->addSql('ALTER TABLE smfn_user DROP subscription_id');
    }
}
