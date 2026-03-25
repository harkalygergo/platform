<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260325155334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webshop ADD template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE webshop ADD CONSTRAINT FK_824618A15DA0FB8 FOREIGN KEY (template_id) REFERENCES _template (id)');
        $this->addSql('CREATE INDEX IDX_824618A15DA0FB8 ON webshop (template_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webshop DROP FOREIGN KEY FK_824618A15DA0FB8');
        $this->addSql('DROP INDEX IDX_824618A15DA0FB8 ON webshop');
        $this->addSql('ALTER TABLE webshop DROP template_id');
    }
}
