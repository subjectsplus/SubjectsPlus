<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616135338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change pluslet extra column to JSON type';
    }

    public function up(Schema $schema): void
    {
        // convert existing values to json format before altering table to json
        // empty strings become null
        // non-compatible json values become null
        $this->addSql('UPDATE pluslet SET extra = NULL WHERE TRIM(extra) = \'\'');
        $this->addSql('UPDATE pluslet SET extra = NULL WHERE JSON_VALID(TRIM(extra)) = 0');
        
        // alter the pluslet extra column to JSON type
        $this->addSql('ALTER TABLE pluslet CHANGE extra extra JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pluslet CHANGE extra extra MEDIUMTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
    }
}
