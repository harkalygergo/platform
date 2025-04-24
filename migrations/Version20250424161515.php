<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424161515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE api (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, description LONGTEXT NOT NULL, domain LONGTEXT NOT NULL, `key` LONGTEXT NOT NULL, secret LONGTEXT NOT NULL, INDEX IDX_AD05D80F3A51721D (instance_id), INDEX IDX_AD05D80FB03A8386 (created_by_id), INDEX IDX_AD05D80F896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE api ADD CONSTRAINT FK_AD05D80F3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE api ADD CONSTRAINT FK_AD05D80FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE api ADD CONSTRAINT FK_AD05D80F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE api DROP FOREIGN KEY FK_AD05D80F3A51721D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE api DROP FOREIGN KEY FK_AD05D80FB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE api DROP FOREIGN KEY FK_AD05D80F896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE api
        SQL);
    }
}
