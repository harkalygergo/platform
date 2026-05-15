<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260515160116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visitor_log ADD instance_id INT DEFAULT NULL AFTER `id`');
        $this->addSql('ALTER TABLE visitor_log ADD CONSTRAINT FK_D7EF165D3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('CREATE INDEX IDX_D7EF165D3A51721D ON visitor_log (instance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visitor_log DROP FOREIGN KEY FK_D7EF165D3A51721D');
        $this->addSql('DROP INDEX IDX_D7EF165D3A51721D ON visitor_log');
        $this->addSql('ALTER TABLE visitor_log DROP instance_id');
    }
}
