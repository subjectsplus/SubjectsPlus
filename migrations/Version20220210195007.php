<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220210195007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change existing columns for pluslet and section, add section_id column to pluslet and copy the existing data from pluslet_section to the new column.';
    }

    public function up(Schema $schema): void
    {
        // Add section_id column to pluslet, change existing column types to tinyint for boolean values
        $this->addSql('ALTER TABLE pluslet ADD section_id INT DEFAULT NULL, CHANGE title title VARCHAR(100) NOT NULL, CHANGE clone clone TINYINT(1) NOT NULL, CHANGE hide_titlebar hide_titlebar TINYINT(1) NOT NULL, CHANGE collapse_body collapse_body TINYINT(1) NOT NULL, CHANGE favorite_box favorite_box TINYINT(1) NOT NULL, CHANGE target_blank_links target_blank_links TINYINT(1) NOT NULL, CHANGE master master TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE pluslet ADD CONSTRAINT FK_48A5B83AD823E37A FOREIGN KEY (section_id) REFERENCES section (section_id)');
        
        // Move data from section_id column in pluslet_section to pluslet
        $this->addSql('UPDATE pluslet AS p
                        INNER JOIN pluslet_section AS ps ON p.pluslet_id = ps.pluslet_id
                        SET p.section_id = ps.section_id');

        // Convert tab_id to int column
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY fk_section_tab');
        $this->addSql('ALTER TABLE section CHANGE tab_id tab_id INT DEFAULT NULL, CHANGE section_index section_index INT NOT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF8D0C9323 FOREIGN KEY (tab_id) REFERENCES tab (tab_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pluslet DROP FOREIGN KEY FK_48A5B83AD823E37A');
        $this->addSql('DROP INDEX IDX_48A5B83AD823E37A ON pluslet');
        $this->addSql('ALTER TABLE pluslet DROP section_id, CHANGE title title VARCHAR(100) CHARACTER SET utf8 DEFAULT \'\' NOT NULL COLLATE `utf8_general_ci`, CHANGE clone clone INT DEFAULT 0 NOT NULL, CHANGE hide_titlebar hide_titlebar INT DEFAULT 0 NOT NULL, CHANGE collapse_body collapse_body INT DEFAULT 0 NOT NULL, CHANGE favorite_box favorite_box INT DEFAULT 0, CHANGE master master INT DEFAULT NULL, CHANGE target_blank_links target_blank_links INT DEFAULT 0');
        $this->addSql('CREATE INDEX INDEXSEARCHpluslet ON pluslet (body(200))');
        
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF8D0C9323');
        $this->addSql('ALTER TABLE section CHANGE tab_id tab_id INT NOT NULL, CHANGE section_index section_index INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT fk_section_tab FOREIGN KEY (tab_id) REFERENCES tab (tab_id) ON UPDATE CASCADE ON DELETE CASCADE');
        
    }
}
