<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210415142832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chchchanges (chchchanges_id BIGINT AUTO_INCREMENT NOT NULL, staff_id INT NOT NULL, ourtable VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, record_id INT NOT NULL, record_title VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, message VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(chchchanges_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collection (collection_id INT AUTO_INCREMENT NOT NULL, title TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, description TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, shortform TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(collection_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collection_subject (collection_subject_id INT AUTO_INCREMENT NOT NULL, collection_id INT NOT NULL, subject_id BIGINT NOT NULL, sort INT DEFAULT 0 NOT NULL, PRIMARY KEY(collection_subject_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE department (department_id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8 DEFAULT \'\' NOT NULL COLLATE `utf8_general_ci`, department_sort INT DEFAULT 0 NOT NULL, telephone VARCHAR(20) CHARACTER SET utf8 DEFAULT \'0\' NOT NULL COLLATE `utf8_general_ci`, email VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, url VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, INDEX INDEXSEARCHdepart (name), PRIMARY KEY(department_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE discipline (discipline_id INT AUTO_INCREMENT NOT NULL, discipline VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, sort INT NOT NULL, UNIQUE INDEX discipline (discipline), PRIMARY KEY(discipline_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'added v2\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE faq (faq_id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, answer TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, keywords VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(faq_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE faq_faqpage (faq_faqpage_id INT AUTO_INCREMENT NOT NULL, faq_id INT NOT NULL, faqpage_id INT NOT NULL, INDEX fk_ff_faqpage_id_idx (faqpage_id), INDEX fk_ff_faq_id_idx (faq_id), PRIMARY KEY(faq_faqpage_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE faq_subject (faq_subject_id INT AUTO_INCREMENT NOT NULL, faq_id INT NOT NULL, subject_id BIGINT NOT NULL, INDEX fk_fs_subject_id_idx (subject_id), INDEX fk_fs_faq_id_idx (faq_id), PRIMARY KEY(faq_subject_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE faqpage (faqpage_id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, description TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(faqpage_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE format (format_id BIGINT AUTO_INCREMENT NOT NULL, format VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(format_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE location (location_id BIGINT AUTO_INCREMENT NOT NULL, format BIGINT DEFAULT NULL, access_restrictions INT DEFAULT NULL, call_number VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, location TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, eres_display VARCHAR(1) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, display_note TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, helpguide VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, citation_guide VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, ctags VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, record_status VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, trial_start DATE DEFAULT NULL, trial_end DATE DEFAULT NULL, INDEX fk_location_format_id_idx (format), INDEX fk_location_restrictions_id_idx (access_restrictions), PRIMARY KEY(location_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE location_title (location_id BIGINT DEFAULT 0 NOT NULL, title_id BIGINT DEFAULT 0 NOT NULL, INDEX fk_lt_location_id_idx (location_id), INDEX fk_lt_title_id_idx (title_id), PRIMARY KEY(location_id, title_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pluslet (pluslet_id BIGINT AUTO_INCREMENT NOT NULL, title VARCHAR(100) CHARACTER SET utf8 DEFAULT \'\' NOT NULL COLLATE `utf8_general_ci`, body LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, local_file VARCHAR(100) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, clone INT DEFAULT 0 NOT NULL, type VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, extra MEDIUMTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, hide_titlebar INT DEFAULT 0 NOT NULL, collapse_body INT DEFAULT 0 NOT NULL, titlebar_styling VARCHAR(100) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, favorite_box INT DEFAULT 0 NOT NULL, master INT DEFAULT 0 NOT NULL, target_blank_links INT DEFAULT 0 NOT NULL, INDEX INDEXSEARCHpluslet (body(200)), PRIMARY KEY(pluslet_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pluslet_section (pluslet_section_id INT AUTO_INCREMENT NOT NULL, section_id BIGINT NOT NULL, pluslet_id BIGINT DEFAULT 0 NOT NULL, pcolumn INT NOT NULL, prow INT NOT NULL, INDEX fk_pt_tab_id_idx (section_id), INDEX fk_pt_pluslet_id_idx (pluslet_id), PRIMARY KEY(pluslet_section_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rank (rank_id INT AUTO_INCREMENT NOT NULL, subject_id BIGINT DEFAULT NULL, title_id BIGINT DEFAULT NULL, source_id BIGINT DEFAULT NULL, rank INT DEFAULT 0 NOT NULL, description_override TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, dbbysub_active TINYINT(1) DEFAULT \'1\', INDEX fk_rank_title_id_idx (title_id), INDEX fk_rank_subject_id_idx (subject_id), INDEX fk_rank_source_id_idx (source_id), PRIMARY KEY(rank_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE restrictions (restrictions_id INT AUTO_INCREMENT NOT NULL, restrictions TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(restrictions_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE section (section_id BIGINT AUTO_INCREMENT NOT NULL, tab_id BIGINT NOT NULL, section_index INT DEFAULT 0 NOT NULL, layout VARCHAR(255) CHARACTER SET utf8 DEFAULT \'4-4-4\' NOT NULL COLLATE `utf8_general_ci`, INDEX fk_section_tab_idx (tab_id), PRIMARY KEY(section_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE source (source_id BIGINT AUTO_INCREMENT NOT NULL, source VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, rs INT DEFAULT NULL, PRIMARY KEY(source_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE staff (staff_id INT AUTO_INCREMENT NOT NULL, user_type_id INT DEFAULT NULL, lname VARCHAR(765) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, fname VARCHAR(765) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, title VARCHAR(765) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, tel VARCHAR(45) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, department_id INT DEFAULT NULL, staff_sort INT DEFAULT NULL, email VARCHAR(765) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, ip VARCHAR(300) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, access_level INT DEFAULT NULL, password VARCHAR(192) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, active INT DEFAULT NULL, ptags VARCHAR(765) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, extra VARCHAR(765) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, bio BLOB DEFAULT NULL, position_number VARCHAR(30) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, job_classification VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, room_number VARCHAR(60) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, supervisor_id INT DEFAULT NULL, emergency_contact_name VARCHAR(150) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, emergency_contact_relation VARCHAR(150) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, emergency_contact_phone VARCHAR(60) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, street_address VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, city VARCHAR(150) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, state VARCHAR(60) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, zip VARCHAR(30) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, home_phone VARCHAR(60) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, cell_phone VARCHAR(60) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, fax VARCHAR(60) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, intercom VARCHAR(30) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, lat_long VARCHAR(75) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, social_media MEDIUMTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, INDEX fk_staff_user_type_id_idx (user_type_id), INDEX INDEXSEARCHstaff (lname(255), fname(255)), INDEX fk_supervisor_staff_id_idx (supervisor_id), INDEX fk_staff_department_id_idx (department_id), PRIMARY KEY(staff_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE staff_department (staff_id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, PRIMARY KEY(staff_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'Added v4\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE staff_subject (staff_id INT DEFAULT 0 NOT NULL, subject_id BIGINT DEFAULT 0 NOT NULL, staff_guide_order INT DEFAULT 0, INDEX fk_ss_staff_id_idx (staff_id), INDEX fk_ss_subject_id_idx (subject_id), PRIMARY KEY(staff_id, subject_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE stats (stats_id INT AUTO_INCREMENT NOT NULL, http_referer VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, query_string VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, remote_address VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, guide_page VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, date INT DEFAULT NULL, page_title VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, user_agent VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, subject_short_form VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, event_type VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, tab_name VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, link_url VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, link_title VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, in_tab VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, in_pluslet VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(stats_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subject (subject_id BIGINT AUTO_INCREMENT NOT NULL, subject VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, active INT DEFAULT 0 NOT NULL, shortform VARCHAR(50) CHARACTER SET utf8 DEFAULT \'0\' NOT NULL COLLATE `utf8_general_ci`, redirect_url VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, header VARCHAR(45) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, description VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, keywords VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, type VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, last_modified DATETIME DEFAULT CURRENT_TIMESTAMP, background_link VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, extra VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, course_code VARCHAR(45) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, instructor VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, INDEX INDEXSEARCHsubject (subject, shortform, description, keywords), PRIMARY KEY(subject_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subject_discipline (subject_id BIGINT NOT NULL, discipline_id INT NOT NULL, INDEX fk_sd_discipline_id_idx1 (discipline_id), INDEX discipline_id (discipline_id), INDEX fk_sd_discipline_id_idx (discipline_id), INDEX fk_sd_subject_id_idx1 (subject_id), INDEX fk_sd_subject_id_idx (subject_id), PRIMARY KEY(subject_id, discipline_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'added v2\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subject_subject (id_subject_subject INT AUTO_INCREMENT NOT NULL, subject_parent BIGINT NOT NULL, subject_child BIGINT NOT NULL, date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX fk_subject_child_idx (subject_child), INDEX fk_subject_parent_idx (subject_parent), PRIMARY KEY(id_subject_subject)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tab (tab_id BIGINT AUTO_INCREMENT NOT NULL, subject_id BIGINT DEFAULT 0 NOT NULL, label VARCHAR(120) CHARACTER SET utf8 DEFAULT \'Main\' NOT NULL COLLATE `utf8_general_ci`, tab_index INT DEFAULT 0 NOT NULL, external_url VARCHAR(500) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, visibility INT DEFAULT 1 NOT NULL, parent VARCHAR(500) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, children VARCHAR(500) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, extra VARCHAR(500) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, INDEX fk_t_subject_id_idx (subject_id), PRIMARY KEY(tab_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE talkback (talkback_id INT AUTO_INCREMENT NOT NULL, a_from INT DEFAULT NULL, question TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, q_from VARCHAR(100) CHARACTER SET utf8 DEFAULT \'\' COLLATE `utf8_general_ci`, date_submitted DATETIME NOT NULL, answer TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, display VARCHAR(11) CHARACTER SET utf8 DEFAULT \'No\' NOT NULL COLLATE `utf8_general_ci`, last_revised_by VARCHAR(100) CHARACTER SET utf8 DEFAULT \'\' NOT NULL COLLATE `utf8_general_ci`, tbtags VARCHAR(255) CHARACTER SET utf8 DEFAULT \'main\' COLLATE `utf8_general_ci`, cattags VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, INDEX INDEXSEARCHtalkback (question(200), answer(200)), INDEX fk_talkback_staff_id_idx (a_from), PRIMARY KEY(talkback_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE title (title_id BIGINT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, alternate_title VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, description TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, internal_notes MEDIUMTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, pre VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, last_modified_by VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, last_modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX INDEXSEARCHtitle (title, alternate_title, description(200)), PRIMARY KEY(title_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_type (user_type_id INT AUTO_INCREMENT NOT NULL, user_type VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(user_type_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE video (video_id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, description TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, source VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, foreign_id VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, duration VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, date DATE NOT NULL, display INT DEFAULT 0 NOT NULL, vtags VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, INDEX INDEXSEARCH (title, description(200)), PRIMARY KEY(video_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE chchchanges');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE collection');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE collection_subject');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE department');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE discipline');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE faq');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE faq_faqpage');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE faq_subject');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE faqpage');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE format');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE location');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE location_title');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pluslet');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pluslet_section');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE rank');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE restrictions');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE section');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE source');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE staff');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE staff_department');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE staff_subject');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE stats');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE subject');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE subject_discipline');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE subject_subject');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tab');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE talkback');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE title');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_type');
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE video');
    }
}
