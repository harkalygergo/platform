<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209164608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website_page ADD featured_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT FK_160F5F543569D950 FOREIGN KEY (featured_image_id) REFERENCES website_media (id)');
        $this->addSql('CREATE INDEX IDX_160F5F543569D950 ON website_page (featured_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY FK_160F5F543569D950');
        $this->addSql('DROP INDEX IDX_160F5F543569D950 ON website_page');
        $this->addSql('ALTER TABLE website_page DROP featured_image_id');
    }
}
