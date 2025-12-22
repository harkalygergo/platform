<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251221222914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD website_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA718F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA718F45C82 ON event (website_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA718F45C82');
        $this->addSql('DROP INDEX IDX_3BAE0AA718F45C82 ON event');
        $this->addSql('ALTER TABLE event DROP website_id');
    }
}
