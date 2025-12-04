<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251203141243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, domain LONGTEXT NOT NULL, public_key LONGTEXT DEFAULT NULL, secret LONGTEXT DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_AD05D80F3A51721D (instance_id), INDEX IDX_AD05D80FB03A8386 (created_by_id), INDEX IDX_AD05D80F896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE billing_profile (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, zip INT NOT NULL, settlement VARCHAR(64) NOT NULL, address VARCHAR(255) NOT NULL, vat VARCHAR(32) DEFAULT NULL, eu_vat VARCHAR(16) DEFAULT NULL, billing_registration_number VARCHAR(32) DEFAULT NULL, phone_number VARCHAR(32) DEFAULT NULL, fax_number VARCHAR(32) DEFAULT NULL, email VARCHAR(128) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE block (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, content LONGTEXT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_831B97223A51721D (instance_id), INDEX IDX_831B9722B03A8386 (created_by_id), INDEX IDX_831B9722896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, items JSON DEFAULT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_BA388B7A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name_prefix VARCHAR(8) DEFAULT NULL, first_name VARCHAR(64) NOT NULL, middle_name VARCHAR(32) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, birth_date DATE DEFAULT NULL, phone VARCHAR(32) DEFAULT NULL, email VARCHAR(128) DEFAULT NULL, country VARCHAR(64) DEFAULT NULL, zip VARCHAR(16) DEFAULT NULL, settlement VARCHAR(128) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, instance_id INT NOT NULL, INDEX IDX_C74404553A51721D (instance_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE form (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, code LONGTEXT NOT NULL, notification_email LONGTEXT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_5288FD4F3A51721D (instance_id), INDEX IDX_5288FD4FB03A8386 (created_by_id), INDEX IDX_5288FD4F896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE instance (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, status TINYINT NOT NULL, intranet LONGTEXT DEFAULT NULL, type VARCHAR(255) DEFAULT \'Platform\', created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, owner_id INT DEFAULT NULL, INDEX IDX_4230B1DE7E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE instance_user (instance_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A59986823A51721D (instance_id), INDEX IDX_A5998682A76ED395 (user_id), PRIMARY KEY (instance_id, user_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE instance_billing_profile (instance_id INT NOT NULL, billing_profile_id INT NOT NULL, INDEX IDX_6E58FFD93A51721D (instance_id), INDEX IDX_6E58FFD9409D7D29 (billing_profile_id), PRIMARY KEY (instance_id, billing_profile_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE instance_feed (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, message LONGTEXT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_B4A14603A51721D (instance_id), INDEX IDX_B4A1460B03A8386 (created_by_id), INDEX IDX_B4A1460896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, original_name VARCHAR(255) NOT NULL, type VARCHAR(32) NOT NULL, path LONGTEXT NOT NULL, size INT NOT NULL, description LONGTEXT DEFAULT NULL, is_public TINYINT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_6A2CA10C3A51721D (instance_id), INDEX IDX_6A2CA10CB03A8386 (created_by_id), INDEX IDX_6A2CA10C896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, position INT DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, INDEX IDX_7D053A933A51721D (instance_id), INDEX IDX_7D053A93B03A8386 (created_by_id), INDEX IDX_7D053A93896DBBDE (updated_by_id), INDEX IDX_7D053A9318F45C82 (website_id), INDEX IDX_7D053A93727ACA70 (parent_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, subject VARCHAR(255) NOT NULL, plain_text_content LONGTEXT DEFAULT NULL, html_content LONGTEXT DEFAULT NULL, send_at DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, instance_id INT NOT NULL, INDEX IDX_7E8585C83A51721D (instance_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE newsletter_settings (id INT AUTO_INCREMENT NOT NULL, from_name VARCHAR(255) NOT NULL, from_email VARCHAR(255) NOT NULL, default_subject VARCHAR(255) NOT NULL, default_plain_text_content LONGTEXT DEFAULT NULL, default_html_content LONGTEXT DEFAULT NULL, default_footer LONGTEXT DEFAULT NULL, instance_id INT NOT NULL, UNIQUE INDEX UNIQ_384766AF3A51721D (instance_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE newsletter_subscriber (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, email LONGTEXT NOT NULL, source VARCHAR(64) DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, client_id INT DEFAULT NULL, INDEX IDX_401562C33A51721D (instance_id), INDEX IDX_401562C3B03A8386 (created_by_id), INDEX IDX_401562C3896DBBDE (updated_by_id), INDEX IDX_401562C319EB6921 (client_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, total INT DEFAULT NULL, currency VARCHAR(16) DEFAULT NULL, items JSON DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, shipping_method VARCHAR(255) DEFAULT NULL, shipping_address LONGTEXT DEFAULT NULL, payment_method VARCHAR(255) DEFAULT NULL, billing_country VARCHAR(255) DEFAULT NULL, billing_zip VARCHAR(32) DEFAULT NULL, billing_city VARCHAR(255) DEFAULT NULL, billing_address LONGTEXT DEFAULT NULL, payment_status VARCHAR(32) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, created_by_id INT DEFAULT NULL, instance_id INT DEFAULT NULL, billing_profile_id INT DEFAULT NULL, INDEX IDX_F5299398B03A8386 (created_by_id), INDEX IDX_F52993983A51721D (instance_id), INDEX IDX_F5299398409D7D29 (billing_profile_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE popup (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, modal_title LONGTEXT NOT NULL, modal_body LONGTEXT NOT NULL, modal_footer LONGTEXT NOT NULL, maximum_appearance INT NOT NULL, shown_count INT NOT NULL, css LONGTEXT NOT NULL, js LONGTEXT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_A0964583A51721D (instance_id), INDEX IDX_A096458B03A8386 (created_by_id), INDEX IDX_A096458896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, type VARCHAR(32) DEFAULT \'domain\', description LONGTEXT DEFAULT NULL, fee INT UNSIGNED DEFAULT 0, currency VARCHAR(8) DEFAULT NULL, frequency_of_payment VARCHAR(16) DEFAULT NULL, next_payment_date DATE DEFAULT NULL, status TINYINT DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, instance_id INT DEFAULT NULL, INDEX IDX_E19D9AD23A51721D (instance_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, deadline DATETIME DEFAULT NULL, priority INT DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, assignee_id INT DEFAULT NULL, INDEX IDX_527EDB253A51721D (instance_id), INDEX IDX_527EDB25B03A8386 (created_by_id), INDEX IDX_527EDB25896DBBDE (updated_by_id), INDEX IDX_527EDB2559EC7D60 (assignee_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE webshop_payment_methods (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(50) DEFAULT NULL, enabled TINYINT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_257C66A43A51721D (instance_id), INDEX IDX_257C66A4B03A8386 (created_by_id), INDEX IDX_257C66A4896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE webshop_shipping_methods (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(50) DEFAULT NULL, enabled TINYINT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_15CA93593A51721D (instance_id), INDEX IDX_15CA9359B03A8386 (created_by_id), INDEX IDX_15CA9359896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE website (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, ftphost VARCHAR(64) DEFAULT NULL, ftpuser VARCHAR(64) DEFAULT NULL, ftppassword VARCHAR(64) DEFAULT NULL, ftppath VARCHAR(128) DEFAULT NULL, domain VARCHAR(64) NOT NULL, name VARCHAR(320) DEFAULT NULL, slogan VARCHAR(320) DEFAULT NULL, description VARCHAR(320) DEFAULT NULL, phone VARCHAR(32) DEFAULT NULL, email VARCHAR(64) DEFAULT NULL, address VARCHAR(320) DEFAULT NULL, facebook VARCHAR(64) DEFAULT NULL, twitter VARCHAR(64) DEFAULT NULL, instagram VARCHAR(64) DEFAULT NULL, linkedin VARCHAR(64) DEFAULT NULL, youtube VARCHAR(64) DEFAULT NULL, tiktok VARCHAR(64) DEFAULT NULL, theme VARCHAR(16) DEFAULT NULL, language VARCHAR(8) DEFAULT NULL, charset VARCHAR(16) DEFAULT \'utf-8\', title VARCHAR(128) DEFAULT NULL, meta_description VARCHAR(320) DEFAULT NULL, meta_keywords VARCHAR(320) DEFAULT NULL, meta_author VARCHAR(64) DEFAULT NULL, meta_robots VARCHAR(16) DEFAULT NULL, header_css LONGTEXT DEFAULT NULL, header_js LONGTEXT DEFAULT NULL, header_html LONGTEXT DEFAULT NULL, body_top_html LONGTEXT DEFAULT NULL, footer_js LONGTEXT DEFAULT NULL, footer_html LONGTEXT DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, favicon_id INT DEFAULT NULL, logo_id INT DEFAULT NULL, INDEX IDX_476F5DE73A51721D (instance_id), INDEX IDX_476F5DE7B03A8386 (created_by_id), INDEX IDX_476F5DE7896DBBDE (updated_by_id), INDEX IDX_476F5DE7D78119FD (favicon_id), INDEX IDX_476F5DE7F98F144A (logo_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE website_category (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_robots VARCHAR(32) DEFAULT NULL, meta_canonical VARCHAR(255) DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, INDEX IDX_D75B504C3A51721D (instance_id), INDEX IDX_D75B504CB03A8386 (created_by_id), INDEX IDX_D75B504C896DBBDE (updated_by_id), INDEX IDX_D75B504C18F45C82 (website_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE website_media (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, original_name VARCHAR(255) NOT NULL, type VARCHAR(32) NOT NULL, path LONGTEXT NOT NULL, size INT NOT NULL, description LONGTEXT DEFAULT NULL, is_public TINYINT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, INDEX IDX_3D4611C03A51721D (instance_id), INDEX IDX_3D4611C0B03A8386 (created_by_id), INDEX IDX_3D4611C0896DBBDE (updated_by_id), INDEX IDX_3D4611C018F45C82 (website_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE website_page (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_robots VARCHAR(32) DEFAULT NULL, meta_canonical VARCHAR(255) DEFAULT NULL, homepage TINYINT DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, INDEX IDX_160F5F543A51721D (instance_id), INDEX IDX_160F5F54B03A8386 (created_by_id), INDEX IDX_160F5F54896DBBDE (updated_by_id), INDEX IDX_160F5F5418F45C82 (website_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE website_post (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_robots VARCHAR(32) DEFAULT NULL, meta_canonical VARCHAR(255) DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, website_id INT DEFAULT NULL, INDEX IDX_588F85F93A51721D (instance_id), INDEX IDX_588F85F9B03A8386 (created_by_id), INDEX IDX_588F85F9896DBBDE (updated_by_id), INDEX IDX_588F85F918F45C82 (website_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE website_post_category (website_post_id INT NOT NULL, website_category_id INT NOT NULL, INDEX IDX_9781B25FE7A77F88 (website_post_id), INDEX IDX_9781B25F59C3646B (website_category_id), PRIMARY KEY (website_post_id, website_category_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE api ADD CONSTRAINT FK_AD05D80F3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE api ADD CONSTRAINT FK_AD05D80FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE api ADD CONSTRAINT FK_AD05D80F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B97223A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
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
        $this->addSql('ALTER TABLE instance_feed ADD CONSTRAINT FK_B4A14603A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE instance_feed ADD CONSTRAINT FK_B4A1460B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE instance_feed ADD CONSTRAINT FK_B4A1460896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A933A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9318F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93727ACA70 FOREIGN KEY (parent_id) REFERENCES menu (id)');
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
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB253A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2559EC7D60 FOREIGN KEY (assignee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE webshop_payment_methods ADD CONSTRAINT FK_257C66A43A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE webshop_payment_methods ADD CONSTRAINT FK_257C66A4B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE webshop_payment_methods ADD CONSTRAINT FK_257C66A4896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE webshop_shipping_methods ADD CONSTRAINT FK_15CA93593A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE webshop_shipping_methods ADD CONSTRAINT FK_15CA9359B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE webshop_shipping_methods ADD CONSTRAINT FK_15CA9359896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE73A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7D78119FD FOREIGN KEY (favicon_id) REFERENCES website_media (id)');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7F98F144A FOREIGN KEY (logo_id) REFERENCES website_media (id)');
        $this->addSql('ALTER TABLE website_category ADD CONSTRAINT FK_D75B504C3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE website_category ADD CONSTRAINT FK_D75B504CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website_category ADD CONSTRAINT FK_D75B504C896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website_category ADD CONSTRAINT FK_D75B504C18F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('ALTER TABLE website_media ADD CONSTRAINT FK_3D4611C03A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE website_media ADD CONSTRAINT FK_3D4611C0B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website_media ADD CONSTRAINT FK_3D4611C0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website_media ADD CONSTRAINT FK_3D4611C018F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT FK_160F5F543A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT FK_160F5F54B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT FK_160F5F54896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website_page ADD CONSTRAINT FK_160F5F5418F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('ALTER TABLE website_post ADD CONSTRAINT FK_588F85F93A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE website_post ADD CONSTRAINT FK_588F85F9B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website_post ADD CONSTRAINT FK_588F85F9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE website_post ADD CONSTRAINT FK_588F85F918F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('ALTER TABLE website_post_category ADD CONSTRAINT FK_9781B25FE7A77F88 FOREIGN KEY (website_post_id) REFERENCES website_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE website_post_category ADD CONSTRAINT FK_9781B25F59C3646B FOREIGN KEY (website_category_id) REFERENCES website_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api DROP FOREIGN KEY FK_AD05D80F3A51721D');
        $this->addSql('ALTER TABLE api DROP FOREIGN KEY FK_AD05D80FB03A8386');
        $this->addSql('ALTER TABLE api DROP FOREIGN KEY FK_AD05D80F896DBBDE');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B97223A51721D');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722B03A8386');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722896DBBDE');
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
        $this->addSql('ALTER TABLE instance_feed DROP FOREIGN KEY FK_B4A14603A51721D');
        $this->addSql('ALTER TABLE instance_feed DROP FOREIGN KEY FK_B4A1460B03A8386');
        $this->addSql('ALTER TABLE instance_feed DROP FOREIGN KEY FK_B4A1460896DBBDE');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C3A51721D');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CB03A8386');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C896DBBDE');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A933A51721D');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93B03A8386');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93896DBBDE');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9318F45C82');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93727ACA70');
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
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB253A51721D');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25B03A8386');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25896DBBDE');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2559EC7D60');
        $this->addSql('ALTER TABLE webshop_payment_methods DROP FOREIGN KEY FK_257C66A43A51721D');
        $this->addSql('ALTER TABLE webshop_payment_methods DROP FOREIGN KEY FK_257C66A4B03A8386');
        $this->addSql('ALTER TABLE webshop_payment_methods DROP FOREIGN KEY FK_257C66A4896DBBDE');
        $this->addSql('ALTER TABLE webshop_shipping_methods DROP FOREIGN KEY FK_15CA93593A51721D');
        $this->addSql('ALTER TABLE webshop_shipping_methods DROP FOREIGN KEY FK_15CA9359B03A8386');
        $this->addSql('ALTER TABLE webshop_shipping_methods DROP FOREIGN KEY FK_15CA9359896DBBDE');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE73A51721D');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7B03A8386');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7896DBBDE');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7D78119FD');
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7F98F144A');
        $this->addSql('ALTER TABLE website_category DROP FOREIGN KEY FK_D75B504C3A51721D');
        $this->addSql('ALTER TABLE website_category DROP FOREIGN KEY FK_D75B504CB03A8386');
        $this->addSql('ALTER TABLE website_category DROP FOREIGN KEY FK_D75B504C896DBBDE');
        $this->addSql('ALTER TABLE website_category DROP FOREIGN KEY FK_D75B504C18F45C82');
        $this->addSql('ALTER TABLE website_media DROP FOREIGN KEY FK_3D4611C03A51721D');
        $this->addSql('ALTER TABLE website_media DROP FOREIGN KEY FK_3D4611C0B03A8386');
        $this->addSql('ALTER TABLE website_media DROP FOREIGN KEY FK_3D4611C0896DBBDE');
        $this->addSql('ALTER TABLE website_media DROP FOREIGN KEY FK_3D4611C018F45C82');
        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY FK_160F5F543A51721D');
        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY FK_160F5F54B03A8386');
        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY FK_160F5F54896DBBDE');
        $this->addSql('ALTER TABLE website_page DROP FOREIGN KEY FK_160F5F5418F45C82');
        $this->addSql('ALTER TABLE website_post DROP FOREIGN KEY FK_588F85F93A51721D');
        $this->addSql('ALTER TABLE website_post DROP FOREIGN KEY FK_588F85F9B03A8386');
        $this->addSql('ALTER TABLE website_post DROP FOREIGN KEY FK_588F85F9896DBBDE');
        $this->addSql('ALTER TABLE website_post DROP FOREIGN KEY FK_588F85F918F45C82');
        $this->addSql('ALTER TABLE website_post_category DROP FOREIGN KEY FK_9781B25FE7A77F88');
        $this->addSql('ALTER TABLE website_post_category DROP FOREIGN KEY FK_9781B25F59C3646B');
        $this->addSql('DROP TABLE api');
        $this->addSql('DROP TABLE billing_profile');
        $this->addSql('DROP TABLE block');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE form');
        $this->addSql('DROP TABLE instance');
        $this->addSql('DROP TABLE instance_user');
        $this->addSql('DROP TABLE instance_billing_profile');
        $this->addSql('DROP TABLE instance_feed');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE newsletter_settings');
        $this->addSql('DROP TABLE newsletter_subscriber');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE popup');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE webshop_payment_methods');
        $this->addSql('DROP TABLE webshop_shipping_methods');
        $this->addSql('DROP TABLE website');
        $this->addSql('DROP TABLE website_category');
        $this->addSql('DROP TABLE website_media');
        $this->addSql('DROP TABLE website_page');
        $this->addSql('DROP TABLE website_post');
        $this->addSql('DROP TABLE website_post_category');
    }
}
