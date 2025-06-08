<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250608131301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE website_media (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, original_name VARCHAR(255) NOT NULL, type VARCHAR(32) NOT NULL, path LONGTEXT NOT NULL, size INT NOT NULL, description LONGTEXT DEFAULT NULL, is_public TINYINT(1) NOT NULL, INDEX IDX_3D4611C03A51721D (instance_id), INDEX IDX_3D4611C0B03A8386 (created_by_id), INDEX IDX_3D4611C0896DBBDE (updated_by_id), INDEX IDX_3D4611C018F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_media ADD CONSTRAINT FK_3D4611C03A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_media ADD CONSTRAINT FK_3D4611C0B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_media ADD CONSTRAINT FK_3D4611C0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_media ADD CONSTRAINT FK_3D4611C018F45C82 FOREIGN KEY (website_id) REFERENCES website (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE website_media DROP FOREIGN KEY FK_3D4611C03A51721D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_media DROP FOREIGN KEY FK_3D4611C0B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_media DROP FOREIGN KEY FK_3D4611C0896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website_media DROP FOREIGN KEY FK_3D4611C018F45C82
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE website_media
        SQL);
    }
}
