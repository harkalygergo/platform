<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313114512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE webshop (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, ftphost VARCHAR(64) DEFAULT NULL, ftpuser VARCHAR(64) DEFAULT NULL, ftppassword VARCHAR(64) DEFAULT NULL, ftppath VARCHAR(128) DEFAULT NULL, domain VARCHAR(64) NOT NULL, name VARCHAR(320) DEFAULT NULL, slogan VARCHAR(320) DEFAULT NULL, description VARCHAR(320) DEFAULT NULL, phone VARCHAR(32) DEFAULT NULL, email VARCHAR(64) DEFAULT NULL, address VARCHAR(320) DEFAULT NULL, facebook VARCHAR(64) DEFAULT NULL, twitter VARCHAR(64) DEFAULT NULL, instagram VARCHAR(64) DEFAULT NULL, linkedin VARCHAR(64) DEFAULT NULL, youtube VARCHAR(64) DEFAULT NULL, tiktok VARCHAR(64) DEFAULT NULL, threads VARCHAR(64) DEFAULT NULL, bluesky VARCHAR(64) DEFAULT NULL, theme VARCHAR(16) DEFAULT NULL, language VARCHAR(8) DEFAULT NULL, charset VARCHAR(16) DEFAULT \'utf-8\', title VARCHAR(128) DEFAULT NULL, meta_description VARCHAR(320) DEFAULT NULL, meta_keywords VARCHAR(320) DEFAULT NULL, meta_author VARCHAR(64) DEFAULT NULL, meta_robots VARCHAR(16) DEFAULT NULL, header_css LONGTEXT DEFAULT NULL, header_js LONGTEXT DEFAULT NULL, header_html LONGTEXT DEFAULT NULL, body_top_html LONGTEXT DEFAULT NULL, footer_js LONGTEXT DEFAULT NULL, footer_html LONGTEXT DEFAULT NULL, google_api_key VARCHAR(128) DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, favicon_id INT DEFAULT NULL, logo_id INT DEFAULT NULL, INDEX IDX_824618A13A51721D (instance_id), INDEX IDX_824618A1B03A8386 (created_by_id), INDEX IDX_824618A1896DBBDE (updated_by_id), INDEX IDX_824618A1D78119FD (favicon_id), INDEX IDX_824618A1F98F144A (logo_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE shop_webshop_product (webshop_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_6E00DEDCA35F8E19 (webshop_id), INDEX IDX_6E00DEDC4584665A (product_id), PRIMARY KEY (webshop_id, product_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE shop_webshop_product_categories (webshop_id INT NOT NULL, product_category_id INT NOT NULL, INDEX IDX_D844C11FA35F8E19 (webshop_id), INDEX IDX_D844C11FBE6903FD (product_category_id), PRIMARY KEY (webshop_id, product_category_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE webshop ADD CONSTRAINT FK_824618A13A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE webshop ADD CONSTRAINT FK_824618A1B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE webshop ADD CONSTRAINT FK_824618A1896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE webshop ADD CONSTRAINT FK_824618A1D78119FD FOREIGN KEY (favicon_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE webshop ADD CONSTRAINT FK_824618A1F98F144A FOREIGN KEY (logo_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE shop_webshop_product ADD CONSTRAINT FK_6E00DEDCA35F8E19 FOREIGN KEY (webshop_id) REFERENCES webshop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_webshop_product ADD CONSTRAINT FK_6E00DEDC4584665A FOREIGN KEY (product_id) REFERENCES ecom_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_webshop_product_categories ADD CONSTRAINT FK_D844C11FA35F8E19 FOREIGN KEY (webshop_id) REFERENCES webshop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_webshop_product_categories ADD CONSTRAINT FK_D844C11FBE6903FD FOREIGN KEY (product_category_id) REFERENCES ecom_product_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webshop DROP FOREIGN KEY FK_824618A13A51721D');
        $this->addSql('ALTER TABLE webshop DROP FOREIGN KEY FK_824618A1B03A8386');
        $this->addSql('ALTER TABLE webshop DROP FOREIGN KEY FK_824618A1896DBBDE');
        $this->addSql('ALTER TABLE webshop DROP FOREIGN KEY FK_824618A1D78119FD');
        $this->addSql('ALTER TABLE webshop DROP FOREIGN KEY FK_824618A1F98F144A');
        $this->addSql('ALTER TABLE shop_webshop_product DROP FOREIGN KEY FK_6E00DEDCA35F8E19');
        $this->addSql('ALTER TABLE shop_webshop_product DROP FOREIGN KEY FK_6E00DEDC4584665A');
        $this->addSql('ALTER TABLE shop_webshop_product_categories DROP FOREIGN KEY FK_D844C11FA35F8E19');
        $this->addSql('ALTER TABLE shop_webshop_product_categories DROP FOREIGN KEY FK_D844C11FBE6903FD');
        $this->addSql('DROP TABLE webshop');
        $this->addSql('DROP TABLE shop_webshop_product');
        $this->addSql('DROP TABLE shop_webshop_product_categories');
    }
}
