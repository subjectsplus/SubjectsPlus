<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513142151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Convert active field of Subject entity from tinyint to smallint to allow for suppressed active type.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE subject CHANGE active active SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject CHANGE active active TINYINT(1) NOT NULL');
    }
}
