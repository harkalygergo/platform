<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260526152434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE testimonial (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, title LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, instance_id INT DEFAULT NULL, main_image_id INT DEFAULT NULL, created_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, INDEX IDX_E6BDCDF73A51721D (instance_id), INDEX IDX_E6BDCDF7E4873418 (main_image_id), INDEX IDX_E6BDCDF7B03A8386 (created_by_id), INDEX IDX_E6BDCDF7896DBBDE (updated_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE testimonial ADD CONSTRAINT FK_E6BDCDF73A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE testimonial ADD CONSTRAINT FK_E6BDCDF7E4873418 FOREIGN KEY (main_image_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE testimonial ADD CONSTRAINT FK_E6BDCDF7B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE testimonial ADD CONSTRAINT FK_E6BDCDF7896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE testimonial DROP FOREIGN KEY FK_E6BDCDF73A51721D');
        $this->addSql('ALTER TABLE testimonial DROP FOREIGN KEY FK_E6BDCDF7E4873418');
        $this->addSql('ALTER TABLE testimonial DROP FOREIGN KEY FK_E6BDCDF7B03A8386');
        $this->addSql('ALTER TABLE testimonial DROP FOREIGN KEY FK_E6BDCDF7896DBBDE');
        $this->addSql('DROP TABLE testimonial');
    }
}
