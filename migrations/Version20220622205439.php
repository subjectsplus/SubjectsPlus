<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220622205439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function preUp(Schema $schema): void
    {
        parent::preUp($schema);

    }

    public function up(Schema $schema): void
    {
        $sql = "SELECT title.title, l.location FROM title LEFT JOIN location l on title.title_id = l.title_id LIMIT 5";
        $results = $this->connection->fetchAllAssociative($sql);
        print_r($results);
    }

    public function down(Schema $schema): void
    {

    }
}
