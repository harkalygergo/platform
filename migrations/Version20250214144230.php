<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214144230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_subscriber ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter_subscriber ADD CONSTRAINT FK_401562C319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_401562C319EB6921 ON newsletter_subscriber (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_subscriber DROP FOREIGN KEY FK_401562C319EB6921');
        $this->addSql('DROP INDEX IDX_401562C319EB6921 ON newsletter_subscriber');
        $this->addSql('ALTER TABLE newsletter_subscriber DROP client_id');
    }
}
