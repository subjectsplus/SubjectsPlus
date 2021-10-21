<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211005182510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add media and media_attachment tables.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (media_id INT AUTO_INCREMENT NOT NULL, staff_id INT DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, file_size INT DEFAULT NULL, mime_type VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, caption LONGTEXT DEFAULT NULL, alt_text LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_attachment (media_attachment_id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, attachment_type VARCHAR(255) DEFAULT NULL, attachment_id INT DEFAULT NULL, INDEX IDX_737A172FEA9FDD75 (media_id), PRIMARY KEY(media_attachment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CD4D57CD FOREIGN KEY (staff_id) REFERENCES staff (staff_id)');
        $this->addSql('ALTER TABLE media_attachment ADD CONSTRAINT FK_737A172FEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (media_id)');
        $this->addSql('ALTER TABLE staff ADD staff_photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF392E003D82A FOREIGN KEY (staff_photo_id) REFERENCES media_attachment (media_attachment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_attachment DROP FOREIGN KEY FK_737A172FEA9FDD75');
        $this->addSql('ALTER TABLE staff DROP FOREIGN KEY FK_426EF392E003D82A');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_attachment');
        $this->addSql('ALTER TABLE staff DROP staff_photo_id');
    }
}
