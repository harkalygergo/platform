<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250527161733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE website_post_category (website_post_id INT NOT NULL, website_category_id INT NOT NULL, INDEX IDX_9781B25FE7A77F88 (website_post_id), INDEX IDX_9781B25F59C3646B (website_category_id), PRIMARY KEY(website_post_id, website_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post_category ADD CONSTRAINT FK_9781B25FE7A77F88 FOREIGN KEY (website_post_id) REFERENCES website_post (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post_category ADD CONSTRAINT FK_9781B25F59C3646B FOREIGN KEY (website_category_id) REFERENCES website_category (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post_category DROP FOREIGN KEY FK_9781B25FE7A77F88
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post_category DROP FOREIGN KEY FK_9781B25F59C3646B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE website_post_category
        SQL);
    }
}
