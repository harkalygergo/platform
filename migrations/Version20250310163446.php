<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310163446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE website (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, ftphost LONGTEXT NOT NULL, ftpuser LONGTEXT NOT NULL, ftppassword LONGTEXT NOT NULL, ftppath LONGTEXT NOT NULL, domain LONGTEXT NOT NULL, name LONGTEXT NOT NULL, description LONGTEXT NOT NULL, theme LONGTEXT NOT NULL, language LONGTEXT NOT NULL, charset LONGTEXT NOT NULL, title LONGTEXT NOT NULL, meta_description LONGTEXT NOT NULL, meta_keywords LONGTEXT NOT NULL, meta_author LONGTEXT NOT NULL, meta_robots LONGTEXT NOT NULL, INDEX IDX_476F5DE73A51721D (instance_id), INDEX IDX_476F5DE7B03A8386 (created_by_id), INDEX IDX_476F5DE7896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE73A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE73A51721D');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7B03A8386');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7896DBBDE');
        $this->addSql('DROP TABLE website');
    }
}
