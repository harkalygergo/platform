<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260417153114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_email_template (id INT AUTO_INCREMENT NOT NULL, order_status VARCHAR(50) NOT NULL, subject VARCHAR(255) NOT NULL, plain_text_content LONGTEXT DEFAULT NULL, html_content LONGTEXT DEFAULT NULL, is_active TINYINT NOT NULL, instance_id INT NOT NULL, INDEX IDX_F476D07A3A51721D (instance_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE order_email_template ADD CONSTRAINT FK_F476D07A3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_email_template DROP FOREIGN KEY FK_F476D07A3A51721D');
        $this->addSql('DROP TABLE order_email_template');
    }
}
