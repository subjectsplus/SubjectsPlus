<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806150822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // chchchanges migration
        $this->addSql('ALTER TABLE chchchanges CHANGE staff_id staff_id INT DEFAULT NULL');
        // todo:
        //  to comply with foreign key constraints, we can either delete the whole row,
        //  set the default value for staff_id to null and update the staff_id column to null
        //  delete any rows where staff_id does not exist to comply with foreign key constraint
        // for now, we are just updating the staff_id column to null
        $this->addSql('UPDATE chchchanges ch SET ch.staff_id = NULL WHERE ch.staff_id NOT IN (SELECT s.staff_id FROM staff s)');
        $this->addSql('ALTER TABLE chchchanges ADD CONSTRAINT FK_CHCHCHANGES_STAFF_ID FOREIGN KEY (staff_id) REFERENCES staff (staff_id)');
        $this->addSql('CREATE INDEX IDX_CHCHCHANGES_STAFF_ID ON chchchanges (staff_id)');

        // faq migration
        
        // allow null values
        $this->addSql('ALTER TABLE faq MODIFY keywords VARCHAR(255) NULL');
        
        // convert existing values to json format before altering table to json
        // empty strings become null
        // non-compatible json values become null
        $this->addSql('UPDATE faq SET keywords = NULL WHERE TRIM(keywords) = \'\'');
        $this->addSql('UPDATE faq SET keywords = NULL WHERE JSON_VALID(CONCAT(\'["\', REPLACE(REPLACE(REPLACE(TRIM(keywords), \',\', \'","\'), \'" \', \'"\'), \' "\', \'"\'), \'"]\')) = 0');

        // updates the remaining keywords to json format if the resulting value is valid json
        $this->addSql('UPDATE faq SET keywords = CONCAT(\'["\', REPLACE(REPLACE(REPLACE(TRIM(keywords), \',\', \'","\'), \'" \', \'"\'), \' "\', \'"\'), \'"]\')
        WHERE keywords IS NOT NULL AND JSON_VALID(CONCAT(\'["\', REPLACE(REPLACE(REPLACE(TRIM(keywords), \',\', \'","\'), \'" \', \'"\'), \' "\', \'"\'), \'"]\')) = 1');

        // alter table to json
        $this->addSql('ALTER TABLE faq CHANGE keywords keywords JSON DEFAULT NULL');

        // faq_faqpage migration
        $this->addSql('ALTER TABLE faq_faqpage DROP FOREIGN KEY fk_ff_faq_id');
        $this->addSql('ALTER TABLE faq_faqpage DROP FOREIGN KEY fk_ff_faqpage_id');
        $this->addSql('ALTER TABLE faq_faqpage ADD CONSTRAINT FK_FAQFAQPAGE_FAQ_ID FOREIGN KEY (faq_id) REFERENCES faq (faq_id)');
        $this->addSql('ALTER TABLE faq_faqpage ADD CONSTRAINT FK_FAQFAQPAGE_FAQPAGE_ID FOREIGN KEY (faqpage_id) REFERENCES faqpage (faqpage_id)');
        
        // faq_subject migration
        $this->addSql('ALTER TABLE faq_subject DROP FOREIGN KEY fk_fs_faq_id');
        $this->addSql('ALTER TABLE faq_subject DROP FOREIGN KEY fk_fs_subject_id');
        $this->addSql('ALTER TABLE faq_subject ADD CONSTRAINT FK_FAQSUBJECT_FAQ_ID FOREIGN KEY (faq_id) REFERENCES faq (faq_id)');
        $this->addSql('ALTER TABLE faq_subject ADD CONSTRAINT FK_FAQSUBJECT_SUBJECT_ID FOREIGN KEY (subject_id) REFERENCES subject (subject_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // chchchanges migration
        $this->addSql('ALTER TABLE chchchanges DROP FOREIGN KEY FK_CHCHCHANGES_STAFF_ID');
        $this->addSql('DROP INDEX IDX_CHCHCHANGES_STAFF_ID ON chchchanges');
        
        // faq migration
        // switch back to varchar(255) allowing null values
        $this->addSql('ALTER TABLE faq MODIFY keywords VARCHAR(255) NULL');
        // switch all null values back to empty strings
        $this->addSql('UPDATE faq SET keywords = \'\' WHERE keywords IS NULL');
        // disallow null values
        $this->addSql('ALTER TABLE faq MODIFY keywords VARCHAR(255) NOT NULL');
        // convert existing values back to original format
        $this->addSql('UPDATE faq SET keywords = REPLACE(REPLACE(REPLACE(keywords, \'"\', \'\'), \'[\', \'\'), \']\', \'\')
        WHERE TRIM(keywords) != \'\'');
        
        // faq_faqpage migration
        $this->addSql('ALTER TABLE faq_faqpage DROP FOREIGN KEY FK_FAQFAQPAGE_FAQ_ID');
        $this->addSql('ALTER TABLE faq_faqpage DROP FOREIGN KEY FK_FAQFAQPAGE_FAQPAGE_ID');
        $this->addSql('ALTER TABLE faq_faqpage ADD CONSTRAINT fk_ff_faq_id FOREIGN KEY (faq_id) REFERENCES faq (faq_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faq_faqpage ADD CONSTRAINT fk_ff_faqpage_id FOREIGN KEY (faqpage_id) REFERENCES faqpage (faqpage_id) ON UPDATE CASCADE ON DELETE CASCADE');
        
        // faq_subject migration
        $this->addSql('ALTER TABLE faq_subject DROP FOREIGN KEY FK_FAQSUBJECT_FAQ_ID');
        $this->addSql('ALTER TABLE faq_subject DROP FOREIGN KEY FK_FAQSUBJECT_SUBJECT_ID');
        $this->addSql('ALTER TABLE faq_subject ADD CONSTRAINT fk_fs_faq_id FOREIGN KEY (faq_id) REFERENCES faq (faq_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faq_subject ADD CONSTRAINT fk_fs_subject_id FOREIGN KEY (subject_id) REFERENCES subject (subject_id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
