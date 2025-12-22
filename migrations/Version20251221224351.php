<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251221224351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instance_website DROP FOREIGN KEY `FK_80FB902418F45C82`');
        $this->addSql('ALTER TABLE instance_website DROP FOREIGN KEY `FK_80FB90243A51721D`');
        $this->addSql('DROP TABLE instance_website');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE instance_website (instance_id INT NOT NULL, website_id INT NOT NULL, INDEX IDX_80FB90243A51721D (instance_id), INDEX IDX_80FB902418F45C82 (website_id), PRIMARY KEY (instance_id, website_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE instance_website ADD CONSTRAINT `FK_80FB902418F45C82` FOREIGN KEY (website_id) REFERENCES website (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instance_website ADD CONSTRAINT `FK_80FB90243A51721D` FOREIGN KEY (instance_id) REFERENCES instance (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
