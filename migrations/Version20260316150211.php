<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316150211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ecom_product_media (id INT AUTO_INCREMENT NOT NULL, position INT DEFAULT 0 NOT NULL, label VARCHAR(255) DEFAULT NULL, product_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_F855549B4584665A (product_id), INDEX IDX_F855549BEA9FDD75 (media_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE ecom_product_media ADD CONSTRAINT FK_F855549B4584665A FOREIGN KEY (product_id) REFERENCES ecom_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ecom_product_media ADD CONSTRAINT FK_F855549BEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecom_product_media DROP FOREIGN KEY FK_F855549B4584665A');
        $this->addSql('ALTER TABLE ecom_product_media DROP FOREIGN KEY FK_F855549BEA9FDD75');
        $this->addSql('DROP TABLE ecom_product_media');
    }
}
