<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416112314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE block (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_831B97223A51721D (instance_id), INDEX IDX_831B9722B03A8386 (created_by_id), INDEX IDX_831B9722896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE block ADD CONSTRAINT FK_831B97223A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE block ADD CONSTRAINT FK_831B9722B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE block ADD CONSTRAINT FK_831B9722896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE block DROP FOREIGN KEY FK_831B97223A51721D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE block DROP FOREIGN KEY FK_831B9722B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE block DROP FOREIGN KEY FK_831B9722896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE block
        SQL);
    }
}
