<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211180110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE smfn_api_key (api_key VARCHAR(64) NOT NULL, title VARCHAR(36) NOT NULL, owner VARCHAR(36) NOT NULL, project VARCHAR(36) NOT NULL, endDate TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(api_key))');
        $this->addSql('CREATE TABLE smfn_refresh_tokens (refresh_token VARCHAR(128) NOT NULL, username VARCHAR(36) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_48E4CBE8C74F2195 ON smfn_refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE smfn_user (roles JSON NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(100) NOT NULL, firstname VARCHAR(100) DEFAULT NULL, lastname VARCHAR(100) DEFAULT NULL, password VARCHAR(100) NOT NULL, is_email_verified BOOLEAN DEFAULT false NOT NULL, is_active BOOLEAN DEFAULT true NOT NULL, is_banned BOOLEAN DEFAULT false NOT NULL, is_deleted BOOLEAN DEFAULT false NOT NULL, id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A8C5186EE7927C74 ON smfn_user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A8C5186EF85E0677 ON smfn_user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE smfn_api_key');
        $this->addSql('DROP TABLE smfn_refresh_tokens');
        $this->addSql('DROP TABLE smfn_user');
    }
}
