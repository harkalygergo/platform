<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260210123259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website_post ADD featured_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE website_post ADD CONSTRAINT FK_588F85F93569D950 FOREIGN KEY (featured_image_id) REFERENCES website_media (id)');
        $this->addSql('CREATE INDEX IDX_588F85F93569D950 ON website_post (featured_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website_post DROP FOREIGN KEY FK_588F85F93569D950');
        $this->addSql('DROP INDEX IDX_588F85F93569D950 ON website_post');
        $this->addSql('ALTER TABLE website_post DROP featured_image_id');
    }
}
