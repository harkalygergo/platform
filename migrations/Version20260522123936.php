<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260522123936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE widget (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, template_code LONGTEXT NOT NULL, name LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, instance_id INT DEFAULT NULL, created_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_85F91ED03A51721D (instance_id), INDEX IDX_85F91ED0B03A8386 (created_by_id), INDEX IDX_85F91ED0896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE widget ADD CONSTRAINT FK_85F91ED03A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE widget ADD CONSTRAINT FK_85F91ED0B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE widget ADD CONSTRAINT FK_85F91ED0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE widget DROP FOREIGN KEY FK_85F91ED03A51721D');
        $this->addSql('ALTER TABLE widget DROP FOREIGN KEY FK_85F91ED0B03A8386');
        $this->addSql('ALTER TABLE widget DROP FOREIGN KEY FK_85F91ED0896DBBDE');
        $this->addSql('DROP TABLE widget');
    }
}
