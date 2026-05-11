<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260511170131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_account (id INT AUTO_INCREMENT NOT NULL, prefix VARCHAR(64) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, instance_id INT DEFAULT NULL, service_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_C0F63E6B3A51721D (instance_id), INDEX IDX_C0F63E6BED5CA9E6 (service_id), INDEX IDX_C0F63E6BB03A8386 (created_by_id), INDEX IDX_C0F63E6B896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE email_account ADD CONSTRAINT FK_C0F63E6B3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE email_account ADD CONSTRAINT FK_C0F63E6BED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE email_account ADD CONSTRAINT FK_C0F63E6BB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE email_account ADD CONSTRAINT FK_C0F63E6B896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email_account DROP FOREIGN KEY FK_C0F63E6B3A51721D');
        $this->addSql('ALTER TABLE email_account DROP FOREIGN KEY FK_C0F63E6BED5CA9E6');
        $this->addSql('ALTER TABLE email_account DROP FOREIGN KEY FK_C0F63E6BB03A8386');
        $this->addSql('ALTER TABLE email_account DROP FOREIGN KEY FK_C0F63E6B896DBBDE');
        $this->addSql('DROP TABLE email_account');
    }
}
