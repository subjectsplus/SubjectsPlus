<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505182129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create config_category table and insert default categories.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE config_category (id INT AUTO_INCREMENT NOT NULL, category_key VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (1, "theme")');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (2, "email")');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (3, "basic_settings")');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (4, "catalog")');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (5, "guides")');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (6, "records")');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (7, "talkback")');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (8, "api")');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (9, "server")');
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (10, "problem_report")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE config_category');
    }
}
