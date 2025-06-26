<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626091057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE website ADD favicon_id INT DEFAULT NULL, ADD logo_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website ADD CONSTRAINT FK_476F5DE7D78119FD FOREIGN KEY (favicon_id) REFERENCES website_media (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website ADD CONSTRAINT FK_476F5DE7F98F144A FOREIGN KEY (logo_id) REFERENCES website_media (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_476F5DE7D78119FD ON website (favicon_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_476F5DE7F98F144A ON website (logo_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7D78119FD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7F98F144A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_476F5DE7D78119FD ON website
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_476F5DE7F98F144A ON website
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE website DROP favicon_id, DROP logo_id
        SQL);
    }
}
