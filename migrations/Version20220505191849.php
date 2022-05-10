<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505191849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, config_category_id INT NOT NULL, option_key VARCHAR(255) NOT NULL, option_label VARCHAR(255) NOT NULL, option_value VARCHAR(255), required TINYINT(1) NOT NULL, INDEX IDX_D48A2F7CC0131185 (config_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7CC0131185 FOREIGN KEY (config_category_id) REFERENCES config_category (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE config');
    }
}
