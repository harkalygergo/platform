<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214132728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE form (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, code LONGTEXT NOT NULL, notification_email LONGTEXT NOT NULL, INDEX IDX_5288FD4F3A51721D (instance_id), INDEX IDX_5288FD4FB03A8386 (created_by_id), INDEX IDX_5288FD4F896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_subscriber (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, email LONGTEXT NOT NULL, INDEX IDX_401562C33A51721D (instance_id), INDEX IDX_401562C3B03A8386 (created_by_id), INDEX IDX_401562C3896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4F3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE newsletter_subscriber ADD CONSTRAINT FK_401562C33A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE newsletter_subscriber ADD CONSTRAINT FK_401562C3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE newsletter_subscriber ADD CONSTRAINT FK_401562C3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form DROP FOREIGN KEY FK_5288FD4F3A51721D');
        $this->addSql('ALTER TABLE form DROP FOREIGN KEY FK_5288FD4FB03A8386');
        $this->addSql('ALTER TABLE form DROP FOREIGN KEY FK_5288FD4F896DBBDE');
        $this->addSql('ALTER TABLE newsletter_subscriber DROP FOREIGN KEY FK_401562C33A51721D');
        $this->addSql('ALTER TABLE newsletter_subscriber DROP FOREIGN KEY FK_401562C3B03A8386');
        $this->addSql('ALTER TABLE newsletter_subscriber DROP FOREIGN KEY FK_401562C3896DBBDE');
        $this->addSql('DROP TABLE form');
        $this->addSql('DROP TABLE newsletter_subscriber');
    }
}
