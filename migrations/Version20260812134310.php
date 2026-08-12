<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260812134310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, first_name VARCHAR(64) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, phone VARCHAR(32) DEFAULT NULL, email VARCHAR(128) DEFAULT NULL, billing_country VARCHAR(64) DEFAULT NULL, billing_zip VARCHAR(16) DEFAULT NULL, billing_settlement VARCHAR(128) DEFAULT NULL, billing_address VARCHAR(255) DEFAULT NULL, shipping_country VARCHAR(64) DEFAULT NULL, shipping_zip VARCHAR(16) DEFAULT NULL, shipping_settlement VARCHAR(128) DEFAULT NULL, shipping_address VARCHAR(255) DEFAULT NULL, source VARCHAR(64) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, instance_id INT NOT NULL, customer_id INT DEFAULT NULL, created_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_81398E09F85E0677 (username), INDEX IDX_81398E093A51721D (instance_id), UNIQUE INDEX UNIQ_81398E099395C3F3 (customer_id), INDEX IDX_81398E09B03A8386 (created_by_id), INDEX IDX_81398E09896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E093A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E099395C3F3 FOREIGN KEY (customer_id) REFERENCES client (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E093A51721D');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E099395C3F3');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09B03A8386');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09896DBBDE');
        $this->addSql('DROP TABLE customer');
    }
}
