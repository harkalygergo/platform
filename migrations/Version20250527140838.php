<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250527140838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE website_post (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_robots VARCHAR(32) DEFAULT NULL, meta_canonical VARCHAR(255) DEFAULT NULL, INDEX IDX_588F85F93A51721D (instance_id), INDEX IDX_588F85F9B03A8386 (created_by_id), INDEX IDX_588F85F9896DBBDE (updated_by_id), INDEX IDX_588F85F918F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post ADD CONSTRAINT FK_588F85F93A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post ADD CONSTRAINT FK_588F85F9B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post ADD CONSTRAINT FK_588F85F9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post ADD CONSTRAINT FK_588F85F918F45C82 FOREIGN KEY (website_id) REFERENCES website (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post DROP FOREIGN KEY FK_588F85F93A51721D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post DROP FOREIGN KEY FK_588F85F9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post DROP FOREIGN KEY FK_588F85F9896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_post DROP FOREIGN KEY FK_588F85F918F45C82
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE website_post
        SQL);
    }
}
