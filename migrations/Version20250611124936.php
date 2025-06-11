<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611124936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE webshop_shipping_methods (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(50) DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_15CA93593A51721D (instance_id), INDEX IDX_15CA9359B03A8386 (created_by_id), INDEX IDX_15CA9359896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE webshop_shipping_methods ADD CONSTRAINT FK_15CA93593A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE webshop_shipping_methods ADD CONSTRAINT FK_15CA9359B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE webshop_shipping_methods ADD CONSTRAINT FK_15CA9359896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE webshop_shipping_methods DROP FOREIGN KEY FK_15CA93593A51721D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE webshop_shipping_methods DROP FOREIGN KEY FK_15CA9359B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE webshop_shipping_methods DROP FOREIGN KEY FK_15CA9359896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE webshop_shipping_methods
        SQL);
    }
}
