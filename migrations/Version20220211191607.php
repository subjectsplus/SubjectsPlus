<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211191607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align the column types for title, source, and subject.';
    }

    public function up(Schema $schema): void
    {
        // drop keys related to title_id and change title_id type
        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY fk_rank_title_id');
        $this->addSql('DROP INDEX INDEXSEARCHtitle ON title');
        $this->addSql('ALTER TABLE title CHANGE title_id title_id INT AUTO_INCREMENT NOT NULL, CHANGE internal_notes internal_notes MEDIUMTEXT DEFAULT NULL');

        // drop keys related to source_id and change source_id type
        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY fk_rank_source_id');
        $this->addSql('ALTER TABLE source CHANGE source_id source_id INT AUTO_INCREMENT NOT NULL');

        // drop keys related to subject_id and change subject_id type
        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY fk_rank_subject_id');
        $this->addSql('ALTER TABLE staff_subject DROP FOREIGN KEY fk_ss_subject_id');
        $this->addSql('ALTER TABLE subject_discipline DROP FOREIGN KEY fk_sd_subject_id');
        $this->addSql('ALTER TABLE tab DROP FOREIGN KEY fk_t_subject_id');
        $this->addSql('ALTER TABLE faq_subject DROP FOREIGN KEY FK_FAQSUBJECT_SUBJECT_ID');
        $this->addSql('ALTER TABLE subject_subject DROP FOREIGN KEY fk_subject_parent');
        $this->addSql('ALTER TABLE subject_subject DROP FOREIGN KEY fk_subject_child');
        $this->addSql('DROP INDEX INDEXSEARCHsubject ON subject');
        $this->addSql('ALTER TABLE subject CHANGE subject_id subject_id INT AUTO_INCREMENT NOT NULL, CHANGE active active TINYINT(1) NOT NULL, CHANGE shortform shortform VARCHAR(50) NOT NULL');

        // update faq_subject subject_id column
        $this->addSql('ALTER TABLE faq_subject CHANGE subject_id subject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE faq_subject ADD CONSTRAINT FK__FAQ_SUBJECT__SUBJECT_ID FOREIGN KEY (subject_id) REFERENCES subject (subject_id)');

        // update rank subject_id, title_id, and source_id columns
        $this->addSql('ALTER TABLE rank CHANGE subject_id subject_id INT DEFAULT NULL, CHANGE title_id title_id INT DEFAULT NULL, CHANGE source_id source_id INT DEFAULT NULL, CHANGE rank rank INT NOT NULL');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT FK_8879E8E5953C1C61 FOREIGN KEY (source_id) REFERENCES source (source_id)');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT FK_8879E8E523EDC87 FOREIGN KEY (subject_id) REFERENCES subject (subject_id)');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT FK_8879E8E5A9F87BD FOREIGN KEY (title_id) REFERENCES title (title_id)');        
        
        // update staff_subject subject_id column
        $this->addSql('ALTER TABLE staff_subject CHANGE subject_id subject_id INT NOT NULL');
        $this->addSql('ALTER TABLE staff_subject ADD CONSTRAINT FK_A77829CC23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (subject_id)');
        $this->addSql('ALTER TABLE staff_subject RENAME INDEX fk_ss_subject_id_idx TO IDX_A77829CC23EDC87');
        
        // update subject_discipline subject_id column
        $this->addSql('ALTER TABLE subject_discipline DROP INDEX fk_sd_subject_id_idx, ADD INDEX IDX_AE46767D23EDC87 (subject_id)');
        $this->addSql('DROP INDEX fk_sd_subject_id_idx1 ON subject_discipline');
        $this->addSql('ALTER TABLE subject_discipline CHANGE subject_id subject_id INT NOT NULL');
        $this->addSql('ALTER TABLE subject_discipline ADD CONSTRAINT FK_AE46767D23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (subject_id)');
        
        // update subject_subject subject_parent and subject_child columns
        $this->addSql('ALTER TABLE subject_subject CHANGE subject_parent subject_parent INT DEFAULT NULL, CHANGE subject_child subject_child INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subject_subject ADD CONSTRAINT FK__SUBJECT_SUBJECT__SUBJECT_PARENT FOREIGN KEY (subject_parent) REFERENCES subject (subject_id)');
        $this->addSql('ALTER TABLE subject_subject ADD CONSTRAINT FK__SUBJECT_SUBJECT__SUBJECT_CHILD FOREIGN KEY (subject_child) REFERENCES subject (subject_id)');

        // update tab columns
        $this->addSql('ALTER TABLE tab CHANGE subject_id subject_id INT DEFAULT NULL, CHANGE tab_index tab_index INT NOT NULL, CHANGE visibility visibility TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE parent parent VARCHAR(500) DEFAULT NULL, CHANGE children children VARCHAR(500) DEFAULT NULL, CHANGE extra extra VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE tab ADD CONSTRAINT FK_73E3430C23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (subject_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE faq_subject DROP FOREIGN KEY FK__FAQ_SUBJECT__SUBJECT_ID');
        $this->addSql('ALTER TABLE subject_subject DROP FOREIGN KEY FK__SUBJECT_SUBJECT__SUBJECT_PARENT');
        $this->addSql('ALTER TABLE subject_subject DROP FOREIGN KEY FK__SUBJECT_SUBJECT__SUBJECT_CHILD');
        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY FK_8879E8E5953C1C61');
        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY FK_8879E8E523EDC87');
        $this->addSql('ALTER TABLE rank DROP FOREIGN KEY FK_8879E8E5A9F87BD');
        $this->addSql('ALTER TABLE staff_subject DROP FOREIGN KEY FK_A77829CC23EDC87');
        $this->addSql('ALTER TABLE subject_discipline DROP FOREIGN KEY FK_AE46767D23EDC87');
        $this->addSql('ALTER TABLE tab DROP FOREIGN KEY FK_73E3430C23EDC87');

        $this->addSql('ALTER TABLE source CHANGE source_id source_id BIGINT AUTO_INCREMENT NOT NULL');

        $this->addSql('ALTER TABLE subject CHANGE subject_id subject_id BIGINT AUTO_INCREMENT NOT NULL, CHANGE active active INT DEFAULT 0 NOT NULL, CHANGE shortform shortform VARCHAR(50) CHARACTER SET utf8 DEFAULT \'0\' NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('CREATE INDEX INDEXSEARCHsubject ON subject (subject, shortform, description, keywords)');

        $this->addSql('ALTER TABLE title CHANGE title_id title_id BIGINT AUTO_INCREMENT NOT NULL, CHANGE internal_notes internal_notes MEDIUMTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci` COMMENT \'added v4.1\'');
        $this->addSql('CREATE INDEX INDEXSEARCHtitle ON title (title, alternate_title, description(200))');

        $this->addSql('ALTER TABLE faq_subject CHANGE subject_id subject_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE faq_subject ADD CONSTRAINT FK_FAQSUBJECT_SUBJECT_ID FOREIGN KEY (subject_id) REFERENCES subject (subject_id)');
        
        $this->addSql('ALTER TABLE rank CHANGE source_id source_id BIGINT DEFAULT NULL, CHANGE subject_id subject_id BIGINT DEFAULT NULL, CHANGE title_id title_id BIGINT DEFAULT NULL, CHANGE rank rank INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT fk_rank_source_id FOREIGN KEY (source_id) REFERENCES source (source_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT fk_rank_subject_id FOREIGN KEY (subject_id) REFERENCES subject (subject_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rank ADD CONSTRAINT fk_rank_title_id FOREIGN KEY (title_id) REFERENCES title (title_id) ON UPDATE CASCADE ON DELETE CASCADE');
       
        $this->addSql('ALTER TABLE staff_subject CHANGE subject_id subject_id BIGINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE staff_subject ADD CONSTRAINT fk_ss_subject_id FOREIGN KEY (subject_id) REFERENCES subject (subject_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE staff_subject RENAME INDEX idx_a77829cc23edc87 TO fk_ss_subject_id_idx');;
       
        $this->addSql('ALTER TABLE subject_discipline CHANGE subject_id subject_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE subject_discipline ADD CONSTRAINT fk_sd_subject_id FOREIGN KEY (subject_id) REFERENCES subject (subject_id) ON UPDATE CASCADE ON DELETE CASCADE');
        
        $this->addSql('CREATE INDEX fk_sd_subject_id_idx1 ON subject_discipline (subject_id)');
        
        $this->addSql('ALTER TABLE subject_discipline RENAME INDEX idx_ae46767d23edc87 TO fk_sd_subject_id_idx');
        
        $this->addSql('ALTER TABLE subject_subject CHANGE subject_child subject_child BIGINT NOT NULL, CHANGE subject_parent subject_parent BIGINT NOT NULL');
        $this->addSql('ALTER TABLE subject_subject ADD CONSTRAINT fk_subject_parent FOREIGN KEY (subject_parent) REFERENCES subject (subject_id)');
        $this->addSql('ALTER TABLE subject_subject ADD CONSTRAINT fk_subject_child FOREIGN KEY (subject_child) REFERENCES subject (subject_id)');

        $this->addSql('ALTER TABLE tab CHANGE subject_id subject_id BIGINT DEFAULT 0 NOT NULL, CHANGE tab_index tab_index INT DEFAULT 0 NOT NULL, CHANGE visibility visibility INT DEFAULT 1 NOT NULL, CHANGE parent parent TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE children children TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE extra extra MEDIUMTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tab ADD CONSTRAINT fk_t_subject_id FOREIGN KEY (subject_id) REFERENCES subject (subject_id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
