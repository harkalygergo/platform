<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260515132033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE visitor_log (id INT AUTO_INCREMENT NOT NULL, visited_at DATETIME NOT NULL, url VARCHAR(2048) NOT NULL, referrer VARCHAR(255) DEFAULT NULL, ip_address VARCHAR(45) DEFAULT NULL, user_agent LONGTEXT DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, session_id VARCHAR(255) DEFAULT NULL, visitor_id VARCHAR(255) DEFAULT NULL, route VARCHAR(255) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_id INT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE visitor_log');
    }
}
