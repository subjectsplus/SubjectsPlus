<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220502160247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add directory column to media table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE media ADD directory VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE media DROP directory');
    }
}
