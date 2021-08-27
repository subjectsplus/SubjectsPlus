<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210824132708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add log table to DB.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, message LONGTEXT NOT NULL, context JSON NOT NULL, level SMALLINT NOT NULL, level_name VARCHAR(255) NOT NULL, client_ip VARCHAR(50) DEFAULT NULL, client_port SMALLINT NOT NULL, method VARCHAR(4) NOT NULL, uri LONGTEXT NOT NULL, request JSON NOT NULL, token VARCHAR(17) DEFAULT NULL, query_string LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE log');
    }
}
