/* Position vacant for staff table */

ALTER TABLE `subjectsplus`.`staff` 
ADD COLUMN `position_vacant` INT(1) NULL DEFAULT 0 AFTER `lat_long`;


/* Give the subject guide a parent */

ALTER TABLE `subjectsplus`.`subject` 
ADD COLUMN `parent` BIGINT(20) NULL AFTER `extra`;


/* Let the tab point to external url */

ALTER TABLE `subjectsplus`.`tab` 
ADD COLUMN `external_url` VARCHAR(500) NULL AFTER `tab_index`;