<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260303172425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecom_product ADD main_image_id INT DEFAULT NULL, DROP main_image');
        $this->addSql('ALTER TABLE ecom_product ADD CONSTRAINT FK_4DF22EF9E4873418 FOREIGN KEY (main_image_id) REFERENCES media (id)');
        $this->addSql('CREATE INDEX IDX_4DF22EF9E4873418 ON ecom_product (main_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecom_product DROP FOREIGN KEY FK_4DF22EF9E4873418');
        $this->addSql('DROP INDEX IDX_4DF22EF9E4873418 ON ecom_product');
        $this->addSql('ALTER TABLE ecom_product ADD main_image VARCHAR(255) DEFAULT NULL, DROP main_image_id');
    }
}
