<?php
namespace SubjectsPlus\Control;
/**
 * sp_Installer - this class handles the installation of SubjectsPlus
 *
 * @package SubjectsPlus
 * @author dgonzalez
 * @copyright Copyright (c) 2013
 * @version $Id$
 * @access public
 */
class Installer
{
	//class constants
	const PASSWORD_LENGTH = 8;

	//class properties
	private $lobjCreateQueries;
	private $lobjInsertQueries;
	private $lstrRandomPassword;

	function __construct()
	{
                // Put in a filler email domain to be overwritten later after user provides one
                
		$lstrEmailDomain = '@sp.edu';

		//set random password and convert to md5 hash to store in database
		$this->setRandomPassword();
		$lstrHashPassword = md5( scrubData( $this->lstrRandomPassword ) );

		//all the table creation queries
		$this->lobjCreateQueries = array(
					"SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\"",
					"CREATE TABLE `user_type` (
					  `user_type_id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_type` varchar(100) NOT NULL,
					  PRIMARY KEY (`user_type_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `video` (
					  `video_id` int(11) NOT NULL AUTO_INCREMENT,
					  `title` varchar(255) NOT NULL,
					  `description` text,
					  `source` varchar(255) NOT NULL,
					  `foreign_id` varchar(255) NOT NULL,
					  `duration` varchar(50) DEFAULT NULL,
					  `date` date NOT NULL,
					  `display` int(1) NOT NULL DEFAULT '0',
					  `vtags` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`video_id`),
					  KEY `INDEXSEARCH` (`title`,`description`(200))
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `chchchanges` (
					  `chchchanges_id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `staff_id` int(11) NOT NULL,
					  `ourtable` varchar(50) CHARACTER SET latin1 NOT NULL,
					  `record_id` int(11) NOT NULL,
					  `record_title` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
					  `message` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
					  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					  PRIMARY KEY (`chchchanges_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `discipline` (
					  `discipline_id` int(11) NOT NULL AUTO_INCREMENT,
					  `discipline` varchar(100) CHARACTER SET latin1 NOT NULL,
					  `sort` int(11) NOT NULL,
					  PRIMARY KEY (`discipline_id`),
					  UNIQUE KEY `discipline` (`discipline`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='added v2'",
					"CREATE TABLE `faqpage` (
					  `faqpage_id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) NOT NULL,
					  `description` text NOT NULL,
					  PRIMARY KEY (`faqpage_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `source` (
					  `source_id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `source` varchar(255) DEFAULT NULL,
					  `rs` int(10) DEFAULT NULL,
					  PRIMARY KEY (`source_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `department` (
					  `department_id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(100) NOT NULL DEFAULT '',
					  `department_sort` int(11) NOT NULL DEFAULT '0',
					  `telephone` varchar(20) NOT NULL DEFAULT '0',
					  `email` varchar(255) DEFAULT NULL,
					  `url` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`department_id`),
					  KEY `INDEXSEARCHdepart` (`name`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `subject` (
					  `subject_id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `subject` varchar(255) DEFAULT NULL,
					  `active` int(1) NOT NULL DEFAULT '0',
					  `shortform` varchar(50) NOT NULL DEFAULT '0',
					  `redirect_url` varchar(255) DEFAULT NULL,
					  `header` varchar(45) DEFAULT NULL,
					  `description` varchar(255) DEFAULT NULL,
					  `keywords` varchar(255) DEFAULT NULL,
					  `type` varchar(20) DEFAULT NULL,
					  `last_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					  `background_link` varchar(255) DEFAULT NULL,
					  `extra` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`subject_id`),
					  KEY `INDEXSEARCHsubject` (`subject`,`shortform`,`description`,`keywords`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `pluslet` (
					  `pluslet_id` int(11) NOT NULL AUTO_INCREMENT,
					  `title` varchar(100) NOT NULL DEFAULT '',
					  `body` longtext NOT NULL,
					  `local_file` varchar(100) DEFAULT NULL,
					  `clone` int(1) NOT NULL DEFAULT '0',
					  `type` varchar(50) DEFAULT NULL,
					  `extra` MEDIUMTEXT DEFAULT NULL,
					  `hide_titlebar` int(1) NOT NULL DEFAULT '0',
					  `collapse_body` int(1) NOT NULL DEFAULT '0',
					  `titlebar_styling` varchar(100) DEFAULT NULL,
					  `favorite_box` int(1) NOT NULL DEFAULT '0',
				      `master` int(1) NOT NULL DEFAULT '0',
				      `target_blank_links` int(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`pluslet_id`),
					  KEY `INDEXSEARCHpluslet` (`body`(200))
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `format` (
					  `format_id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `format` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`format_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `faq` (
					  `faq_id` int(11) NOT NULL AUTO_INCREMENT,
					  `question` varchar(255) NOT NULL,
					  `answer` text NOT NULL,
					  `keywords` varchar(255) NOT NULL,
					  PRIMARY KEY (`faq_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `restrictions` (
					  `restrictions_id` int(10) NOT NULL AUTO_INCREMENT,
					  `restrictions` text,
					  PRIMARY KEY (`restrictions_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `title` (
					  `title_id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `title` varchar(255) DEFAULT NULL,
					  `alternate_title` varchar(255) DEFAULT NULL,
					  `description` text,
					  `pre` varchar(255) DEFAULT NULL,
					  `last_modified_by` varchar(50) DEFAULT NULL,
					  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  PRIMARY KEY (`title_id`),
					  KEY `INDEXSEARCHtitle` (`title`,`alternate_title`,`description`(200))
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `rank` (
					  `rank_id` int(11) NOT NULL AUTO_INCREMENT,
					  `rank` int(10) NOT NULL DEFAULT '0',
					  `subject_id` bigint(20) DEFAULT NULL,
					  `title_id` bigint(20) DEFAULT NULL,
					  `source_id` bigint(20) DEFAULT NULL,
					  `description_override` text,
					  PRIMARY KEY (`rank_id`),
					  KEY `fk_rank_subject_id_idx` (`subject_id`),
					  KEY `fk_rank_title_id_idx` (`title_id`),
					  KEY `fk_rank_source_id_idx` (`source_id`),
					  CONSTRAINT `fk_rank_source_id` FOREIGN KEY (`source_id`) REFERENCES `source` (`source_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `fk_rank_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `fk_rank_title_id` FOREIGN KEY (`title_id`) REFERENCES `title` (`title_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `tab` (
					  `tab_id` int(11) NOT NULL AUTO_INCREMENT,
					  `subject_id` bigint(20) NOT NULL DEFAULT '0',
					  `label` varchar(120) NOT NULL DEFAULT 'Main',
					  `tab_index` int(11) NOT NULL DEFAULT '0',
					  `external_url` varchar(500) DEFAULT NULL,
					  `visibility` int(1) NOT NULL DEFAULT '1',
					  `parent` varchar(500) DEFAULT NULL,
					  `children` varchar(500) DEFAULT NULL,
					  `extra` varchar(500) DEFAULT NULL,
					  PRIMARY KEY (`tab_id`),
					  KEY `fk_t_subject_id_idx` (`subject_id`),
					  CONSTRAINT `fk_t_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `subject_subject` (
					  `id_subject_subject` int(11) NOT NULL AUTO_INCREMENT,
					  `subject_parent` bigint(20) NOT NULL,
					  `subject_child` bigint(20) NOT NULL,
					  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					  PRIMARY KEY (`id_subject_subject`),
					  KEY `fk_subject_parent_idx` (`subject_parent`),
					  KEY `fk_subject_child_idx` (`subject_child`),
					  CONSTRAINT `fk_subject_child` FOREIGN KEY (`subject_child`) REFERENCES `subject` (`subject_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
					  CONSTRAINT `fk_subject_parent` FOREIGN KEY (`subject_parent`) REFERENCES `subject` (`subject_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `staff` (
					  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
					  `lname` varchar(765) DEFAULT NULL,
					  `fname` varchar(765) DEFAULT NULL,
					  `title` varchar(765) DEFAULT NULL,
					  `tel` varchar(45) DEFAULT NULL,
					  `department_id` int(11) DEFAULT NULL,
					  `staff_sort` int(11) DEFAULT NULL,
					  `email` varchar(765) DEFAULT NULL,
					  `ip` varchar(300) DEFAULT NULL,
					  `access_level` int(11) DEFAULT NULL,
					  `user_type_id` int(11) DEFAULT NULL,
					  `password` varchar(192) DEFAULT NULL,
					  `active` int(1) DEFAULT NULL,
					  `ptags` varchar(765) DEFAULT NULL,
					  `extra` varchar(765) DEFAULT NULL,
					  `bio` blob,
					  `position_number` varchar(30) DEFAULT NULL,
					  `job_classification` varchar(255) DEFAULT NULL,
					  `room_number` varchar(60) DEFAULT NULL,
					  `supervisor_id` int(11) DEFAULT NULL,
					  `emergency_contact_name` varchar(150) DEFAULT NULL,
					  `emergency_contact_relation` varchar(150) DEFAULT NULL,
					  `emergency_contact_phone` varchar(60) DEFAULT NULL,
					  `street_address` varchar(255) DEFAULT NULL,
					  `city` varchar(150) DEFAULT NULL,
					  `state` varchar(60) DEFAULT NULL,
					  `zip` varchar(30) DEFAULT NULL,
					  `home_phone` varchar(60) DEFAULT NULL,
					  `cell_phone` varchar(60) DEFAULT NULL,
					  `fax` varchar(60) DEFAULT NULL,
					  `intercom` varchar(30) DEFAULT NULL,
					  `lat_long` varchar(75) DEFAULT NULL,
					  `social_media` MEDIUMTEXT DEFAULT NULL,
					  PRIMARY KEY (`staff_id`),
					  KEY `fk_supervisor_staff_id_idx` (`supervisor_id`),
					  KEY `fk_staff_user_type_id_idx` (`user_type_id`),
					  KEY `fk_staff_department_id_idx` (`department_id`),
					  KEY `INDEXSEARCHstaff` (`lname`(255),`fname`(255)),
					  CONSTRAINT `fk_staff_user_type_id` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`user_type_id`) ON DELETE SET NULL ON UPDATE SET NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `talkback` (
					  `talkback_id` int(11) NOT NULL AUTO_INCREMENT,
					  `question` text NOT NULL,
					  `q_from` varchar(100) DEFAULT '',
					  `date_submitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					  `answer` text NOT NULL,
					  `a_from` int(11) DEFAULT NULL,
					  `display` varchar(11) NOT NULL DEFAULT 'No',
					  `last_revised_by` varchar(100) NOT NULL DEFAULT '',
					  `tbtags` varchar(255) DEFAULT 'main',
					  `cattags` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`talkback_id`),
					  KEY `INDEXSEARCHtalkback` (`question`(200),`answer`(200)),
					  KEY `fk_talkback_staff_id_idx` (`a_from`),
					  CONSTRAINT `fk_talkback_staff_id` FOREIGN KEY (`a_from`) REFERENCES `staff` (`staff_id`) ON DELETE SET NULL ON UPDATE SET NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `section` (
					  `section_id` int(11) NOT NULL AUTO_INCREMENT,
					  `section_index` int(11) NOT NULL DEFAULT '0',
					  `layout` varchar(255) NOT NULL DEFAULT '4-4-4',
					  `tab_id` int(11) NOT NULL,
					  PRIMARY KEY (`section_id`),
					  KEY `fk_section_tab_idx` (`tab_id`),
					  CONSTRAINT `fk_section_tab` FOREIGN KEY (`tab_id`) REFERENCES `tab` (`tab_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `faq_faqpage` (
					  `faq_faqpage_id` int(11) NOT NULL AUTO_INCREMENT,
					  `faq_id` int(11) NOT NULL,
					  `faqpage_id` int(11) NOT NULL,
					  PRIMARY KEY (`faq_faqpage_id`),
					  KEY `fk_ff_faq_id_idx` (`faq_id`),
					  KEY `fk_ff_faqpage_id_idx` (`faqpage_id`),
					  CONSTRAINT `fk_ff_faqpage_id` FOREIGN KEY (`faqpage_id`) REFERENCES `faqpage` (`faqpage_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `fk_ff_faq_id` FOREIGN KEY (`faq_id`) REFERENCES `faq` (`faq_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `location` (
					  `location_id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `format` bigint(20) DEFAULT NULL,
					  `call_number` varchar(255) DEFAULT NULL,
					  `location` varchar(255) DEFAULT NULL,
					  `access_restrictions` int(10) DEFAULT NULL,
					  `eres_display` varchar(1) DEFAULT NULL,
					  `display_note` text,
					  `helpguide` varchar(255) DEFAULT NULL,
					  `citation_guide` varchar(255) DEFAULT NULL,
					  `ctags` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`location_id`),
					  KEY `fk_location_format_id_idx` (`format`),
					  KEY `fk_location_restrictions_id_idx` (`access_restrictions`),
					  CONSTRAINT `fk_location_format_id` FOREIGN KEY (`format`) REFERENCES `format` (`format_id`) ON DELETE SET NULL ON UPDATE SET NULL,
					  CONSTRAINT `fk_location_restrictions_id` FOREIGN KEY (`access_restrictions`) REFERENCES `restrictions` (`restrictions_id`) ON DELETE SET NULL ON UPDATE SET NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `faq_subject` (
					  `faq_subject_id` int(11) NOT NULL AUTO_INCREMENT,
					  `faq_id` int(11) NOT NULL,
					  `subject_id` bigint(20) NOT NULL,
					  PRIMARY KEY (`faq_subject_id`),
					  KEY `fk_fs_faq_id_idx` (`faq_id`),
					  KEY `fk_fs_subject_id_idx` (`subject_id`),
					  CONSTRAINT `fk_fs_faq_id` FOREIGN KEY (`faq_id`) REFERENCES `faq` (`faq_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `fk_fs_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `location_title` (
					  `location_id` bigint(20) NOT NULL DEFAULT '0',
					  `title_id` bigint(20) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`location_id`,`title_id`),
					  KEY `fk_lt_location_id_idx` (`location_id`),
					  KEY `fk_lt_title_id_idx` (`title_id`),
					  CONSTRAINT `fk_lt_location_id` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `fk_lt_title_id` FOREIGN KEY (`title_id`) REFERENCES `title` (`title_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `subject_discipline` (
					  `subject_id` bigint(20) NOT NULL,
					  `discipline_id` int(11) NOT NULL,
					  PRIMARY KEY (`subject_id`,`discipline_id`),
					  KEY `discipline_id` (`discipline_id`),
					  KEY `fk_sd_subject_id_idx` (`subject_id`),
					  KEY `fk_sd_discipline_id_idx` (`discipline_id`),
					  KEY `fk_sd_subject_id_idx1` (`subject_id`),
					  KEY `fk_sd_discipline_id_idx1` (`discipline_id`),
					  CONSTRAINT `fk_sd_discipline_id` FOREIGN KEY (`discipline_id`) REFERENCES `discipline` (`discipline_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `fk_sd_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='added v2'",
					"CREATE TABLE `staff_subject` (
					  `staff_id` int(11) NOT NULL DEFAULT '0',
					  `subject_id` bigint(20) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`staff_id`,`subject_id`),
					  KEY `fk_ss_subject_id_idx` (`subject_id`),
					  KEY `fk_ss_staff_id_idx` (`staff_id`),
					  CONSTRAINT `fk_ss_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `fk_ss_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					"CREATE TABLE `pluslet_section` (
					  `pluslet_section_id` int(11) NOT NULL AUTO_INCREMENT,
					  `pluslet_id` int(11) NOT NULL DEFAULT '0',
					  `section_id` int(11) NOT NULL,
					  `pcolumn` int(11) NOT NULL,
					  `prow` int(11) NOT NULL,
					  PRIMARY KEY (`pluslet_section_id`),
					  KEY `fk_pt_pluslet_id_idx` (`pluslet_id`),
					  KEY `fk_pt_tab_id_idx` (`section_id`),
					  CONSTRAINT `fk_pt_section_id` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8", 
				"CREATE TABLE `stats` (
  `stats_id` int(11) NOT NULL AUTO_INCREMENT,
  `http_referer` varchar(200) DEFAULT NULL,
  `query_string` varchar(200) DEFAULT NULL,
  `remote_address` varchar(200) DEFAULT NULL,
  `guide_page` varchar(200) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `page_title` varchar(200) DEFAULT NULL,
  `user_agent` varchar(200) DEFAULT NULL,
  `subject_short_form` varchar(200) DEFAULT NULL,
  `event_type` varchar(200) DEFAULT NULL,
  `tab_name` varchar(200) DEFAULT NULL,
  `link_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`stats_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8",
			
				"CREATE TABLE IF NOT EXISTS `collection` (
						`collection_id` int(11) NOT NULL AUTO_INCREMENT,
						`title` text NOT NULL,
						`description` text NOT NULL,
						`shortform` text NOT NULL,
						PRIMARY KEY (`collection_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8",
				
				"CREATE TABLE IF NOT EXISTS `collection_subject` (
						`collection_subject_id` int(11) NOT NULL AUTO_INCREMENT,
						`collection_id` int(11) NOT NULL,
						`subject_id` int(11) NOT NULL,
						`sort` int(11) NOT NULL,
						PRIMARY KEY (`collection_subject_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8",

				"CREATE TABLE IF NOT EXISTS `staff_department` (
  					`staff_id` int(11) NOT NULL AUTO_INCREMENT,
  					`department_id` int(11) NOT NULL,
  					PRIMARY KEY (`staff_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Added v4'"
			
			);

		//all the subjectqueries -- default data
		$this->lobjInsertQueries = array(
					"INSERT INTO `chchchanges` VALUES (1,1,'guide',1,'General','insert','2011-03-26 19:16:19'),(2,1,'record',1,'Sample Record','insert','2011-03-26 20:08:54')",
					"INSERT INTO `subject` VALUES (1,'General',1,'general','',NULL,NULL,NULL,'Subject','2011-03-26 19:16:19',NULL,'{\"maincol\":\"\"}')",
					"INSERT INTO `tab` VALUES (1,1,'Main',0,NULL,1,NULL,NULL,NULL)",
					"INSERT INTO `section` VALUES (1,0,'4-6-2',1)",
					"INSERT INTO `pluslet` VALUES 
				(1,'All Items by Source','','',0,'Special','',0,0,NULL,0,NULL,0),
				(2,'Key to Icons','','',0,'Special','',0,0,NULL,0,NULL,0),
				(3,'Subject Specialist','','',0,'Special','',0,0,NULL,0,NULL,0),
				(4,'FAQs','','',0,'Special','',0,0,NULL,0,NULL,0),
				(5,'Books:  Use the Library Catalog','','',0,'Special','',0,0,NULL,0,NULL,0),
				(6,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0),
				(7,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0),
				(8,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0),
				(9,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0),
				(10,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0),
				(11,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0),
				(12,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0),
				(13,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0),
				(14,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0),
				(15,'','','',0,'Reserved_for_Special','',0,0,NULL,0,NULL,0)",
					"INSERT INTO `format` VALUES (1,'Web'),(2,'Print'),(3,'Print w/ URL')",
					"INSERT INTO `user_type` VALUES (1,'Staff'),(2,'Machine'),(3,'Student')",
					"INSERT INTO `discipline` VALUES (1,'agriculture',1),(2,'anatomy &amp; physiology',2),(3,'anthropology',3),(4,'applied sciences',4),(5,'architecture',5),
					(6,'astronomy &amp; astrophysics',6),(7,'biology',7),(8,'botany',8),(9,'business',9),(10,'chemistry',10),(11,'computer science',11),(12,'dance',12),(13,'dentistry',13),
					(14,'diet &amp; clinical nutrition',14),(15,'drama',15),(16,'ecology',16),(17,'economics',17),(18,'education',18),(19,'engineering',19),(20,'environmental sciences',20),
					(21,'film',21),(22,'forestry',22),(23,'geography',23),(24,'geology',24),(25,'government',25),(26,'history &amp; archaeology',26),(27,'human anatomy &amp; physiology',27),
					(28,'international relations',28),(29,'journalism &amp; communications',29),(30,'languages &amp; literatures',30),(31,'law',31),(32,'library &amp; information science',32),
					(33,'mathematics',33),(34,'medicine',34),(35,'meteorology &amp; climatology',35),(36,'military &amp; naval science',36),(37,'music',37),(38,'nursing',38),
					(39,'occupational therapy &amp; rehabilitation',39),(40,'oceanography',40),(41,'parapsychology &amp; occult sciences',41),(42,'pharmacy, therapeutics, &amp; pharmacology',42),
					(43,'philosophy',43),(44,'physical therapy',44),(45,'physics',45),(46,'political science',46),(47,'psychology',47),(48,'public health',48),(49,'recreation &amp; sports',49),
					(50,'religion',50),(51,'sciences (general)',51),(52,'social sciences (general)',52),(53,'social welfare &amp; social work',53),(54,'sociology &amp; social history',54),
					(55,'statistics',55),(56,'veterinary medicine',56),(57,'visual arts',57),(58,'women&#039;s studies',58),(59,'zoology',59)",
					"INSERT INTO `restrictions` VALUES (1,'None'),(2,'Restricted'),(3,'On Campus Only'),(4,'Rest--No Proxy')",
					"INSERT INTO `title` VALUES (1,'Sample Record',NULL,'Here you can enter a description of the record.&nbsp; A description may be overwritten for a given subject by clicking the icon next to the desired subject in the Record screen.<br />',
					NULL,NULL,'2011-03-26 20:08:54')",
					"INSERT INTO `source` VALUES (1,'Journals/Magazines',1),(2,'Newspapers',5),(3,'Web Sites',10),(4,'FAQs',15),(5,'Almanacs & Yearbooks',100),(6,'Atlases',100),(7,'Bibliographies',100),
					(8,'Biographical Information',100),(9,'Concordances',100),(10,'Dictionaries',100),(11,'Encyclopedias',100),(12,'Government Information',100),(13,'Grants/Scholarships/Financial Aid',100),
					(14,'Handbooks & Guides',100),(15,'Images',100),(16,'Local',100),(17,'Primary Sources',100),(18,'Quotations',100),(19,'Regional',100),(20,'Reviews',100),(21,'Statistics/Data',100),
					(22,'Directories',100),(23,'Dissertations',100),(24,'Newspapers--International',100),(25,'Newswires',100),(26,'TV Stations',100),(27,'Radio Stations',100),(28,'Transcripts',100),
					(30,'Audio Files',100),(31,'Organizations',100)",
					"INSERT INTO `location` VALUES (1,1,'','http://www.subjectsplus.com/wiki/',1,'Y','',NULL,NULL,'')",
					"INSERT INTO `department` VALUES (1,'Library Administration',1,'5555',NULL,NULL)",
					"INSERT INTO `staff` VALUES (1,'Admin','Super','SubjectsPlus Admin','5555',1,0,'admin$lstrEmailDomain','',0,1,'{$lstrHashPassword}',1,'talkback|faq|records|eresource_mgr|videos|admin|librarian|supervisor','{\"css\": \"basic\"}',
					'This is the default user with a SubjectsPlus install.  You should delete or rename me before you go live!',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)",
					"INSERT INTO `location_title` VALUES (1,1)",
					"INSERT INTO `rank` VALUES (1,0,1,1,1,'')",
					"INSERT INTO `staff_subject` VALUES (1,1)"
			);
	}

	/**
	 * sp_Installer::install() - this method performs the installation of SubjectsPlus
	 *
	 * @return boolean
	 */
	public function install( )
	{
        $db = new Querier;
		foreach($this->lobjCreateQueries as $lstrCQuery)
		{
			if( $db->exec( $lstrCQuery ) === FALSE )
			{
				var_dump($db->errorInfo());
				$this->displayInstallationErrorPage( _( "Problem creating new table." ) );
				return FALSE;
			}
		}

		foreach($this->lobjInsertQueries as $lstrIQuery)
		{
			if( $db->exec( $lstrIQuery ) === FALSE )
			{
				$this->displayInstallationErrorPage( _( "Problem inserting new data into table." ) );
				$error_info = $db->errorInfo();
				if (count_chars($error_info[2]) > 0) {
					var_dump($db->errorInfo());
					echo $lstrIQuery;
				}
				
				return FALSE;
			}
		}

		if( !$this->updateRewriteBases() )
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * sp_Installer::displayInstallationCompletePage() - this method displays the
	 * installation complete page
	 *
	 * @return void
	 */
	public function displayInstallationCompletePage()
	{
	global $administrator_email;
    $db = new Querier; 
    $db->exec("UPDATE staff SET staff.email=". $db->quote($administrator_email) . " WHERE staff.staff_id = 1");

		?>
		<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
			<div class="install-pluslet" name="error_page" align="center">
				<h2 class="bw_head"><?php echo _( "Installation Complete" ); ?></h2>

				<p><?php echo _( "SubjectsPlus installation complete. Please log in." ); ?></p>
				<p><?php echo _( "<strong>Username: </strong> " ) . "$administrator_email"; ?></p>
				<p><?php echo _( "<strong>Password: </strong> " ); echo htmlentities($this->lstrRandomPassword) ?></p>
				<p><a href="login.php" target="_blank"><?php echo _( "Log In" ); ?></a></p>
			</div>
		</div>
		<?php
	}

	/**
	 * sp_Installer::displayInstallationErrorPage() - this methods displays the
	 * installation error page
	 *
	 * @param string $lstrReason
	 * @return
	 */
	private function displayInstallationErrorPage( $lstrReason = '' )
	{
		?>
		<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
	<div class="install-pluslet" name="error_page" align="center">
			<h2 class="bw_head"><?php echo _( "Installation Error" ); ?></h2>

				<p><?php echo $lstrReason; ?></p>
				<p><?php echo _( "Try again." ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * sp_Installer::setRandomPassword() - generates and sets a random password
	 * containing 2 lower case letters, 2 upper case letters, 2 digits, and 2 special
	 * characters
	 *
	 * @return
	 */
	private function setRandomPassword()
	{
		//list of characters to use for random password generation
		$lstrLowerCaseLetters = "abcdefghijklmnopqrstuvwxyz";
		$lstrUpperCaseLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$lstrNumbers = "0123456789";
		$lstrSpecialCharacters = "!@#%^&*";
		$lstrRandomString = '';

		//select 2 random chracters of each list
		$lstrRandomString .= $lstrLowerCaseLetters[rand(0, strlen($lstrLowerCaseLetters) - 1)];
		$lstrRandomString .= $lstrLowerCaseLetters[rand(0, strlen($lstrLowerCaseLetters) - 1)];
		$lstrRandomString .= $lstrUpperCaseLetters[rand(0, strlen($lstrUpperCaseLetters) - 1)];
		$lstrRandomString .= $lstrUpperCaseLetters[rand(0, strlen($lstrUpperCaseLetters) - 1)];
		$lstrRandomString .= $lstrNumbers[rand(0, strlen($lstrNumbers) - 1)];
		$lstrRandomString .= $lstrNumbers[rand(0, strlen($lstrNumbers) - 1)];
		$lstrRandomString .= $lstrSpecialCharacters[rand(0, strlen($lstrSpecialCharacters) - 1)];
		$lstrRandomString .= $lstrSpecialCharacters[rand(0, strlen($lstrSpecialCharacters) - 1)];

		//mix up random string to create random password
		for ($i = 0; $i < self::PASSWORD_LENGTH; $i++)
		{
			$lintPosition = rand(0, strlen($lstrRandomString) - 1);

			$this->lstrRandomPassword .= $lstrRandomString[$lintPosition];

			$lstrRandomString = substr_replace( $lstrRandomString, '', $lintPosition, 1 );
		}
	}
	

	/**
	 * sp_Installer::updateRewriteBases() - this method updates all the htaccess files
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
