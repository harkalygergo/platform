<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617115425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE website ADD phone_number VARCHAR(32) DEFAULT NULL, ADD email VARCHAR(64) DEFAULT NULL, ADD address VARCHAR(320) DEFAULT NULL, ADD facebook VARCHAR(64) DEFAULT NULL, ADD twitter VARCHAR(64) DEFAULT NULL, ADD instagram VARCHAR(64) DEFAULT NULL, ADD linkedin VARCHAR(64) DEFAULT NULL, ADD youtube VARCHAR(64) DEFAULT NULL, ADD tiktok VARCHAR(64) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE website DROP phone_number, DROP email, DROP address, DROP facebook, DROP twitter, DROP instagram, DROP linkedin, DROP youtube, DROP tiktok
        SQL);
    }
}
