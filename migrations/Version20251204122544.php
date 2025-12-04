<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251204122544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD name_prefix VARCHAR(8) DEFAULT NULL, ADD first_name VARCHAR(64) DEFAULT NULL, ADD middle_name VARCHAR(32) DEFAULT NULL, ADD last_name VARCHAR(64) DEFAULT NULL, ADD nick_name VARCHAR(128) DEFAULT NULL, ADD birth_name VARCHAR(128) DEFAULT NULL, ADD birthdate DATE DEFAULT NULL, ADD position VARCHAR(128) DEFAULT NULL, ADD phone VARCHAR(32) DEFAULT NULL, ADD is_active TINYINT NOT NULL, ADD last_login DATETIME DEFAULT NULL, ADD last_activation DATETIME DEFAULT NULL, ADD profile_image_url VARCHAR(255) DEFAULT NULL, ADD default_instance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EEA261AB FOREIGN KEY (default_instance_id) REFERENCES instance (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649EEA261AB ON user (default_instance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EEA261AB');
        $this->addSql('DROP INDEX IDX_8D93D649EEA261AB ON user');
        $this->addSql('ALTER TABLE user DROP name_prefix, DROP first_name, DROP middle_name, DROP last_name, DROP nick_name, DROP birth_name, DROP birthdate, DROP position, DROP phone, DROP is_active, DROP last_login, DROP last_activation, DROP profile_image_url, DROP default_instance_id');
    }
}
