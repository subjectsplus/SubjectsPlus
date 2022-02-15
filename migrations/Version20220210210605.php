<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220210210605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add pcolumn and prow columns to pluslet, migrate existing data to the new columns.';
    }

    public function up(Schema $schema): void
    {
        // Add prow and pcolumn to pluslet
        $this->addSql('ALTER TABLE pluslet ADD pcolumn INT NOT NULL, ADD prow INT NOT NULL');
        $this->addSql('ALTER TABLE pluslet RENAME INDEX fk_48a5b83ad823e37a TO IDX_48A5B83AD823E37A');
        
        // Move data from prow and pcolumn columns in pluslet_section to pluslet
        $this->addSql('UPDATE pluslet AS p
                        INNER JOIN pluslet_section AS ps ON p.pluslet_id = ps.pluslet_id
                        SET p.pcolumn = ps.pcolumn, p.prow = ps.prow');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pluslet DROP pcolumn, DROP prow');
        $this->addSql('ALTER TABLE pluslet RENAME INDEX IDX_48A5B83AD823E37A TO fk_48a5b83ad823e37a');
    }
}
