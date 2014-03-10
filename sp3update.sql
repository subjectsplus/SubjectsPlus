/* Position vacant for staff table */

ALTER TABLE `staff` 
ADD COLUMN `position_vacant` INT(1) NULL DEFAULT 0 AFTER `lat_long`;


/* Give the subject guide a parent */

ALTER TABLE `subject` 
ADD COLUMN `parent` BIGINT(20) NULL AFTER `extra`;

/* And give it a header, for header switching (perhaps UM only) */
ALTER TABLE `subject` ADD `header` VARCHAR( 100 ) NULL AFTER `extra` 

/* Let the tab point to external url */

ALTER TABLE `tab` 
ADD COLUMN `external_url` VARCHAR(500) NULL AFTER `tab_index`;

/* Changes to pluslet table to allow header tweaking */

ALTER TABLE `pluslet` ADD `hide_titlebar` INT( 1 ) NOT NULL DEFAULT '0',
ADD `collapse_body` INT( 1 ) NOT NULL DEFAULT '0',
ADD `suppress_body` INT( 1 ) NOT NULL DEFAULT '0',
ADD `titlebar_styling` VARCHAR( 100 ) NULL 


/* Associate subject guides with departments like CHC */

CREATE TABLE `subject_department` (
`idsubject_department` int(11) NOT NULL,
`id_subject` bigint(20) NOT NULL,
`id_department` int(11) NOT NULL,
PRIMARY KEY (`idsubject_department`),
KEY `fk_subject_id_idx` (`id_subject`),
KEY `fk_department_id_idx` (`id_department`),
CONSTRAINT `fk_subject_id` FOREIGN KEY (`id_subject`) REFERENCES `subject` (`subject_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
CONSTRAINT `fk_department_id` FOREIGN KEY (`id_department`) REFERENCES `department` (`department_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


