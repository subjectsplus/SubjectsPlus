<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210929205803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE staff_photo (staffphoto_id INT AUTO_INCREMENT NOT NULL, staff_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_69522CDFD4D57CD (staff_id), PRIMARY KEY(staffphoto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE staff_photo ADD CONSTRAINT FK_69522CDFD4D57CD FOREIGN KEY (staff_id) REFERENCES staff (staff_id)');
        $this->addSql('ALTER TABLE staff ADD staffphoto_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF3925660BA47 FOREIGN KEY (staffphoto_id) REFERENCES staff_photo (staffphoto_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_426EF3925660BA47 ON staff (staffphoto_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE staff DROP FOREIGN KEY FK_426EF3925660BA47');
        $this->addSql('DROP TABLE staff_photo');
        $this->addSql('DROP INDEX UNIQ_426EF3925660BA47 ON staff');
        $this->addSql('ALTER TABLE staff DROP staffphoto_id');
    }
}
