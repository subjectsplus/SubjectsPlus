/* Position vacant for staff table */

ALTER TABLE `staff` 
ADD COLUMN `position_vacant` INT(1) NULL DEFAULT 0 AFTER `lat_long`;


/* Give the subject guide a parent */

ALTER TABLE `subject` 
ADD COLUMN `parent` BIGINT(20) NULL AFTER `extra`;


/* Let the tab point to external url */

ALTER TABLE `tab` 
ADD COLUMN `external_url` VARCHAR(500) NULL AFTER `tab_index`;

/* Changes to pluslet table to allow header tweaking */

ALTER TABLE `pluslet` ADD `hide_titlebar` INT( 1 ) NOT NULL DEFAULT '0',
ADD `collapse_body` INT( 1 ) NOT NULL DEFAULT '0',
ADD `suppress_body` INT( 1 ) NOT NULL DEFAULT '0',
ADD `titlebar_styling` VARCHAR( 100 ) NULL 
