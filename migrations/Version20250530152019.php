<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530152019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE media ADD original_name VARCHAR(255) NOT NULL, ADD is_public TINYINT(1) NOT NULL, CHANGE media_type type VARCHAR(32) NOT NULL, CHANGE media_url path LONGTEXT NOT NULL, CHANGE media_size size INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE media DROP original_name, DROP is_public, CHANGE type media_type VARCHAR(32) NOT NULL, CHANGE path media_url LONGTEXT NOT NULL, CHANGE size media_size INT NOT NULL
        SQL);
    }
}
