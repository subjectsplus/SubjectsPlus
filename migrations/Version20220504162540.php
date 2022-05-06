<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504162540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change updated_at and deleted_at columns in Media entity to datetime_immutable.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE media CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE media CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
    }
}
