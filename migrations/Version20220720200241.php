<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220720200241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO config_category (id, category_key) VALUES (11, "media")');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (11, "small_image_dimensions", "Small Image Dimensions", "264x264", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (11, "medium_image_dimensions", "Medium Image Dimensions", "500x500", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (11, "large_image_dimensions", "Large Image Dimensions", "750x750", 0)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM config WHERE option_key = "small_image_dimensions" OR option_key = "medium_image_dimensions" OR option_key = "large_image_dimensions"');
        $this->addSql('DELETE FROM config_category WHERE category_key = "media"');
    }
}
