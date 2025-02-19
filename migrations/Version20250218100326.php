<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218100326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_settings ADD instance_id INT NOT NULL');
        $this->addSql('ALTER TABLE newsletter_settings ADD CONSTRAINT FK_384766AF3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('CREATE INDEX IDX_384766AF3A51721D ON newsletter_settings (instance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_settings DROP FOREIGN KEY FK_384766AF3A51721D');
        $this->addSql('DROP INDEX IDX_384766AF3A51721D ON newsletter_settings');
        $this->addSql('ALTER TABLE newsletter_settings DROP instance_id');
    }
}
