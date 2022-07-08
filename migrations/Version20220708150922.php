<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220708150922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert theme configuration keys and default values.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "meta_description", "Site Description", "The best stuff for your research. No kidding.", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "meta_author", "Site Author/Insititution", "University of Miami Libraries", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "meta_keywords", "Site Keywords", "research guides, databases", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "site_logo", "Site Logo", "build/images/frontend/sp_logo.png", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_id", "Theme", "default", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_bg_color", "Theme Background Color", "#FFFFFF", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_text_color", "Theme Text Color", "#333333", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_link_color", "Theme Link Color", "#005A8F", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_link_hover_color", "Theme Link Hover Color", "#0078BD", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_menu_bg_color", "Theme Menu Background Color", "#00243c", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_menu_text_color", "Theme Menu Text Color", "#FFFFFF", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_menu_link_color", "Theme Menu Link Color", "#FFFFFF", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_menu_link_hover_color", "Theme Menu Link Hover Color", "#089DB4", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_header_bg_color", "Theme Header Background Color", "#FFFFFF", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_header_text_color", "Theme Header Text Color", "#333333", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_header_link_color", "Theme Header Link Color", "#005A8F", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_header_link_hover_color", "Theme Header Link Hover Color", "#0078BD", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_footer_bg_color", "Theme Footer Background Color", "#00243c", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_footer_text_color", "Theme Footer Text Color", "#FFFFFF", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_footer_link_color", "Theme Footer Link Color", "#FFFFFF", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_footer_link_hover_color", "Theme Footer Link Hover Color", "#089DB4", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_ui_base_color", "Theme UI Base Color", "#e9ecef", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_ui_base_text_color", "Theme UI Base Text Color", "#333333", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_ui_active_color", "Theme UI Active Color", "#089DB4", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_ui_active_text_color", "Theme UI Active Text Color", "#FFFFFF", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "theme_ui_active_hover_color", "Theme UI Active Hover Color", "#0A8194", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "display_experts", "Guide Display Experts", "true", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "display_new_databases", "Guide Display New Databases", "true", 0)');
        $this->addSql('INSERT INTO config (config_category_id, option_key, option_label, option_value, required) VALUES (1, "display_new_guides", "Guide Display New Guides", "true", 0)');
    }

    public function down(Schema $schema): void
    {
    }
}
