<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260827170449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website ADD homepage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7571EDDA FOREIGN KEY (homepage_id) REFERENCES cms_page (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_476F5DE7571EDDA ON website (homepage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7571EDDA');
        $this->addSql('DROP INDEX IDX_476F5DE7571EDDA ON website');
        $this->addSql('ALTER TABLE website DROP homepage_id');
    }
}
