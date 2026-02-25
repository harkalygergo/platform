<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224152731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ecom_product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, sku VARCHAR(100) DEFAULT NULL, barcode VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, `lead` LONGTEXT DEFAULT NULL, price NUMERIC(12, 2) NOT NULL, sale_price NUMERIC(12, 2) DEFAULT NULL, sale_start_date DATETIME DEFAULT NULL, sale_end_date DATETIME DEFAULT NULL, cost_price NUMERIC(12, 2) DEFAULT NULL, currency VARCHAR(3) DEFAULT \'HUF\' NOT NULL, vat_rate NUMERIC(5, 2) DEFAULT 27 NOT NULL, quantity INT DEFAULT 0 NOT NULL, min_quantity INT DEFAULT 1 NOT NULL, max_quantity INT DEFAULT NULL, low_stock_threshold INT DEFAULT 5 NOT NULL, track_inventory TINYINT DEFAULT 1 NOT NULL, allow_backorder TINYINT DEFAULT 0 NOT NULL, weight NUMERIC(10, 3) DEFAULT NULL, width NUMERIC(10, 2) DEFAULT NULL, height NUMERIC(10, 2) DEFAULT NULL, depth NUMERIC(10, 2) DEFAULT NULL, weight_unit VARCHAR(10) DEFAULT \'kg\' NOT NULL, dimension_unit VARCHAR(10) DEFAULT \'cm\' NOT NULL, main_image VARCHAR(255) DEFAULT NULL, images JSON DEFAULT NULL, status VARCHAR(20) DEFAULT \'draft\' NOT NULL, is_active TINYINT DEFAULT 1 NOT NULL, is_featured TINYINT DEFAULT 0 NOT NULL, is_new TINYINT DEFAULT 0 NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, published_at DATETIME DEFAULT NULL, sort_order INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_4DF22EF9989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ecom_product');
    }
}
