<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302164549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify faq_subject and faq_faqpage tables to satisfy doctrine relation requirements for faq table.';
    }

    public function up(Schema $schema): void
    {
        // Remove duplicate entries
        $this->addSql('DELETE fs2.* FROM faq_subject AS fs1
                        JOIN faq_subject AS fs2 
                        ON fs2.faq_id = fs1.faq_id
                        AND fs2.subject_id = fs1.subject_id
                        AND fs2.faq_subject_id > fs1.faq_subject_id');
        
        $this->addSql('DELETE ffp2.* FROM faq_faqpage AS ffp1
                        JOIN faq_faqpage AS ffp2 
                        ON ffp2.faq_id = ffp1.faq_id
                        AND ffp2.faqpage_id = ffp1.faqpage_id
                        AND ffp2.faq_faqpage_id > ffp1.faq_faqpage_id');

        $this->addSql('ALTER TABLE faq_subject MODIFY faq_subject_id INT NOT NULL');
        $this->addSql('ALTER TABLE faq_subject DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE faq_subject DROP faq_subject_id, CHANGE subject_id subject_id INT NOT NULL');
        $this->addSql('ALTER TABLE faq_subject ADD PRIMARY KEY (faq_id, subject_id)');
        $this->addSql('ALTER TABLE faq_subject RENAME INDEX fk_fs_faq_id_idx TO IDX_E0BF552181BEC8C2');
        $this->addSql('ALTER TABLE faq_subject RENAME INDEX fk_fs_subject_id_idx TO IDX_E0BF552123EDC87');
        $this->addSql('ALTER TABLE faq_faqpage MODIFY faq_faqpage_id INT NOT NULL');
        $this->addSql('ALTER TABLE faq_faqpage DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE faq_faqpage DROP faq_faqpage_id');
        $this->addSql('ALTER TABLE faq_faqpage ADD PRIMARY KEY (faq_id, faqpage_id)');
        $this->addSql('ALTER TABLE faq_faqpage RENAME INDEX fk_ff_faq_id_idx TO IDX_5C2FB14681BEC8C2');
        $this->addSql('ALTER TABLE faq_faqpage RENAME INDEX fk_ff_faqpage_id_idx TO IDX_5C2FB14685BBA432');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE faq_faqpage ADD faq_faqpage_id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (faq_faqpage_id)');
        $this->addSql('ALTER TABLE faq_faqpage RENAME INDEX idx_5c2fb14681bec8c2 TO fk_ff_faq_id_idx');
        $this->addSql('ALTER TABLE faq_faqpage RENAME INDEX idx_5c2fb14685bba432 TO fk_ff_faqpage_id_idx');
        $this->addSql('ALTER TABLE faq_subject ADD faq_subject_id INT AUTO_INCREMENT NOT NULL, CHANGE subject_id subject_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (faq_subject_id)');
        $this->addSql('ALTER TABLE faq_subject RENAME INDEX idx_e0bf552181bec8c2 TO fk_fs_faq_id_idx');
        $this->addSql('ALTER TABLE faq_subject RENAME INDEX idx_e0bf552123edc87 TO fk_fs_subject_id_idx');
    }
}
