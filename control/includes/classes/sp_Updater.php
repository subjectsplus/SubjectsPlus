<?php

/**
 * sp_Updater - this class handles the updating to SubjectsPlus 2.0
 *
 * @package SubjectsPlus
 * @author dgonzalez
 * @copyright Copyright (c) 2013
 * @version $Id$
 * @access public
 */
/**
 * sp_Updater
 *
 * @package
 * @author dgonzalez
 * @copyright Copyright (c) 2013
 * @version $Id$
 * @access public
 */
class sp_Updater
{
	//class properties
	private $lobjNewTables;
	private $lobjInsertInto;
	private $lobjFixData;
	private $lobjAlterTables;

	function __construct()
	{
		//queries to add new tables
		$this->lobjNewTables = array(
			"CREATE TABLE IF NOT EXISTS `discipline` (
			  `discipline_id` int(11) NOT NULL AUTO_INCREMENT,
			  `discipline` varchar(100) CHARACTER SET latin1 NOT NULL,
			  `sort` int(11) NOT NULL,
			  PRIMARY KEY (`discipline_id`),
			  UNIQUE KEY `discipline` (`discipline`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='added v2'",
			"CREATE TABLE IF NOT EXISTS `video` (
			  `video_id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) NOT NULL,
			  `description` text,
			  `source` varchar(255) NOT NULL,
			  `foreign_id` varchar(255) NOT NULL,
			  `duration` varchar(50) DEFAULT NULL,
			  `date` date NOT NULL,
			  `display` int(1) NOT NULL DEFAULT '0',
			  `vtags` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`video_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='added v2'",
			"CREATE TABLE IF NOT EXISTS `subject_discipline` (
			  `subject_id` bigint(20) NOT NULL,
			  `discipline_id` int(11) NOT NULL,
			  PRIMARY KEY (`subject_id`,`discipline_id`),
			  KEY `discipline_id` (`discipline_id`),
			  KEY `fk_sd_subject_id_idx` (`subject_id`),
			  KEY `fk_sd_discipline_id_idx` (`discipline_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='added v2'"
		);

		//queries to insert into new tables
		$this->lobjInsertInto = array(
			"INSERT INTO `discipline` (`discipline_id`,`discipline`,`sort`) VALUES (1,'agriculture',1),(2,'anatomy &amp; physiology',2),
			(3,'anthropology',3),(4,'applied sciences',4),(5,'architecture',5),(6,'astronomy &amp; astrophysics',6),(7,'biology',7),
			(8,'botany',8),(9,'business',9),(10,'chemistry',10),(11,'computer science',11),(12,'dance',12),(13,'dentistry',13),
			(14,'diet &amp; clinical nutrition',14),(15,'drama',15),(16,'ecology',16),(17,'economics',17),(18,'education',18),
			(19,'engineering',19),(20,'environmental sciences',20),(21,'film',21),(22,'forestry',22),(23,'geography',23),
			(24,'geology',24),(25,'government',25),(26,'history &amp; archaeology',26),(27,'human anatomy &amp; physiology',27),
			(28,'international relations',28),(29,'journalism &amp; communications',29),(30,'languages &amp; literatures',30),(31,'law',31),
			(32,'library &amp; information science',32),(33,'mathematics',33),(34,'medicine',34),(35,'meteorology &amp; climatology',35),
			(36,'military &amp; naval science',36),(37,'music',37),(38,'nursing',38),(39,'occupational therapy &amp; rehabilitation',39),
			(40,'oceanography',40),(41,'parapsychology &amp; occult sciences',41),(42,'pharmacy, therapeutics, &amp; pharmacology',42),
			(43,'philosophy',43),(44,'physical therapy',44),(45,'physics',45),(46,'political science',46),(47,'psychology',47),(48,'public health',48),
			(49,'recreation &amp; sports',49),(50,'religion',50),(51,'sciences (general)',51),(52,'social sciences (general)',52),
			(53,'social welfare &amp; social work',53),(54,'sociology &amp; social history',54),(55,'statistics',55),(56,'veterinary medicine',56),
			(57,'visual arts',57),(58,'women&#039;s studies',58),(59,'zoology',59)"
		);

		//queries to fix data and columns
		$this->lobjFixData = array(
			"ALTER TABLE `rank` CHANGE COLUMN `subject_id` `subject_id` BIGINT(20) NULL DEFAULT NULL, CHANGE COLUMN `title_id` `title_id` BIGINT(20) NULL DEFAULT NULL,
			CHANGE COLUMN `source_id` `source_id` BIGINT(20) NULL DEFAULT NULL",
			"UPDATE `talkback` SET `a_from` = NULL WHERE `a_from` REGEXP '[^0-9]'",
			"UPDATE `talkback` SET `a_from` = NULL WHERE `a_from` = ''",
			"ALTER TABLE `staff` CHANGE COLUMN `department_id` `department_id` INT(11) NULL DEFAULT NULL, CHANGE COLUMN `user_type_id` `user_type_id` INT(11) NULL DEFAULT NULL", //columns need to match referenced table and column
			"ALTER TABLE `talkback` CHANGE COLUMN `a_from` `a_from` INT(11) NULL DEFAULT NULL",
			"ALTER TABLE `chchchanges` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `department` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `faq` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `faq_faqpage` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `faq_subject` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `faqpage` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `format` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `location` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `location_title` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `pluslet` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `pluslet_subject` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `rank` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `restrictions` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `source` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `staff` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `staff_subject` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `subject` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `talkback` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `title` CHARSET = utf8 , ENGINE = InnoDB",
			"ALTER TABLE `user_type` CHARSET = utf8 , ENGINE = InnoDB",
			"UPDATE `staff` SET `department_id` = NULL WHERE `department_id` = 0",
			"UPDATE `staff` SET `user_type_id` = NULL WHERE `user_type_id` = 0",
			"UPDATE `staff` SET `extra` = '{\"css\": \"basic\"}' WHERE `extra` IS NULL",
			"UPDATE `rank` SET `subject_id` = NULL WHERE `subject_id` = 0",
			"UPDATE `rank` SET `source_id` = NULL WHERE `source_id` = 0",
			"UPDATE `rank` SET `title_id` = NULL WHERE `title_id` = 0",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-12-0\"}' WHERE `extra` LIKE '%{\"maincol\": 100}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-10-2\"}' WHERE `extra` LIKE '%{\"maincol\": 90}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-9-3\"}' WHERE `extra` LIKE '%{\"maincol\": 80}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-8-4\"}' WHERE `extra` LIKE '%{\"maincol\": 70}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-7-5\"}' WHERE `extra` LIKE '%{\"maincol\": 60}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-6-6\"}' WHERE `extra` LIKE '%{\"maincol\": 50}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-5-7\"}' WHERE `extra` LIKE '%{\"maincol\": 40}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-4-8\"}' WHERE `extra` LIKE '%{\"maincol\": 30}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-3-9\"}' WHERE `extra` LIKE '%{\"maincol\": 20}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-2-10\"}' WHERE `extra` LIKE '%{\"maincol\": 10}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-0-12\"}' WHERE `extra` LIKE '%{\"maincol\": 0}%'",
			"UPDATE `subject` SET `extra` = '{\"maincol\": \"0-8-4\"}' WHERE `extra` IS NULL",
			"DELETE FROM `faq_faqpage` WHERE `faq_id` = 0 OR `faqpage_id` = 0",
			"DELETE FROM `faq_subject` WHERE `faq_id` = 0 OR `subject_id` = 0",
			"DELETE FROM `location_title` WHERE `location_id` = 0 OR `title_id` = 0",
			"DELETE FROM `pluslet_subject` WHERE `pluslet_id` = 0 OR `subject_id` = 0",
			"DELETE FROM `staff_subject` WHERE `staff_id` = 0 OR `subject_id` = 0",
			"DELETE FROM `faq_faqpage` WHERE `faq_id` NOT IN (SELECT `faq_id` FROM `faq`) OR `faqpage_id` NOT IN (SELECT `faqpage_id` FROM `faqpage`)",
			"DELETE FROM `faq_subject` WHERE `faq_id` NOT IN (SELECT `faq_id` FROM `faq`) OR `subject_id` NOT IN (SELECT `subject_id` FROM `subject`)",
			"DELETE FROM `location_title` WHERE `location_id` NOT IN (SELECT `location_id` FROM `location`) OR `title_id` NOT IN (SELECT `title_id` FROM `title`)",
			"DELETE FROM `pluslet_subject` WHERE `pluslet_id` NOT IN (SELECT `pluslet_id` FROM `pluslet`) OR `subject_id` NOT IN (SELECT `subject_id` FROM `subject`)",
			"DELETE FROM `rank` WHERE `subject_id` NOT IN (SELECT `subject_id` FROM `subject`) OR `title_id` NOT IN (SELECT `title_id` FROM `title`)
			OR `source_id` NOT IN (SELECT `source_id` FROM `source`)",
			"DELETE FROM `staff_subject` WHERE `staff_id` NOT IN (SELECT `staff_id` FROM `staff`) OR `subject_id` NOT IN (SELECT `subject_id` FROM `subject`)",
			"UPDATE `talkback` SET `a_from` = NULL WHERE `a_from` NOT IN (SELECT `staff_id` FROM `staff`)",
			"CREATE TABLE `temp_chchchanges` LIKE `chchchanges`",
			"INSERT `temp_chchchanges` SELECT o.* FROM `chchchanges` o LEFT OUTER JOIN ( SELECT * FROM (SELECT * FROM `chchchanges` ORDER BY `date_added` DESC) j
			GROUP BY `ourtable`, `record_id`, `message`) n ON o.`chchchanges_id` = n.`chchchanges_id` WHERE n.`chchchanges_id` IS NULL",
			"DELETE FROM `chchchanges` WHERE `chchchanges_id` IN ( SELECT `chchchanges_id` FROM `temp_chchchanges` )",
			"DROP TABLE `temp_chchchanges`"
		);

		//queries to change or drop columns, add referential integrity, add indexes
		$this->lobjAlterTables = array(
			"DROP TABLE IF EXISTS `pluslet_staff`",
			"ALTER TABLE `department` ADD COLUMN `email` VARCHAR(255) NULL DEFAULT NULL  AFTER `telephone`,
			ADD COLUMN `url` VARCHAR(255) NULL DEFAULT NULL  AFTER `email`",
			"ALTER TABLE `faq_subject` CHANGE COLUMN `subject_id` `subject_id` BIGINT(20) NOT NULL",
			"ALTER TABLE `location` DROP COLUMN `limit2` , CHANGE COLUMN `format` `format` BIGINT(20) NULL,
			CHANGE COLUMN `display_note` `display_note` TEXT NULL DEFAULT NULL",
			"ALTER TABLE `location_title` CHANGE COLUMN `location_id` `location_id` BIGINT(20) NOT NULL DEFAULT '0',
			CHANGE COLUMN `title_id` `title_id` BIGINT(20) NOT NULL DEFAULT '0'",
			"ALTER TABLE `pluslet_subject` CHANGE COLUMN `subject_id` `subject_id` BIGINT(20) NOT NULL DEFAULT '0'",
			"ALTER TABLE `staff` CHANGE COLUMN `bio` `bio` BLOB NULL DEFAULT NULL, ADD COLUMN `position_number` VARCHAR(30) NULL DEFAULT NULL  AFTER `bio`,
			ADD COLUMN `job_classification` VARCHAR(255) NULL DEFAULT NULL  AFTER `position_number`,
			ADD COLUMN `room_number` VARCHAR(60) NULL DEFAULT NULL  AFTER `job_classification`, ADD COLUMN `supervisor_id` INT(11) NULL DEFAULT NULL  AFTER `room_number`,
			ADD COLUMN `emergency_contact_name` VARCHAR(150) NULL DEFAULT NULL  AFTER `supervisor_id`,
			ADD COLUMN `emergency_contact_relation` VARCHAR(150) NULL DEFAULT NULL  AFTER `emergency_contact_name`,
			ADD COLUMN `emergency_contact_phone` VARCHAR(60) NULL DEFAULT NULL  AFTER `emergency_contact_relation`,
			ADD COLUMN `street_address` VARCHAR(255) NULL DEFAULT NULL  AFTER `emergency_contact_phone`, ADD COLUMN `city` VARCHAR(150) NULL DEFAULT NULL  AFTER `street_address`,
			ADD COLUMN `state` VARCHAR(60) NULL DEFAULT NULL  AFTER `city` , ADD COLUMN `zip` VARCHAR(30) NULL DEFAULT NULL  AFTER `state`,
			ADD COLUMN `home_phone` VARCHAR(60) NULL DEFAULT NULL  AFTER `zip` , ADD COLUMN `cell_phone` VARCHAR(60) NULL DEFAULT NULL  AFTER `home_phone`,
			ADD COLUMN `fax` VARCHAR(60) NULL DEFAULT NULL  AFTER `cell_phone` , ADD COLUMN `intercom` VARCHAR(30) NULL DEFAULT NULL  AFTER `fax`,
			ADD COLUMN `lat_long` VARCHAR(75) NULL DEFAULT NULL  AFTER `intercom`",
			"ALTER TABLE `staff_subject` CHANGE COLUMN `subject_id` `subject_id` BIGINT(20) NOT NULL DEFAULT '0'",
			"ALTER TABLE `subject` DROP COLUMN `last_modified_by` , DROP COLUMN `created_by` , DROP COLUMN `use_faq` , DROP COLUMN `rss`,
			ADD COLUMN `description` VARCHAR(255) NULL DEFAULT NULL  AFTER `shortform` , ADD COLUMN `keywords` VARCHAR(255) NULL DEFAULT NULL  AFTER `description`",
			"ALTER TABLE `talkback` ADD COLUMN `tbtags` VARCHAR(255) NULL DEFAULT 'main'  AFTER `last_revised_by` , ADD COLUMN `cattags` VARCHAR(255) NULL DEFAULT NULL  AFTER `tbtags`",
			"ALTER TABLE `title` ADD COLUMN `alternate_title` VARCHAR(255) NULL DEFAULT NULL  AFTER `title`",
			"ALTER TABLE `department`  ADD INDEX `INDEXSEARCHdepart` (`name` ASC)",
			"ALTER TABLE `faq_faqpage` ADD CONSTRAINT `fk_ff_faq_id` FOREIGN KEY (`faq_id` ) REFERENCES `faq` (`faq_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_ff_faqpage_id` FOREIGN KEY (`faqpage_id` ) REFERENCES `faqpage` (`faqpage_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD INDEX `fk_ff_faq_id_idx` (`faq_id` ASC), ADD INDEX `fk_ff_faqpage_id_idx` (`faqpage_id` ASC)",
			"ALTER TABLE `faq_subject` ADD CONSTRAINT `fk_fs_faq_id` FOREIGN KEY (`faq_id` ) REFERENCES `faq` (`faq_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_fs_subject_id` FOREIGN KEY (`subject_id` ) REFERENCES `subject` (`subject_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD INDEX `fk_fs_faq_id_idx` (`faq_id` ASC)  , ADD INDEX `fk_fs_subject_id_idx` (`subject_id` ASC)",
			"ALTER TABLE `location`  ADD CONSTRAINT `fk_location_format_id` FOREIGN KEY (`format` ) REFERENCES `format` (`format_id` ) ON DELETE SET NULL ON UPDATE SET NULL,
			ADD CONSTRAINT `fk_location_restrictions_id` FOREIGN KEY (`access_restrictions` ) REFERENCES `restrictions` (`restrictions_id` ) ON DELETE SET NULL ON UPDATE SET NULL,
			ADD INDEX `fk_location_format_id_idx` (`format` ASC)  , ADD INDEX `fk_location_restrictions_id_idx` (`access_restrictions` ASC)",
			"ALTER TABLE `location_title` ADD CONSTRAINT `fk_lt_location_id` FOREIGN KEY (`location_id` ) REFERENCES `location` (`location_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_lt_title_id` FOREIGN KEY (`title_id` ) REFERENCES `title` (`title_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD INDEX `fk_lt_location_id_idx` (`location_id` ASC), ADD INDEX `fk_lt_title_id_idx` (`title_id` ASC)",
			"ALTER TABLE `pluslet`ADD INDEX `INDEXSEARCHpluslet` (`body`(200) ASC)",
			"ALTER TABLE `pluslet_subject` ADD CONSTRAINT `fk_sp_pluslet_id` FOREIGN KEY (`pluslet_id` ) REFERENCES `pluslet` (`pluslet_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_sp_subject_id`  FOREIGN KEY (`subject_id` ) REFERENCES `subject` (`subject_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD INDEX `fk_sp_pluslet_id_idx` (`pluslet_id` ASC)  , ADD INDEX `fk_sp_subject_id_idx` (`subject_id` ASC)",
			"ALTER TABLE `rank` ADD CONSTRAINT `fk_rank_subject_id` FOREIGN KEY (`subject_id` ) REFERENCES `subject` (`subject_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_rank_title_id` FOREIGN KEY (`title_id` ) REFERENCES `title` (`title_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_rank_source_id` FOREIGN KEY (`source_id` ) REFERENCES `source` (`source_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD INDEX `fk_rank_subject_id_idx` (`subject_id` ASC), ADD INDEX `fk_rank_title_id_idx` (`title_id` ASC), ADD INDEX `fk_rank_source_id_idx` (`source_id` ASC)",
			"ALTER TABLE `staff` ADD CONSTRAINT `fk_supervisor_staff_id` FOREIGN KEY (`supervisor_id` ) REFERENCES `staff` (`staff_id` ) ON DELETE SET NULL ON UPDATE SET NULL,
			ADD CONSTRAINT `fk_staff_user_type_id` FOREIGN KEY (`user_type_id` ) REFERENCES `user_type` (`user_type_id` ) ON DELETE SET NULL ON UPDATE SET NULL,
			ADD CONSTRAINT `fk_staff_department_id` FOREIGN KEY (`department_id` ) REFERENCES `department` (`department_id` ) ON DELETE SET NULL ON UPDATE SET NULL,
			ADD INDEX `INDEXSEARCHstaff` (`lname`(255) ASC, `fname`(255) ASC), ADD INDEX `fk_supervisor_staff_id_idx` (`supervisor_id` ASC), ADD INDEX `fk_staff_user_type_id_idx` (`user_type_id` ASC),
			ADD INDEX `fk_staff_department_id_idx` (`department_id` ASC)",
			"ALTER TABLE `staff_subject` ADD CONSTRAINT `fk_ss_subject_id` FOREIGN KEY (`subject_id` ) REFERENCES `subject` (`subject_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_ss_staff_id` FOREIGN KEY (`staff_id` ) REFERENCES `staff` (`staff_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD INDEX `fk_ss_subject_id_idx` (`subject_id` ASC)  , ADD INDEX `fk_ss_staff_id_idx` (`staff_id` ASC)",
			"ALTER TABLE `subject`  ADD INDEX `INDEXSEARCHsubject` (`subject` ASC, `shortform` ASC, `description` ASC, `keywords` ASC)",
			"ALTER TABLE `subject_discipline` ADD CONSTRAINT `fk_sd_subject_id` FOREIGN KEY (`subject_id` ) REFERENCES `subject` (`subject_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_sd_discipline_id` FOREIGN KEY (`discipline_id` ) REFERENCES `discipline` (`discipline_id` ) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD INDEX `fk_sd_subject_id_idx1` (`subject_id` ASC)  , ADD INDEX `fk_sd_discipline_id_idx1` (`discipline_id` ASC)",
			"ALTER TABLE `talkback`  ADD INDEX `INDEXSEARCHtalkback` (`question`(200) ASC, `answer`(200) ASC)",
			"ALTER TABLE `title`  ADD INDEX `INDEXSEARCHtitle` (`title` ASC, `alternate_title` ASC, `description`(200) ASC)",
			"ALTER TABLE `video`  ADD INDEX `INDEXSEARCH` (`title` ASC, `description`(200) ASC)",
			"ALTER TABLE `talkback` ADD CONSTRAINT `fk_talkback_staff_id` FOREIGN KEY (`a_from` ) REFERENCES `staff` (`staff_id` ) ON DELETE SET NULL ON UPDATE SET NULL,
			ADD INDEX `fk_talkback_staff_id_idx` (`a_from` ASC)"
		);
	}

	/**
	 * sp_Updater::displayStartingUpdaterPage() - this method will display the page
	 * that displays before the updater runs update
	 *
	 * @return void
	 */
	public function displayStartingUpdaterPage()
	{
		?>
		<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
			<h2 class="bw_head"><?php echo _( "Update to SubjectsPlus 2.0" ); ?></h2>
			<div class="box" name="error_page">
				<div align="center">
					<p><?php echo _( "Welcome to the SubjectPlus Updater!" ); ?></p>
					<br />
					<p><?php echo _( "Before we begin, if you are currently running version 0.9, please run the <a href=\"migrate_to_v1_fromv09.php\">Migrator</a>." ); ?></p>
					<p><?php echo _( "If you are running version 1.x, follow these steps to update to version 2.0." ); ?></p>
				</div>
				<br />
				<ul>
					<li>
						<strong><?php echo _( "Backup your current SubjectsPlus database" ); ?></strong>
						<br />
						<em style="font-style: italic; font-size: smaller;"><?php echo _( "The updater will examine and update your data in order to word with 2.0. Also if error occurs, you may need to revert back to older database." ); ?></em>
					</li>
					<li><?php echo _( "After <a href=\"?step=1\">Run Updater</a>" ); ?>
						<br />
						<em style="font-style: italic; font-size: smaller;"><?php echo _( "This may take a while. Be patient." ); ?></em>
					</li>
				</ul><br />
			</div>
		</div>
		<?php
	}

	/**
	 * sp_Updater::update() - this method updates to SubjectPlus 2.0
	 *
	 * @return boolean
	 */
	public function update()
	{
		foreach($this->lobjNewTables as $lstrNQuery)
		{
			if( !mysql_query( $lstrNQuery ) )
			{
				$this->displayUpdaterErrorPage( _( "Problem creating new table." ) . "<br />$lstrNQuery" );
				return FALSE;
			}
		}

		foreach($this->lobjInsertInto as $lstrIQuery)
		{
			if( !mysql_query( $lstrIQuery ) )
			{
				$this->displayUpdaterErrorPage( _( "Problem inserting new data into table." ) . "<br />$lstrIQuery" );
				return FALSE;
			}
		}

		if( !$this->fixExistingData() )
		{
			return FALSE;
		}

		if( !$this->beforeAlterQueries() ) return FALSE;

		foreach($this->lobjAlterTables as $lstrAQuery)
		{
			if( !mysql_query( $lstrAQuery ) )
			{
				$this->displayUpdaterErrorPage( _( "Problem altering existing tables." ) . "<br />$lstrAQuery" );
				return FALSE;
			}
		}

		if( !$this->afterAlterQueries() ) return FALSE;

		if( !$this->updateRewriteBases() ) return FALSE;

		return TRUE;
	}

	/**
	 * sp_Updater::displayUpdaterCompletePage() - this method displays the
	 * updater complete page
	 *
	 * @return void
	 */
	public function displayUpdaterCompletePage()
	{
		?>
		<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
			<h2 class="bw_head"><?php echo _( "Update Complete" ); ?></h2>
			<div class="box" name="error_page" align="center">
				<p><?php echo _( "SubjectsPlus update to 2.0 complete. Please log in." ); ?></p>
				<br />
				<p><a href="login.php"><?php echo _( "Log In" ); ?></a></p>
				<br />
				<em style="font-style: italic; font-size: smaller;"><?php echo _( "The first time you log in after update, you must use entire email address for user (e.g. admin@sp.edu)." ); ?></em>
			</div>
		</div>
		<?php
	}

	/**
	 * sp_Updater::displayUpdaterErrorPage() - this methods displays the
	 * updater error page
	 *
	 * @param string $lstrReason
	 * @return
	 */
	private function displayUpdaterErrorPage( $lstrReason = '' )
	{
		?>
		<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
			<h2 class="bw_head"><?php echo _( "Updater Error" ); ?></h2>
			<div class="box" name="error_page" align="center">
				<p><?php echo $lstrReason; ?></p>
				<p><?php echo _( "Please drop database and restore backup before trying again." ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * sp_Updater::fixExistingData() - this method fixes existing data in database by SQL
	 * queries and other means
	 *
	 * @return boolean
	 */
	private function fixExistingData()
	{

		foreach($this->lobjFixData as $lstrFQuery)
		{
			if( !mysql_query( $lstrFQuery ) )
			{
				$this->displayUpdaterErrorPage( _( "Problem fixing existing data." ) . "<br />$lstrFQuery" );
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * sp_Updater::beforeAlterQueries() - this method executes custom queries before
	 * the execution of the alter table queries
	 *
	 * @return boolean
	 */
	private function beforeAlterQueries()
	{
		$lstrQuery = "SHOW COLUMNS FROM `subject` LIKE 'keywords'";

		$lrscResults = mysql_query( $lstrQuery );

		if( mysql_num_rows( $lrscResults ) > 0 )
		{
			$lstrQuery = "ALTER TABLE `subject` CHANGE COLUMN `keywords` `keywords_backup` VARCHAR(255) ";

			if( !mysql_query( $lstrQuery ) )
			{
				$this->displayUpdaterErrorPage( _( "Problem renaming existing keyword column." ) . "<br />$lstrQuery" );
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * sp_Updater::afterAlterQueries() - this method executes custom queries after
	 * the execution of the alter table queries
	 *
	 * @return boolean
	 */
	private function afterAlterQueries()
	{
		$lstrQuery = "SHOW COLUMNS FROM `subject` LIKE 'keywords_backup'";

		$lrscResults = mysql_query( $lstrQuery );

		if( mysql_num_rows( $lrscResults ) > 0 )
		{
			$lstrQuery = "ALTER TABLE `subject` DROP COLUMN `keywords`";

			if( !mysql_query( $lstrQuery ) )
			{
				$this->displayUpdaterErrorPage( _( "Problem droping for existing keyword column." ) . "<br />$lstrQuery" );
				return FALSE;
			}

			$lstrQuery = "ALTER TABLE `subject` CHANGE COLUMN `keywords_backup` `keywords` VARCHAR(255)";

			if( !mysql_query( $lstrQuery ) )
			{
				$this->displayUpdaterErrorPage( _( "Problem renaming for existing keyword column." ) . "<br />$lstrQuery" );
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * sp_Updater::updateRewriteBases() - this method updates all the htaccess files
	 * RewriteBase line based on current installation path
	 *
	 * @return boolean
	 */
	private function updateRewriteBases()
	{
		//get rewrite base
		$lstrRewriteBase = getRewriteBase();

		//get root to subjectsplus path
		$lstrRootPath = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . DIRECTORY_SEPARATOR;

		//all htaccess files needing to update
		$lobjFiles = array( $lstrRootPath . 'subjects' . DIRECTORY_SEPARATOR . '.htaccess',
						 	$lstrRootPath . 'api' . DIRECTORY_SEPARATOR . '.htaccess'
						 );

		//go through each path and replace existing rewrite base with new rewrite base
		foreach( $lobjFiles as $lstrPath )
		{
			$lobjFile = file( $lstrPath );

			foreach( $lobjFile as $lintLineNumber => $lstrLine )
			{
				$lstrLine = preg_replace( '/RewriteBase.*sp\//', "RewriteBase $lstrRewriteBase", $lstrLine);

				$lobjFile[ $lintLineNumber ] = $lstrLine;
			}

			//open the file for writing which will truncate all data on the file.
			$lhndFile = fopen( $lstrPath, 'w' );

			//if opening of the file givers error, return false
			if( $lhndFile === FALSE ) return FALSE;

			//go through and write file array to file
			foreach( $lobjFile as $lstrLine )
			{
				$lboolSuccess = fwrite( $lhndFile, $lstrLine );

				//if the file cannot be written to, return false.
				if( $lboolSuccess === FALSE ) return FALSE;
			}
		}

		return TRUE;
	}
}

?>