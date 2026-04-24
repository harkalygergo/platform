<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260424150718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY `FK_476F5DE7939D8D9A`');
        $this->addSql('DROP INDEX IDX_476F5DE7939D8D9A ON website');
        $this->addSql('ALTER TABLE website CHANGE terms_page_id terms_and_conditions_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT FK_476F5DE7FBE5077 FOREIGN KEY (terms_and_conditions_id) REFERENCES cms_page (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_476F5DE7FBE5077 ON website (terms_and_conditions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website DROP FOREIGN KEY FK_476F5DE7FBE5077');
        $this->addSql('DROP INDEX IDX_476F5DE7FBE5077 ON website');
        $this->addSql('ALTER TABLE website CHANGE terms_and_conditions_id terms_page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE website ADD CONSTRAINT `FK_476F5DE7939D8D9A` FOREIGN KEY (terms_page_id) REFERENCES cms_page (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_476F5DE7939D8D9A ON website (terms_page_id)');
    }
}
