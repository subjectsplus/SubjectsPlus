<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211117173640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add necessary columns for generated large, medium, and small image files.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD large_file_name VARCHAR(255) DEFAULT NULL, ADD medium_file_name VARCHAR(255) DEFAULT NULL, ADD small_file_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP large_file_name, DROP medium_file_name, DROP small_file_name');
    }
}
