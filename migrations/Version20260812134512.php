<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260812134512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY `FK_81398E099395C3F3`');
        $this->addSql('DROP INDEX UNIQ_81398E099395C3F3 ON customer');
        $this->addSql('ALTER TABLE customer CHANGE customer_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E0919EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E0919EB6921 ON customer (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E0919EB6921');
        $this->addSql('DROP INDEX UNIQ_81398E0919EB6921 ON customer');
        $this->addSql('ALTER TABLE customer CHANGE client_id customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT `FK_81398E099395C3F3` FOREIGN KEY (customer_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E099395C3F3 ON customer (customer_id)');
    }
}
