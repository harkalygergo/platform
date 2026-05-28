<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260528162748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE form_field (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, name VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, position INT NOT NULL, is_required TINYINT NOT NULL, default_value VARCHAR(255) NOT NULL, placeholder VARCHAR(255) NOT NULL, help_text VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, form_id INT DEFAULT NULL, created_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_D8B2E19B5FF69B7D (form_id), INDEX IDX_D8B2E19BB03A8386 (created_by_id), INDEX IDX_D8B2E19B896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE form_field ADD CONSTRAINT FK_D8B2E19B5FF69B7D FOREIGN KEY (form_id) REFERENCES form (id)');
        $this->addSql('ALTER TABLE form_field ADD CONSTRAINT FK_D8B2E19BB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE form_field ADD CONSTRAINT FK_D8B2E19B896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_field DROP FOREIGN KEY FK_D8B2E19B5FF69B7D');
        $this->addSql('ALTER TABLE form_field DROP FOREIGN KEY FK_D8B2E19BB03A8386');
        $this->addSql('ALTER TABLE form_field DROP FOREIGN KEY FK_D8B2E19B896DBBDE');
        $this->addSql('DROP TABLE form_field');
    }
}
