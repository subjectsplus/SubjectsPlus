<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220523181039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // replace_special_characters custom function
        $this->addSql('DROP FUNCTION IF EXISTS replace_special_characters');
        $this->addSql('CREATE FUNCTION replace_special_characters( str VARCHAR(255), replacement VARCHAR(32) ) RETURNS VARCHAR(255)
            BEGIN
                DECLARE i, len SMALLINT DEFAULT 1;
                DECLARE cleaned_str VARCHAR(255) DEFAULT "";
                DECLARE current_char CHAR(1);

                SET len = CHAR_LENGTH( str );
                REPEAT
                    BEGIN
                        SET current_char = MID( str, i, 1 );
                        IF current_char REGEXP "[[:alnum:]]" COLLATE utf8mb4_unicode_ci THEN 
                            SET cleaned_str = CONCAT(cleaned_str, current_char);
                        ELSE 
                            SET cleaned_str = CONCAT(cleaned_str, replacement);
                        END IF;

                        SET i = i + 1;
                    END;
                UNTIL i > len END REPEAT;

                RETURN cleaned_str;
            END'
        );

        // Fix existing improper shortform format; replace special characters with a dash, trim and lowercase the shortform
        $this->addSql('UPDATE subject SET shortform = LOWER(replace_special_characters(TRIM(shortform), "-" COLLATE utf8mb4_unicode_ci))');
    }

    public function down(Schema $schema): void
    {
    }
}
