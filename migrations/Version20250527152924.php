<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250527152924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE website_category (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_robots VARCHAR(32) DEFAULT NULL, meta_canonical VARCHAR(255) DEFAULT NULL, INDEX IDX_D75B504C3A51721D (instance_id), INDEX IDX_D75B504CB03A8386 (created_by_id), INDEX IDX_D75B504C896DBBDE (updated_by_id), INDEX IDX_D75B504C18F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_category ADD CONSTRAINT FK_D75B504C3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_category ADD CONSTRAINT FK_D75B504CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_category ADD CONSTRAINT FK_D75B504C896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_category ADD CONSTRAINT FK_D75B504C18F45C82 FOREIGN KEY (website_id) REFERENCES website (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE website_category DROP FOREIGN KEY FK_D75B504C3A51721D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_category DROP FOREIGN KEY FK_D75B504CB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_category DROP FOREIGN KEY FK_D75B504C896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_category DROP FOREIGN KEY FK_D75B504C18F45C82
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE website_category
        SQL);
    }
}
