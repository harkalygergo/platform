<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260507135832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY `FK_476F5DE7D78119FD`');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY `FK_476F5DE7F98F144A`');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7D78119FD FOREIGN KEY (favicon_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7F98F144A FOREIGN KEY (logo_id) REFERENCES media (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7D78119FD');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7F98F144A');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT `FK_476F5DE7D78119FD` FOREIGN KEY (favicon_id) REFERENCES website_media (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT `FK_476F5DE7F98F144A` FOREIGN KEY (logo_id) REFERENCES website_media (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
