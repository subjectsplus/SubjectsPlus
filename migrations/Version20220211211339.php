<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211211339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate the location_title table to location including existing data.';
    }

    public function up(Schema $schema): void
    {
        // update columns for location to include title_id, change location_id type
        $this->addSql('ALTER TABLE location ADD title_id INT DEFAULT NULL, CHANGE location_id location_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBA9F87BD FOREIGN KEY (title_id) REFERENCES title (title_id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CBA9F87BD ON location (title_id)');

        // Move data from title_id columns in location_title to location
        $this->addSql('UPDATE location AS l
                        INNER JOIN location_title AS lt ON l.location_id = lt.location_id
                        SET l.title_id = CASE 
                        WHEN lt.title_id IN (SELECT title_id FROM title)
                        THEN lt.title_id
                        ELSE NULL
                        END');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBA9F87BD');
        $this->addSql('DROP INDEX IDX_5E9E89CBA9F87BD ON location');
        $this->addSql('ALTER TABLE location DROP title_id, CHANGE location_id location_id BIGINT AUTO_INCREMENT NOT NULL, CHANGE format format BIGINT DEFAULT NULL');        
    }
}

