<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226120528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecom_product ADD instance_id INT DEFAULT NULL, CHANGE vat_rate vat_rate NUMERIC(5, 2) DEFAULT 27 NOT NULL');
        $this->addSql('ALTER TABLE ecom_product ADD CONSTRAINT FK_4DF22EF93A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('CREATE INDEX IDX_4DF22EF93A51721D ON ecom_product (instance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecom_product DROP FOREIGN KEY FK_4DF22EF93A51721D');
        $this->addSql('DROP INDEX IDX_4DF22EF93A51721D ON ecom_product');
        $this->addSql('ALTER TABLE ecom_product DROP instance_id, CHANGE vat_rate vat_rate NUMERIC(5, 2) DEFAULT \'27.00\' NOT NULL');
    }
}
