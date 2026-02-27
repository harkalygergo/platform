<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227173008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ecom_product_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, parent_category_id INT DEFAULT NULL, instance_id INT DEFAULT NULL, INDEX IDX_BE026B46796A8F92 (parent_category_id), INDEX IDX_BE026B463A51721D (instance_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ecom_product_category_website (product_category_id INT NOT NULL, website_id INT NOT NULL, INDEX IDX_B75B7891BE6903FD (product_category_id), INDEX IDX_B75B789118F45C82 (website_id), PRIMARY KEY (product_category_id, website_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE ecom_product_category ADD CONSTRAINT FK_BE026B46796A8F92 FOREIGN KEY (parent_category_id) REFERENCES ecom_product_category (id)');
        $this->addSql('ALTER TABLE ecom_product_category ADD CONSTRAINT FK_BE026B463A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE ecom_product_category_website ADD CONSTRAINT FK_B75B7891BE6903FD FOREIGN KEY (product_category_id) REFERENCES ecom_product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ecom_product_category_website ADD CONSTRAINT FK_B75B789118F45C82 FOREIGN KEY (website_id) REFERENCES website (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecom_product_category DROP FOREIGN KEY FK_BE026B46796A8F92');
        $this->addSql('ALTER TABLE ecom_product_category DROP FOREIGN KEY FK_BE026B463A51721D');
        $this->addSql('ALTER TABLE ecom_product_category_website DROP FOREIGN KEY FK_B75B7891BE6903FD');
        $this->addSql('ALTER TABLE ecom_product_category_website DROP FOREIGN KEY FK_B75B789118F45C82');
        $this->addSql('DROP TABLE ecom_product_category');
        $this->addSql('DROP TABLE ecom_product_category_website');
    }
}
