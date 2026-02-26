<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226170108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ecom_product_website (product_id INT NOT NULL, website_id INT NOT NULL, INDEX IDX_B4B8D5D04584665A (product_id), INDEX IDX_B4B8D5D018F45C82 (website_id), PRIMARY KEY (product_id, website_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE ecom_product_website ADD CONSTRAINT FK_B4B8D5D04584665A FOREIGN KEY (product_id) REFERENCES ecom_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ecom_product_website ADD CONSTRAINT FK_B4B8D5D018F45C82 FOREIGN KEY (website_id) REFERENCES website (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecom_product_website DROP FOREIGN KEY FK_B4B8D5D04584665A');
        $this->addSql('ALTER TABLE ecom_product_website DROP FOREIGN KEY FK_B4B8D5D018F45C82');
        $this->addSql('DROP TABLE ecom_product_website');
    }
}
