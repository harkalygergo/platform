<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250513082620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE instance_feed (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', user_id INT NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_B4A14603A51721D (instance_id), INDEX IDX_B4A1460B03A8386 (created_by_id), INDEX IDX_B4A1460896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE instance_feed ADD CONSTRAINT FK_B4A14603A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE instance_feed ADD CONSTRAINT FK_B4A1460B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE instance_feed ADD CONSTRAINT FK_B4A1460896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE instance_feed DROP FOREIGN KEY FK_B4A14603A51721D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE instance_feed DROP FOREIGN KEY FK_B4A1460B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE instance_feed DROP FOREIGN KEY FK_B4A1460896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE instance_feed
        SQL);
    }
}
