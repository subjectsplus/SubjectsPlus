<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225151117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Change faq table's active column default value to false.";
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE faq CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE faq CHANGE active active TINYINT(1) DEFAULT \'1\' NOT NULL');
    }
}
