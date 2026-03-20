<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260319175103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD payment_method_id INT DEFAULT NULL, DROP payment_method');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993985AA1164F FOREIGN KEY (payment_method_id) REFERENCES webshop_payment_methods (id)');
        $this->addSql('CREATE INDEX IDX_F52993985AA1164F ON `order` (payment_method_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993985AA1164F');
        $this->addSql('DROP INDEX IDX_F52993985AA1164F ON `order`');
        $this->addSql('ALTER TABLE `order` ADD payment_method VARCHAR(255) NOT NULL, DROP payment_method_id');
    }
}
