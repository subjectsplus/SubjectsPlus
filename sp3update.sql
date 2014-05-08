/* Position vacant for staff table */

ALTER TABLE `staff`
ADD COLUMN `position_vacant` INT(1) NULL DEFAULT 0 AFTER `lat_long`;



/* And give it a header, for header switching (perhaps UM only) */
ALTER TABLE `subject` ADD `header` VARCHAR( 100 ) NULL AFTER `extra`;

/* Let the tab point to external url */

ALTER TABLE `tab`
ADD COLUMN `external_url` VARCHAR(500) NULL AFTER `tab_index`;

/* Changes to pluslet table to allow header tweaking */

ALTER TABLE `pluslet` ADD `hide_titlebar` INT( 1 ) NOT NULL DEFAULT '0',
ADD `collapse_body` INT( 1 ) NOT NULL DEFAULT '0',
ADD `suppress_body` INT( 1 ) NOT NULL DEFAULT '0',
ADD `titlebar_styling` VARCHAR( 100 ) NULL;


/* Associate subject guides with departments like CHC */

CREATE TABLE IF NOT EXISTS `subject_department` (
`idsubject_department` int(11) NOT NULL AUTO_INCREMENT,
`id_subject` bigint(20) NOT NULL,
`id_department` int(11) NOT NULL,
PRIMARY KEY (`idsubject_department`),
KEY `fk_subject_id_idx` (`id_subject`),
KEY `fk_department_id_idx` (`id_department`),
CONSTRAINT `fk_subject_id` FOREIGN KEY (`id_subject`) REFERENCES `subject` (`subject_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
CONSTRAINT `fk_department_id` FOREIGN KEY (`id_department`) REFERENCES `department` (`department_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `subject_department`
ADD COLUMN `date` TIMESTAMP NOT NULL AFTER `id_department`;

/* Subject parent relationship */


CREATE TABLE IF NOT EXISTS`subject_subject` (
`id_subject_subject` INT NOT NULL AUTO_INCREMENT,
`subject_parent` BIGINT(20) NOT NULL,
`subject_child` BIGINT(20) NOT NULL,
PRIMARY KEY (`id_subject_subject`),
INDEX `fk_subject_parent_idx` (`subject_parent` ASC),
INDEX `fk_subject_child_idx` (`subject_child` ASC),
CONSTRAINT `fk_subject_parent`
FOREIGN KEY (`subject_parent`)
REFERENCES `subject` (`subject_id`)
ON DELETE NO ACTION
ON UPDATE NO ACTION,
CONSTRAINT `fk_subject_child`
FOREIGN KEY (`subject_child`)
REFERENCES `subject` (`subject_id`)
ON DELETE NO ACTION
ON UPDATE NO ACTION);

ALTER TABLE `subject_subject`
ADD COLUMN `date` TIMESTAMP NOT NULL AFTER `subject_child`;

ALTER TABLE `tab`
ADD COLUMN `visibility` INT(11) NOT NULL DEFAULT 2 AFTER `external_url`;

/* New Section table */
CREATE TABLE IF NOT EXISTS `section` (

  `section_id` INT(11) NOT NULL AUTO_INCREMENT ,

  `section_index` INT(11) NOT NULL DEFAULT 0 ,

  `layout` VARCHAR(255) NOT NULL DEFAULT '4-4-4' ,

  `tab_id` INT(11) NOT NULL ,

  PRIMARY KEY (`section_id`) ,

  INDEX `fk_section_tab_idx` (`tab_id` ASC) ,

  CONSTRAINT `fk_section_tab`

    FOREIGN KEY (`tab_id` )

    REFERENCES `tab` (`tab_id` )

    ON DELETE CASCADE

    ON UPDATE CASCADE)

ENGINE = InnoDB

DEFAULT CHARACTER SET = utf8;

/* Make an intial section per tab */
INSERT INTO section (tab_id)
SELECT tab_id FROM tab;

/* Change tab_ids to newly created section_id related to that tab id (above) */
SET FOREIGN_KEY_CHECKS = 0;

UPDATE pluslet_tab pt INNER JOIN section s
ON pt.tab_id = s.tab_id
SET pt.tab_id = s.section_id;

SET FOREIGN_KEY_CHECKS = 1;

/* change pluslet_tab to pluslet_section */
ALTER TABLE `pluslet_tab` DROP FOREIGN KEY `fk_pt_tab_id` ;

ALTER TABLE `pluslet_tab` CHANGE COLUMN `tab_id` `section_id` INT(11) NOT NULL  ,

  ADD CONSTRAINT `fk_pt_section_id`

  FOREIGN KEY (`section_id` )

  REFERENCES `section` (`section_id` )

  ON DELETE CASCADE

  ON UPDATE CASCADE, RENAME TO  `subjectsplus`.`pluslet_section` ;

/*  change column name */
ALTER TABLE `pluslet_section` CHANGE COLUMN `pluslet_tab_id` `pluslet_section_id` INT(11) NOT NULL AUTO_INCREMENT  ;

/* add visibility column to tab */
ALTER TABLE `tab` ADD COLUMN `visibility` INT(1) NOT NULL DEFAULT 1  AFTER `external_url` ;