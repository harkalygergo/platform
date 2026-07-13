<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260713153116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE form_fill (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(255) DEFAULT NULL, data JSON NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, instance_id INT DEFAULT NULL, form_id INT DEFAULT NULL, created_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_61628AA33A51721D (instance_id), INDEX IDX_61628AA35FF69B7D (form_id), INDEX IDX_61628AA3B03A8386 (created_by_id), INDEX IDX_61628AA3896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE form_fill ADD CONSTRAINT FK_61628AA33A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE form_fill ADD CONSTRAINT FK_61628AA35FF69B7D FOREIGN KEY (form_id) REFERENCES form (id)');
        $this->addSql('ALTER TABLE form_fill ADD CONSTRAINT FK_61628AA3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE form_fill ADD CONSTRAINT FK_61628AA3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_fill DROP FOREIGN KEY FK_61628AA33A51721D');
        $this->addSql('ALTER TABLE form_fill DROP FOREIGN KEY FK_61628AA35FF69B7D');
        $this->addSql('ALTER TABLE form_fill DROP FOREIGN KEY FK_61628AA3B03A8386');
        $this->addSql('ALTER TABLE form_fill DROP FOREIGN KEY FK_61628AA3896DBBDE');
        $this->addSql('DROP TABLE form_fill');
    }
}
