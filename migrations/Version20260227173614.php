<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227173614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecom_product ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ecom_product ADD CONSTRAINT FK_4DF22EF912469DE2 FOREIGN KEY (category_id) REFERENCES ecom_product_category (id)');
        $this->addSql('CREATE INDEX IDX_4DF22EF912469DE2 ON ecom_product (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecom_product DROP FOREIGN KEY FK_4DF22EF912469DE2');
        $this->addSql('DROP INDEX IDX_4DF22EF912469DE2 ON ecom_product');
        $this->addSql('ALTER TABLE ecom_product DROP category_id');
    }
}
