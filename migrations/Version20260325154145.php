<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260325154145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website ADD template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE75DA0FB8 FOREIGN KEY (template_id) REFERENCES _template (id)');
        $this->addSql('CREATE INDEX IDX_476F5DE75DA0FB8 ON website (template_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE75DA0FB8');
        $this->addSql('DROP INDEX IDX_476F5DE75DA0FB8 ON website');
        $this->addSql('ALTER TABLE website DROP template_id');
    }
}
