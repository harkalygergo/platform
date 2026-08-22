<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260821165946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cms_page_website (cms_page_id INT NOT NULL, website_id INT NOT NULL, INDEX IDX_3C2C410852AA6CF5 (cms_page_id), INDEX IDX_3C2C410818F45C82 (website_id), PRIMARY KEY (cms_page_id, website_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE cms_page_website ADD CONSTRAINT FK_3C2C410852AA6CF5 FOREIGN KEY (cms_page_id) REFERENCES cms_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cms_page_website ADD CONSTRAINT FK_3C2C410818F45C82 FOREIGN KEY (website_id) REFERENCES website (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cms_page_website DROP FOREIGN KEY FK_3C2C410852AA6CF5');
        $this->addSql('ALTER TABLE cms_page_website DROP FOREIGN KEY FK_3C2C410818F45C82');
        $this->addSql('DROP TABLE cms_page_website');
    }
}
