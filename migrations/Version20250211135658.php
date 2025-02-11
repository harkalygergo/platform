<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211135658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE popup (id INT AUTO_INCREMENT NOT NULL, instance_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name LONGTEXT NOT NULL, modal_title LONGTEXT NOT NULL, modal_body LONGTEXT NOT NULL, modal_footer LONGTEXT NOT NULL, maximum_appearance INT NOT NULL, shown_count INT NOT NULL, css LONGTEXT NOT NULL, js LONGTEXT NOT NULL, INDEX IDX_A0964583A51721D (instance_id), INDEX IDX_A096458B03A8386 (created_by_id), INDEX IDX_A096458896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE popup ADD CONSTRAINT FK_A0964583A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('ALTER TABLE popup ADD CONSTRAINT FK_A096458B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE popup ADD CONSTRAINT FK_A096458896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE popup DROP FOREIGN KEY FK_A0964583A51721D');
        $this->addSql('ALTER TABLE popup DROP FOREIGN KEY FK_A096458B03A8386');
        $this->addSql('ALTER TABLE popup DROP FOREIGN KEY FK_A096458896DBBDE');
        $this->addSql('DROP TABLE popup');
    }
}
