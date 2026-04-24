<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260424114408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cms_page (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_robots VARCHAR(32) DEFAULT NULL, meta_canonical VARCHAR(255) DEFAULT NULL, homepage TINYINT DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, featured_image_id INT DEFAULT NULL, INDEX IDX_D39C1B5D3A51721D (instance_id), INDEX IDX_D39C1B5DB03A8386 (created_by_id), INDEX IDX_D39C1B5D896DBBDE (updated_by_id), INDEX IDX_D39C1B5D18F45C82 (website_id), INDEX IDX_D39C1B5D3569D950 (featured_image_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE cms_page ADD CONSTRAINT FK_D39C1B5D3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE cms_page ADD CONSTRAINT FK_D39C1B5DB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cms_page ADD CONSTRAINT FK_D39C1B5D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cms_page ADD CONSTRAINT FK_D39C1B5D18F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('ALTER TABLE cms_page ADD CONSTRAINT FK_D39C1B5D3569D950 FOREIGN KEY (featured_image_id) REFERENCES media (id)');

        // copy data from website_page to cms_page
        $this->addSql('INSERT INTO cms_page SELECT * FROM website_page;');

        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY `FK_160F5F5418F45C82`');
        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY `FK_160F5F543569D950`');
        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY `FK_160F5F543A51721D`');
        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY `FK_160F5F54896DBBDE`');
        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY `FK_160F5F54B03A8386`');
        $this->addSql('DROP TABLE website_page');
        $this->addSql('ALTER TABLE website ADD terms_page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7939D8D9A FOREIGN KEY (terms_page_id) REFERENCES cms_page (id)');
        $this->addSql('CREATE INDEX IDX_476F5DE7939D8D9A ON website (terms_page_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE website_page (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, content LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, meta_title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, meta_description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, meta_keywords VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, meta_robots VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, meta_canonical VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, homepage TINYINT DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, featured_image_id INT DEFAULT NULL, INDEX IDX_160F5F5418F45C82 (website_id), INDEX IDX_160F5F543569D950 (featured_image_id), INDEX IDX_160F5F543A51721D (instance_id), INDEX IDX_160F5F54896DBBDE (updated_by_id), INDEX IDX_160F5F54B03A8386 (created_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT `FK_160F5F5418F45C82` FOREIGN KEY (website_id) REFERENCES website (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT `FK_160F5F543569D950` FOREIGN KEY (featured_image_id) REFERENCES media (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT `FK_160F5F543A51721D` FOREIGN KEY (instance_id) REFERENCES instance (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT `FK_160F5F54896DBBDE` FOREIGN KEY (updated_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT `FK_160F5F54B03A8386` FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');

        // copy data from website_page to cms_page
        $this->addSql('INSERT INTO website_page SELECT * FROM cms_page;');

        $this->addSql('ALTER TABLE cms_page DROP FOREIGN KEY FK_D39C1B5D3A51721D');
        $this->addSql('ALTER TABLE cms_page DROP FOREIGN KEY FK_D39C1B5DB03A8386');
        $this->addSql('ALTER TABLE cms_page DROP FOREIGN KEY FK_D39C1B5D896DBBDE');
        $this->addSql('ALTER TABLE cms_page DROP FOREIGN KEY FK_D39C1B5D18F45C82');
        $this->addSql('ALTER TABLE cms_page DROP FOREIGN KEY FK_D39C1B5D3569D950');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7939D8D9A');
        $this->addSql('DROP INDEX IDX_476F5DE7939D8D9A ON website');
        $this->addSql('ALTER TABLE website DROP terms_page_id');
        $this->addSql('DROP TABLE cms_page');
    }
}
