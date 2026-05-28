<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260528163952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_field ADD instance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE form_field ADD CONSTRAINT FK_D8B2E19B3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('CREATE INDEX IDX_D8B2E19B3A51721D ON form_field (instance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE form_field DROP FOREIGN KEY FK_D8B2E19B3A51721D');
        $this->addSql('DROP INDEX IDX_D8B2E19B3A51721D ON form_field');
        $this->addSql('ALTER TABLE form_field DROP instance_id');
    }
}
