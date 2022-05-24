<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510192703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add width and height columns for the large, medium, and small media image sizes.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE media ADD large_image_file_width INT DEFAULT NULL, ADD large_image_file_height INT DEFAULT NULL, ADD medium_image_file_width INT DEFAULT NULL, ADD medium_image_file_height INT DEFAULT NULL, ADD small_image_file_width INT DEFAULT NULL, ADD small_image_file_height INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE media DROP large_image_file_width, DROP large_image_file_height, DROP medium_image_file_width, DROP medium_image_file_height, DROP small_image_file_width, DROP small_image_file_height');
    }
}
