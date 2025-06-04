<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604163459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE menu ADD parent_id INT DEFAULT NULL, ADD position INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE menu ADD CONSTRAINT FK_7D053A93727ACA70 FOREIGN KEY (parent_id) REFERENCES menu (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7D053A93727ACA70 ON menu (parent_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7D053A93727ACA70 ON menu
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE menu DROP parent_id, DROP position
        SQL);
    }
}
