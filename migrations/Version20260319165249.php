<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260319165249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webshop_payment_methods ADD card_status TINYINT DEFAULT 0 NOT NULL, ADD card_base_url_test VARCHAR(255) NOT NULL, ADD card_customer_test VARCHAR(255) NOT NULL, ADD card_terminal_test VARCHAR(255) NOT NULL, ADD card_username_test VARCHAR(255) NOT NULL, ADD card_password_test VARCHAR(255) NOT NULL, ADD card_base_url_live VARCHAR(255) NOT NULL, ADD card_customer_live VARCHAR(255) NOT NULL, ADD card_terminal_live VARCHAR(255) NOT NULL, ADD card_username_live VARCHAR(255) NOT NULL, ADD card_password_live VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webshop_payment_methods DROP card_status, DROP card_base_url_test, DROP card_customer_test, DROP card_terminal_test, DROP card_username_test, DROP card_password_test, DROP card_base_url_live, DROP card_customer_live, DROP card_terminal_live, DROP card_username_live, DROP card_password_live');
    }
}
