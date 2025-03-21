<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321101044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE billing_profile (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, zip INT NOT NULL, settlement VARCHAR(64) NOT NULL, address VARCHAR(255) NOT NULL, vat VARCHAR(32) DEFAULT NULL, eu_vat VARCHAR(16) DEFAULT NULL, email VARCHAR(128) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, items JSON DEFAULT NULL, UNIQUE INDEX UNIQ_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, instance_id INT NOT NULL, name_prefix VARCHAR(8) DEFAULT NULL, first_name VARCHAR(64) NOT NULL, middle_name VARCHAR(32) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, birth_date DATE DEFAULT NULL, phone VARCHAR(32) DEFAULT NULL, email VARCHAR(128) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C74404553A51721D (instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, code LONGTEXT NOT NULL, notification_email LONGTEXT NOT NULL, INDEX IDX_5288FD4F3A51721D (instance_id), INDEX IDX_5288FD4FB03A8386 (created_by_id), INDEX IDX_5288FD4F896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instance (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, status INT NOT NULL, intranet LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4230B1DE7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instance_user (instance_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A59986823A51721D (instance_id), INDEX IDX_A5998682A76ED395 (user_id), PRIMARY KEY(instance_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instance_billing_profile (instance_id INT NOT NULL, billing_profile_id INT NOT NULL, INDEX IDX_6E58FFD93A51721D (instance_id), INDEX IDX_6E58FFD9409D7D29 (billing_profile_id), PRIMARY KEY(instance_id, billing_profile_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, instance_id INT NOT NULL, subject VARCHAR(255) NOT NULL, plain_text_content LONGTEXT DEFAULT NULL, html_content LONGTEXT DEFAULT NULL, INDEX IDX_7E8585C83A51721D (instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_settings (id INT AUTO_INCREMENT NOT NULL, instance_id INT NOT NULL, from_name VARCHAR(255) NOT NULL, from_email VARCHAR(255) NOT NULL, default_subject VARCHAR(255) NOT NULL, default_plain_text_content LONGTEXT DEFAULT NULL, default_html_content LONGTEXT DEFAULT NULL, default_footer LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_384766AF3A51721D (instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_subscriber (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, client_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, email LONGTEXT NOT NULL, INDEX IDX_401562C33A51721D (instance_id), INDEX IDX_401562C3B03A8386 (created_by_id), INDEX IDX_401562C3896DBBDE (updated_by_id), INDEX IDX_401562C319EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, instance_id INT DEFAULT NULL, billing_profile_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total INT DEFAULT NULL, currency VARCHAR(16) DEFAULT NULL, items JSON DEFAULT NULL, payment_status VARCHAR(32) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_F5299398B03A8386 (created_by_id), INDEX IDX_F52993983A51721D (instance_id), INDEX IDX_F5299398409D7D29 (billing_profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE popup (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, modal_title LONGTEXT NOT NULL, modal_body LONGTEXT NOT NULL, modal_footer LONGTEXT NOT NULL, maximum_appearance INT NOT NULL, shown_count INT NOT NULL, css LONGTEXT NOT NULL, js LONGTEXT NOT NULL, INDEX IDX_A0964583A51721D (instance_id), INDEX IDX_A096458B03A8386 (created_by_id), INDEX IDX_A096458896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, type VARCHAR(32) DEFAULT \'domain\', description LONGTEXT DEFAULT NULL, fee INT UNSIGNED DEFAULT 0, currency VARCHAR(8) DEFAULT NULL, frequency_of_payment VARCHAR(16) DEFAULT NULL, next_payment_date DATE DEFAULT NULL, status TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E19D9AD23A51721D (instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, default_instance_id INT DEFAULT NULL, roles JSON NOT NULL, name_prefix VARCHAR(8) DEFAULT NULL, first_name VARCHAR(64) DEFAULT NULL, middle_name VARCHAR(32) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, nick_name VARCHAR(128) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, birth_name VARCHAR(128) DEFAULT NULL, birthdate DATE DEFAULT NULL, position VARCHAR(128) DEFAULT NULL, phone VARCHAR(32) DEFAULT NULL, email VARCHAR(128) DEFAULT NULL, status TINYINT(1) NOT NULL, last_login DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_activation DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', profile_image_url VARCHAR(255) DEFAULT NULL, INDEX IDX_8D93D649EEA261AB (default_instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, ftphost VARCHAR(64) DEFAULT NULL, ftpuser VARCHAR(64) DEFAULT NULL, ftppassword VARCHAR(64) DEFAULT NULL, ftppath VARCHAR(128) DEFAULT NULL, domain VARCHAR(64) NOT NULL, name VARCHAR(320) DEFAULT NULL, description VARCHAR(320) DEFAULT NULL, theme VARCHAR(16) DEFAULT NULL, language VARCHAR(8) DEFAULT NULL, charset VARCHAR(16) DEFAULT \'utf-8\', title VARCHAR(128) DEFAULT NULL, meta_description VARCHAR(320) DEFAULT NULL, meta_keywords VARCHAR(320) DEFAULT NULL, meta_author VARCHAR(64) DEFAULT NULL, meta_robots VARCHAR(16) DEFAULT NULL, INDEX IDX_476F5DE73A51721D (instance_id), INDEX IDX_476F5DE7B03A8386 (created_by_id), INDEX IDX_476F5DE7896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404553A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4F3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE instance ADD CONSTRAINT FK_4230B1DE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE instance_user ADD CONSTRAINT FK_A59986823A51721D FOREIGN KEY (instance_id) REFERENCES instance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instance_user ADD CONSTRAINT FK_A5998682A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instance_billing_profile ADD CONSTRAINT FK_6E58FFD93A51721D FOREIGN KEY (instance_id) REFERENCES instance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instance_billing_profile ADD CONSTRAINT FK_6E58FFD9409D7D29 FOREIGN KEY (billing_profile_id) REFERENCES billing_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter ADD CONSTRAINT FK_7E8585C83A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE newsletter_settings ADD CONSTRAINT FK_384766AF3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE newsletter_subscriber ADD CONSTRAINT FK_401562C33A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE newsletter_subscriber ADD CONSTRAINT FK_401562C3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE newsletter_subscriber ADD CONSTRAINT FK_401562C3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE newsletter_subscriber ADD CONSTRAINT FK_401562C319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993983A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398409D7D29 FOREIGN KEY (billing_profile_id) REFERENCES billing_profile (id)');
        $this->addSql('ALTER TABLE popup ADD CONSTRAINT FK_A0964583A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE popup ADD CONSTRAINT FK_A096458B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE popup ADD CONSTRAINT FK_A096458896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD23A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EEA261AB FOREIGN KEY (default_instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE73A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404553A51721D');
        $this->addSql('ALTER TABLE form DROP FOREIGN KEY FK_5288FD4F3A51721D');
        $this->addSql('ALTER TABLE form DROP FOREIGN KEY FK_5288FD4FB03A8386');
        $this->addSql('ALTER TABLE form DROP FOREIGN KEY FK_5288FD4F896DBBDE');
        $this->addSql('ALTER TABLE instance DROP FOREIGN KEY FK_4230B1DE7E3C61F9');
        $this->addSql('ALTER TABLE instance_user DROP FOREIGN KEY FK_A59986823A51721D');
        $this->addSql('ALTER TABLE instance_user DROP FOREIGN KEY FK_A5998682A76ED395');
        $this->addSql('ALTER TABLE instance_billing_profile DROP FOREIGN KEY FK_6E58FFD93A51721D');
        $this->addSql('ALTER TABLE instance_billing_profile DROP FOREIGN KEY FK_6E58FFD9409D7D29');
        $this->addSql('ALTER TABLE newsletter DROP FOREIGN KEY FK_7E8585C83A51721D');
        $this->addSql('ALTER TABLE newsletter_settings DROP FOREIGN KEY FK_384766AF3A51721D');
        $this->addSql('ALTER TABLE newsletter_subscriber DROP FOREIGN KEY FK_401562C33A51721D');
        $this->addSql('ALTER TABLE newsletter_subscriber DROP FOREIGN KEY FK_401562C3B03A8386');
        $this->addSql('ALTER TABLE newsletter_subscriber DROP FOREIGN KEY FK_401562C3896DBBDE');
        $this->addSql('ALTER TABLE newsletter_subscriber DROP FOREIGN KEY FK_401562C319EB6921');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B03A8386');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993983A51721D');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398409D7D29');
        $this->addSql('ALTER TABLE popup DROP FOREIGN KEY FK_A0964583A51721D');
        $this->addSql('ALTER TABLE popup DROP FOREIGN KEY FK_A096458B03A8386');
        $this->addSql('ALTER TABLE popup DROP FOREIGN KEY FK_A096458896DBBDE');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD23A51721D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EEA261AB');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE73A51721D');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7B03A8386');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7896DBBDE');
        $this->addSql('DROP TABLE billing_profile');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE form');
        $this->addSql('DROP TABLE instance');
        $this->addSql('DROP TABLE instance_user');
        $this->addSql('DROP TABLE instance_billing_profile');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE newsletter_settings');
        $this->addSql('DROP TABLE newsletter_subscriber');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE popup');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE website');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
