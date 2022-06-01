<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601211045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE record (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, alternate_title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, internal_notes LONGTEXT DEFAULT NULL, pre VARCHAR(10) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, call_number VARCHAR(255) DEFAULT NULL, eres_display VARCHAR(1) NOT NULL, display_note LONGTEXT DEFAULT NULL, trial_start DATE DEFAULT NULL, trial_end DATE DEFAULT NULL, record_status VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE record');
    }
}
