<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260320154820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webshop_payment_methods CHANGE card_base_url_test card_base_url_test VARCHAR(255) DEFAULT NULL, CHANGE card_customer_test card_customer_test VARCHAR(255) DEFAULT NULL, CHANGE card_terminal_test card_terminal_test VARCHAR(255) DEFAULT NULL, CHANGE card_username_test card_username_test VARCHAR(255) DEFAULT NULL, CHANGE card_password_test card_password_test VARCHAR(255) DEFAULT NULL, CHANGE card_base_url_live card_base_url_live VARCHAR(255) DEFAULT NULL, CHANGE card_customer_live card_customer_live VARCHAR(255) DEFAULT NULL, CHANGE card_terminal_live card_terminal_live VARCHAR(255) DEFAULT NULL, CHANGE card_username_live card_username_live VARCHAR(255) DEFAULT NULL, CHANGE card_password_live card_password_live VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webshop_payment_methods CHANGE card_base_url_test card_base_url_test VARCHAR(255) NOT NULL, CHANGE card_customer_test card_customer_test VARCHAR(255) NOT NULL, CHANGE card_terminal_test card_terminal_test VARCHAR(255) NOT NULL, CHANGE card_username_test card_username_test VARCHAR(255) NOT NULL, CHANGE card_password_test card_password_test VARCHAR(255) NOT NULL, CHANGE card_base_url_live card_base_url_live VARCHAR(255) NOT NULL, CHANGE card_customer_live card_customer_live VARCHAR(255) NOT NULL, CHANGE card_terminal_live card_terminal_live VARCHAR(255) NOT NULL, CHANGE card_username_live card_username_live VARCHAR(255) NOT NULL, CHANGE card_password_live card_password_live VARCHAR(255) NOT NULL');
    }
}
