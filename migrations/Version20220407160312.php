<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220407160312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add uuid to Section entity.';
    }

    public function up(Schema $schema): void
    {
        // Create uuid column as BINARY(16) with UNIQUE index
        $this->addSql('ALTER TABLE section ADD uuid BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX section_uuid ON section (uuid)');

        // UUIDv4 custom function
        $this->addSql('DROP FUNCTION IF EXISTS uuid_v4s');
        $this->addSql('CREATE FUNCTION uuid_v4s() RETURNS CHAR(36)
            BEGIN
                -- 1st and 2nd block are made of 6 random bytes
                SET @block1 = HEX(RANDOM_BYTES(4));
                SET @block2 = HEX(RANDOM_BYTES(2));
            
                -- 3rd block will start with a 4 indicating the version, remaining is random
                SET @block3 = SUBSTR(HEX(RANDOM_BYTES(2)), 2, 3);
            
                -- 4th block first nibble can only be 8, 9 A or B, remaining is random
                SET @block4 = CONCAT(HEX(FLOOR(ASCII(RANDOM_BYTES(1)) / 64)+8),
                            SUBSTR(HEX(RANDOM_BYTES(2)), 2, 3));
            
                -- 5th block is made of 6 random bytes
                SET @block5 = HEX(RANDOM_BYTES(6));
            
                -- Build the complete UUID
                RETURN LOWER(CONCAT(
                    @block1, "-", @block2, "-4", @block3, "-", @block4, "-", @block5
                ));
            END'
        );

        // Generate uuidv4 for existing columns
        $this->addSql('UPDATE section SET uuid = UNHEX(REPLACE(uuid_v4s(), "-","")) WHERE uuid IS NULL;');
        
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX section_uuid ON section');
        $this->addSql('ALTER TABLE section DROP uuid');
    }
}
