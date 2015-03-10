DROP DATABASE IF EXISTS sp_test;

CREATE DATABASE  IF NOT EXISTS `sp_test` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `sp_test`;
-- MySQL dump 10.13  Distrib 5.6.19, for linux-glibc2.5 (x86_64)
--
-- Host: 127.0.0.1    Database: sp
-- ------------------------------------------------------
-- Server version	5.5.41-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `chchchanges`
--

DROP TABLE IF EXISTS `chchchanges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chchchanges` (
  `chchchanges_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `ourtable` varchar(50) CHARACTER SET latin1 NOT NULL,
  `record_id` int(11) NOT NULL,
  `record_title` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `message` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`chchchanges_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chchchanges`
--

LOCK TABLES `chchchanges` WRITE;
/*!40000 ALTER TABLE `chchchanges` DISABLE KEYS */;
INSERT INTO `chchchanges` VALUES (1,1,'guide',1,'General','insert','2011-03-26 23:16:19'),(2,1,'record',1,'Sample Record','insert','2011-03-27 00:08:54'),(3,1,'guide',2,'test','insert','2015-02-18 20:16:20'),(4,1,'guide',60857,'','delete','2015-02-20 14:32:46'),(5,1,'guide',60857,'','delete','2015-02-20 14:35:26'),(6,1,'guide',60857,'','delete','2015-02-20 14:38:37'),(7,1,'guide',60857,'','delete','2015-02-20 14:41:05'),(8,1,'guide',60857,'','delete','2015-02-20 14:41:34'),(9,1,'guide',10536,'','delete','2015-02-20 14:46:05');
/*!40000 ALTER TABLE `chchchanges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `department_sort` int(11) NOT NULL DEFAULT '0',
  `telephone` varchar(20) NOT NULL DEFAULT '0',
  `email` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`department_id`),
  KEY `INDEXSEARCHdepart` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'Library Administration',1,'5555',NULL,NULL);
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discipline`
--

DROP TABLE IF EXISTS `discipline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discipline` (
  `discipline_id` int(11) NOT NULL AUTO_INCREMENT,
  `discipline` varchar(100) CHARACTER SET latin1 NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`discipline_id`),
  UNIQUE KEY `discipline` (`discipline`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COMMENT='added v2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discipline`
--

LOCK TABLES `discipline` WRITE;
/*!40000 ALTER TABLE `discipline` DISABLE KEYS */;
INSERT INTO `discipline` VALUES (1,'agriculture',1),(2,'anatomy &amp; physiology',2),(3,'anthropology',3),(4,'applied sciences',4),(5,'architecture',5),(6,'astronomy &amp; astrophysics',6),(7,'biology',7),(8,'botany',8),(9,'business',9),(10,'chemistry',10),(11,'computer science',11),(12,'dance',12),(13,'dentistry',13),(14,'diet &amp; clinical nutrition',14),(15,'drama',15),(16,'ecology',16),(17,'economics',17),(18,'education',18),(19,'engineering',19),(20,'environmental sciences',20),(21,'film',21),(22,'forestry',22),(23,'geography',23),(24,'geology',24),(25,'government',25),(26,'history &amp; archaeology',26),(27,'human anatomy &amp; physiology',27),(28,'international relations',28),(29,'journalism &amp; communications',29),(30,'languages &amp; literatures',30),(31,'law',31),(32,'library &amp; information science',32),(33,'mathematics',33),(34,'medicine',34),(35,'meteorology &amp; climatology',35),(36,'military &amp; naval science',36),(37,'music',37),(38,'nursing',38),(39,'occupational therapy &amp; rehabilitation',39),(40,'oceanography',40),(41,'parapsychology &amp; occult sciences',41),(42,'pharmacy, therapeutics, &amp; pharmacology',42),(43,'philosophy',43),(44,'physical therapy',44),(45,'physics',45),(46,'political science',46),(47,'psychology',47),(48,'public health',48),(49,'recreation &amp; sports',49),(50,'religion',50),(51,'sciences (general)',51),(52,'social sciences (general)',52),(53,'social welfare &amp; social work',53),(54,'sociology &amp; social history',54),(55,'statistics',55),(56,'veterinary medicine',56),(57,'visual arts',57),(58,'women&#039;s studies',58),(59,'zoology',59);
/*!40000 ALTER TABLE `discipline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_faqpage`
--

DROP TABLE IF EXISTS `faq_faqpage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq_faqpage` (
  `faq_faqpage_id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_id` int(11) NOT NULL,
  `faqpage_id` int(11) NOT NULL,
  PRIMARY KEY (`faq_faqpage_id`),
  KEY `fk_ff_faq_id_idx` (`faq_id`),
  KEY `fk_ff_faqpage_id_idx` (`faqpage_id`),
  CONSTRAINT `fk_ff_faqpage_id` FOREIGN KEY (`faqpage_id`) REFERENCES `faqpage` (`faqpage_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ff_faq_id` FOREIGN KEY (`faq_id`) REFERENCES `faq` (`faq_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_faqpage`
--

LOCK TABLES `faq_faqpage` WRITE;
/*!40000 ALTER TABLE `faq_faqpage` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq_faqpage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_subject`
--

DROP TABLE IF EXISTS `faq_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq_subject` (
  `faq_subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_id` int(11) NOT NULL,
  `subject_id` bigint(20) NOT NULL,
  PRIMARY KEY (`faq_subject_id`),
  KEY `fk_fs_faq_id_idx` (`faq_id`),
  KEY `fk_fs_subject_id_idx` (`subject_id`),
  CONSTRAINT `fk_fs_faq_id` FOREIGN KEY (`faq_id`) REFERENCES `faq` (`faq_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_fs_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_subject`
--

LOCK TABLES `faq_subject` WRITE;
/*!40000 ALTER TABLE `faq_subject` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq_subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqpage`
--

DROP TABLE IF EXISTS `faqpage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqpage` (
  `faqpage_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`faqpage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqpage`
--

LOCK TABLES `faqpage` WRITE;
/*!40000 ALTER TABLE `faqpage` DISABLE KEYS */;
/*!40000 ALTER TABLE `faqpage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `format`
--

DROP TABLE IF EXISTS `format`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `format` (
  `format_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `format` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`format_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `format`
--

LOCK TABLES `format` WRITE;
/*!40000 ALTER TABLE `format` DISABLE KEYS */;
INSERT INTO `format` VALUES (1,'Web'),(2,'Print'),(3,'Print w/ URL');
/*!40000 ALTER TABLE `format` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
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
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (1,1,'','http://www.subjectsplus.com/wiki/',1,'Y','',NULL,NULL,''),(2,1,NULL,'http://library.miami.edu/sp/subjects/new_acquisitions.php?acq=0&sub=29',1,'N',NULL,NULL,NULL,NULL),(3,1,NULL,'http://www.as.miami.edu/judaic/',1,'N',NULL,NULL,NULL,NULL),(4,1,NULL,'http://www.library.miami.edu/#',1,'N',NULL,NULL,NULL,NULL),(5,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://search.epnet.com/login.asp?custid=s3593571&profile=ebscohost&defaultdb=rfh',1,'N',NULL,NULL,NULL,NULL),(6,1,NULL,'http://www.brillonline.nl/subscriber/uid=3331/title_home?title_id=bdr_bdr',1,'N',NULL,NULL,NULL,NULL),(7,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.oxfordislamicstudies.com',1,'N',NULL,NULL,NULL,NULL),(8,1,NULL,'http://www.brillonline.nl/subscriber/uid=3331/title_home?title_id=q3_q3',1,'N',NULL,NULL,NULL,NULL),(9,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.brillonline.nl/subscriber/uid=3331/title_home?title_id=ei3_ei3',1,'N',NULL,NULL,NULL,NULL),(10,1,NULL,'http://www.brillonline.nl/subscriber/uid=3331/title_home?title_id=islam_islam',1,'N',NULL,NULL,NULL,NULL),(11,1,NULL,'http://www.brillonline.nl/subscriber/uid=3331/title_home?title_id=ewic_ewic',1,'N',NULL,NULL,NULL,NULL),(12,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://search.ebscohost.com/login.aspx?authtype=ip,uid&profile=ehost&defaultdb=ich',1,'N',NULL,NULL,NULL,NULL),(13,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://quod.lib.umich.edu/b/bas/',1,'N',NULL,NULL,NULL,NULL),(14,1,NULL,'http://www.oxfordreference.com/views/SUBJECT_SEARCH.html?subject=s22',1,'N',NULL,NULL,NULL,NULL),(15,1,NULL,'http://www.iranica.com/newsite/home/index.isc',1,'N',NULL,NULL,NULL,NULL),(16,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.csa.com/htbin/dbrng.cgi?username=ray58&access=ray5858&adv=1',1,'N',NULL,NULL,NULL,NULL),(17,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.itergateway.org/',1,'N',NULL,NULL,NULL,NULL),(18,1,NULL,'http://www.library.miami.edu/search/eresources/infosheet.php?service_id=134',1,'N',NULL,NULL,NULL,NULL),(19,1,NULL,'http://www.library.miami.edu/search/eresources/infosheet.php?service_id=692',1,'N',NULL,NULL,NULL,NULL),(20,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://firstsearch.oclc.org/FSIP?autho=100106045&dbname=AnthropologyPlus',1,'N',NULL,NULL,NULL,NULL),(21,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.anthrosource.net/',1,'N',NULL,NULL,NULL,NULL),(22,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.csa.com/htbin/dbrng.cgi?username=ray58&access=ray5858&cat=socioabs',1,'N',NULL,NULL,NULL,NULL),(23,1,NULL,'http://www.isiknowledge.com/WOS',1,'N',NULL,NULL,NULL,NULL),(24,1,NULL,'http://www.library.miami.edu/search/eresources/infosheet.php?service_id=578',1,'N',NULL,NULL,NULL,NULL),(25,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://search.ebscohost.com/login.aspx?authtype=ip,uid&profile=ehost&defaultdb=aph',1,'N',NULL,NULL,NULL,NULL),(26,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.proquest.com/pqdauto?COPT=U01EPTQmSU5UPTAmREJTPUc2',1,'N',NULL,NULL,NULL,NULL),(27,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://infotrac.galegroup.com/itweb/miami_richter?db=AONE',1,'N',NULL,NULL,NULL,NULL),(28,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://search.ebscohost.com/login.aspx?',1,'N',NULL,NULL,NULL,NULL),(29,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.lexisnexis.com/hottopics/lnacademic/',1,'N',NULL,NULL,NULL,NULL),(30,1,NULL,'http://www.etana.org/abzu/',1,'N',NULL,NULL,NULL,NULL),(31,1,NULL,'http://www.allacademic.com/meta/p_mla_apa_research_citation/1/1/1/1/6/p111166_index.html',1,'N',NULL,NULL,NULL,NULL),(32,1,NULL,'http://faculty.washington.edu/snoegel/okeanos9.html',1,'N',NULL,NULL,NULL,NULL),(33,1,NULL,'http://www.columbia.edu/cu/lweb/indiv/mideast/cuvlm/',1,'N',NULL,NULL,NULL,NULL),(34,1,NULL,'http://www.islamfortoday.com/historyusa4.htm',1,'N',NULL,NULL,NULL,NULL),(35,1,NULL,'http://www.muslimphilosophy.com/',1,'N',NULL,NULL,NULL,NULL),(36,1,NULL,'http://www.mcgill.ca/hssl/collections/links/subject/mideast/',1,'N',NULL,NULL,NULL,NULL),(37,1,NULL,'http://etext.virginia.edu/koran.html',1,'N',NULL,NULL,NULL,NULL),(38,1,NULL,'http://www.unc.edu/depts/sufilit/archive.htm',1,'N',NULL,NULL,NULL,NULL),(39,1,NULL,'http://www.library.ucsb.edu/subjects/mideast/neareast.html',1,'N',NULL,NULL,NULL,NULL),(40,1,NULL,'http://www.uga.edu/islam/',1,'N',NULL,NULL,NULL,NULL),(41,1,NULL,'http://www.wabashcenter.wabash.edu/resources/result_browse.aspx?topic=573&pid=361',1,'N',NULL,NULL,NULL,NULL),(42,1,NULL,'http://www.library.yale.edu/rsc/religion/islam.html',1,'N',NULL,NULL,NULL,NULL),(43,1,NULL,'http://www.unc.edu/~cernst/resources.htm',1,'N',NULL,NULL,NULL,NULL),(44,1,NULL,'http://www.fordham.edu/halsall/islam/islamsbook.html',1,'N',NULL,NULL,NULL,NULL),(45,1,NULL,'http://www.msawest.net/islam/',1,'N',NULL,NULL,NULL,NULL),(46,1,NULL,'http://www.al-islam.org/',1,'N',NULL,NULL,NULL,NULL),(47,1,NULL,'http://www.princeton.edu/~humcomp/alkhaz.html',1,'N',NULL,NULL,NULL,NULL),(48,1,NULL,'http://ibisweb.miami.edu/record=b1037333',1,'N',NULL,NULL,NULL,NULL),(49,1,NULL,'http://www.brillonline.nl/subscriber/uid=3331/title_home?title_id=ei3_ei3',1,'N',NULL,NULL,NULL,NULL),(50,1,NULL,'http://www.al-islam.org/encyclopedia/',1,'N',NULL,NULL,NULL,NULL),(51,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.columbiagazetteer.org/',1,'N',NULL,NULL,NULL,NULL),(52,1,NULL,'http://www.unc.edu/awmc/index.html',1,'N',NULL,NULL,NULL,NULL),(53,1,NULL,'http://www.lib.utexas.edu/Libs/PCL/Map_collection/Map_collection.html',1,'N',NULL,NULL,NULL,NULL),(54,1,NULL,'http://ibisweb.miami.edu/record=b1034209~S11',1,'N',NULL,NULL,NULL,NULL),(55,1,NULL,'http://ibisweb.miami.edu/record=b3112607~S11',1,'N',NULL,NULL,NULL,NULL),(56,1,NULL,'http://www.library.miami.edu/search/eresources/infosheet.php?service_id=455',1,'N',NULL,NULL,NULL,NULL),(57,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://ica.princeton.edu',1,'N',NULL,NULL,NULL,NULL),(58,1,NULL,'http://www.metmuseum.org/toah/splash.htm',1,'N',NULL,NULL,NULL,NULL),(59,1,NULL,'http://www.greatbuildings.com/',1,'N',NULL,NULL,NULL,NULL),(60,1,NULL,'http://ibisweb.miami.edu/record=b2946804~S11',1,'N',NULL,NULL,NULL,NULL),(61,1,NULL,'http://ibisweb.miami.edu/record=b2065497~S11',1,'N',NULL,NULL,NULL,NULL),(62,1,NULL,'http://ibisweb.miami.edu/record=b2144808~S11',1,'N',NULL,NULL,NULL,NULL),(63,1,NULL,'http://digicoll.library.wisc.edu/Arts/subcollections/CasselmanImageAbout.html',1,'N',NULL,NULL,NULL,NULL),(64,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://infotrac.galegroup.com/itweb/miami_richter?db=BGMI',1,'N',NULL,NULL,NULL,NULL),(65,1,NULL,'http://www.online-literature.com/poe/',1,'N',NULL,NULL,NULL,NULL),(66,1,NULL,'http://www2.lv.psu.edu/PSA/',1,'N',NULL,NULL,NULL,NULL),(67,1,NULL,'http://www.poemuseum.org/',1,'N',NULL,NULL,NULL,NULL),(68,1,NULL,'http://www.nps.gov/edal/index.htm',1,'N',NULL,NULL,NULL,NULL),(69,1,NULL,'http://www.eapoe.org/',1,'N',NULL,NULL,NULL,NULL),(70,1,NULL,'http://www.bronxhistoricalsociety.org/poecottage.html',1,'N',NULL,NULL,NULL,NULL),(71,1,NULL,'http://knowingpoe.thinkport.org/default_flash.asp',1,'N',NULL,NULL,NULL,NULL),(72,1,NULL,'http://norman.hrc.utexas.edu/poeDC/',1,'N',NULL,NULL,NULL,NULL),(73,1,NULL,'http://www.nypl.org/archives/1792',1,'N',NULL,NULL,NULL,NULL),(74,1,NULL,'http://www.prattlibrary.org/research/digitalcollections.aspx?id=180',1,'N',NULL,NULL,NULL,NULL),(75,1,NULL,'http://etext.lib.virginia.edu/services/courses/rbs//99/rbspoe99.html',1,'N',NULL,NULL,NULL,NULL),(76,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://www.jstor.org/',1,'N',NULL,NULL,NULL,NULL),(77,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://newfirstsearch.oclc.org/dbname=MLA;done=referer;FSIP',1,'N',NULL,NULL,NULL,NULL),(78,1,NULL,'http://www.hathitrust.org/',1,'N',NULL,NULL,NULL,NULL),(79,1,NULL,'https://iiiprxy.library.miami.edu/login?url=http://go.galegroup.com/ps/start.do?p=LitRC&u=miami_richter',1,'N',NULL,NULL,NULL,NULL),(80,1,NULL,'http://www.imdb.com/name/nm0000590/',1,'N',NULL,NULL,NULL,NULL),(81,1,NULL,'http://www.imdb.com/character/ch0028694/',1,'N',NULL,NULL,NULL,NULL),(82,1,NULL,'http://www.biography.com/people/edgar-allan-poe-9443160',1,'N',NULL,NULL,NULL,NULL),(83,1,NULL,'http://www.theedgars.com/',1,'N',NULL,NULL,NULL,NULL),(84,1,NULL,'http://www.fantasticfiction.co.uk/p/edgar-allan-poe/',1,'N',NULL,NULL,NULL,NULL),(85,1,NULL,'http://ibisweb.miami.edu/record=b3069969',1,'N',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location_title`
--

DROP TABLE IF EXISTS `location_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location_title` (
  `location_id` bigint(20) NOT NULL DEFAULT '0',
  `title_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`location_id`,`title_id`),
  KEY `fk_lt_location_id_idx` (`location_id`),
  KEY `fk_lt_title_id_idx` (`title_id`),
  CONSTRAINT `fk_lt_location_id` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lt_title_id` FOREIGN KEY (`title_id`) REFERENCES `title` (`title_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location_title`
--

LOCK TABLES `location_title` WRITE;
/*!40000 ALTER TABLE `location_title` DISABLE KEYS */;
INSERT INTO `location_title` VALUES (1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10),(11,11),(12,12),(13,13),(14,14),(15,15),(16,16),(17,17),(18,18),(19,19),(20,20),(21,21),(22,22),(23,23),(24,24),(25,25),(26,26),(27,27),(28,28),(29,29),(30,30),(31,31),(32,32),(33,33),(34,34),(35,35),(36,36),(37,38),(38,40),(39,41),(40,42),(41,43),(42,44),(43,45),(44,46),(45,47),(46,48),(47,49),(48,51),(49,52),(50,54),(51,55),(52,56),(53,57),(54,58),(55,59),(56,60),(57,61),(58,62),(59,63),(60,64),(61,66),(62,67),(63,68),(64,69),(65,71),(66,72),(67,73),(68,74),(69,75),(70,76),(71,77),(72,78),(73,79),(74,80),(75,81),(76,82),(77,83),(78,84),(79,85),(80,86),(81,87),(82,88),(83,90),(84,91),(85,92);
/*!40000 ALTER TABLE `location_title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pluslet`
--

DROP TABLE IF EXISTS `pluslet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pluslet` (
  `pluslet_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `body` longtext NOT NULL,
  `local_file` varchar(100) DEFAULT NULL,
  `clone` int(1) NOT NULL DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  `extra` varchar(255) DEFAULT NULL,
  `hide_titlebar` int(1) NOT NULL DEFAULT '0',
  `collapse_body` int(1) NOT NULL DEFAULT '0',
  `titlebar_styling` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pluslet_id`),
  KEY `INDEXSEARCHpluslet` (`body`(200))
) ENGINE=InnoDB AUTO_INCREMENT=8953564 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pluslet`
--

LOCK TABLES `pluslet` WRITE;
/*!40000 ALTER TABLE `pluslet` DISABLE KEYS */;
INSERT INTO `pluslet` VALUES (1,'All Items by Source','','',0,'Special','',0,0,NULL),(2,'Key to Icons','','',0,'Special','',0,0,NULL),(3,'Subject Specialist','','',0,'Special','',0,0,NULL),(4,'FAQs','','',0,'Special','',0,0,NULL),(5,'Books:  Use the Library Catalog','','',0,'Special','',0,0,NULL),(6,'','','',0,'Reserved_for_Special','',0,0,NULL),(7,'','','',0,'Reserved_for_Special','',0,0,NULL),(8,'','','',0,'Reserved_for_Special','',0,0,NULL),(9,'','','',0,'Reserved_for_Special','',0,0,NULL),(10,'','','',0,'Reserved_for_Special','',0,0,NULL),(11,'','','',0,'Reserved_for_Special','',0,0,NULL),(12,'','','',0,'Reserved_for_Special','',0,0,NULL),(13,'','','',0,'Reserved_for_Special','',0,0,NULL),(14,'','','',0,'Reserved_for_Special','',0,0,NULL),(15,'','','',0,'Reserved_for_Special','',0,0,NULL),(16,'test','test',NULL,0,'Basic','',0,0,''),(182478,'Popular Websites','<div class=\"links\">{{dab},{65},{  Literature Network - Edgar Allan Poe},{01}}<div class=\"link-description\">Full text to many of Poe\'s writings.</div></div><div class=\"links\">{{dab},{66},{Poe Studies Association},{01}}<div class=\"link-description\">\"The Poe Studies Association... provides a forum for the scholarly and informal exchange of information on Edgar Allan Poe, his life, and works.\" - Poe Studies Association</div></div><div class=\"links\">{{dab},{67},{Poe Museum},{01}}<div class=\"link-description\">\"The Poe Museum provides a retreat into early 19th century Richmond where Edgar Allan Poe lived and worked.\" - Poe Museum</div></div><div class=\"links\">{{dab},{68},{Edgar Allan Poe National Historic Site},{01}}<div class=\"link-description\">Poe\'s Pennsylvania home</div></div><div class=\"links\">{{dab},{69},{Edgar Allan Poe society of Baltimore},{01}}<div class=\"link-description\">\"Since 1977, the Poe Society has returned its efforts to focus on the annual commemorative lecture and associated publications.\"</div></div><div class=\"links\">{{dab},{70},{Bronx Historical Society},{01}}<div class=\"link-description\">Proprietors of one of Poe\'s residence\'s and short documentary.</div></div><div class=\"links\">{{dab},{71},{Knowing Poe},{01}}<div class=\"link-description\">Made for classroom setting, but containing a lot of useful information and links for all.</div></div><div class=\"media\"></div>',NULL,0,'Basic',NULL,0,0,NULL),(182849,'Biography, Criticisms and Encyclopedias','<div class=\"books\"><a href=\"http://ibisweb.miami.edu/record=b4563541~S11\">Edgar Allan Poe: beyond gothicism</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://ibisweb.miami.edu/record=b1827617~S11\">Edgar Allan Poe : mournful and never-ending remembrance</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://ibisweb.miami.edu/record=b2802331~S11\">Edgar Allan Poe A to Z</a><div class=\"book-description\"></div></div><div class=\"media\"></div>',NULL,0,'Basic',NULL,0,0,NULL),(182908,'Videos','<div class=\"books\"><a href=\"http://ibisweb.miami.edu/record=b3148943~S5\">The Fall of the House of Usher</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b3943114~S5\">Edgar Allan Poe : terror of the soul</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b3378072~S5\">Spirits of the dead</a><div class=\"book-description\"></div></div><div class=\"media\"><p>To browse a list of videos pertaining to Poe in the library holdings search the <a href=\"http://new.library.miami.edu/media/\">CD/DVDs</a> tab on our library website. </p></div>',NULL,0,'Basic',NULL,0,0,NULL),(183171,'Websites','<div class=\"links\">{{dab},{65},{  Literature Network - Edgar Allan Poe},{01}}<div class=\"link-description\">Full text to many of Poe\'s writings.</div></div><div class=\"links\">{{dab},{84},{Fantastic Fiction},{01}}<div class=\"link-description\">Edgar Allan Poe bibliography.  Also contains list of anthologies containing Edgar Allan Poe\'s stories.</div></div><div class=\"links\">{{dab},{85},{Graham\'s Magazine},{01}}<div class=\"link-description\">This archive contains Poe\'s articles from the journal.  When searching remember that he went by \"Edgar A. Poe\" in life.</div></div><div class=\"links\">{{dab},{75},{Letters at University of Virginia},{01}}<div class=\"link-description\">This virtual collection unites letters physically held in the University of Virginia Library Special Collections Department, Charlottesville, Va; the Poe Museum, Richmond, Va.; and the Valentine Museum, Richmond, Va.</div></div><div class=\"media\"></div>',NULL,0,'Basic',NULL,0,0,NULL),(183173,'Anthologies','<div class=\"books\"><a href=\"http://ibisweb.miami.edu/record=b3578092\">The portable Edgar Allan Poe</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://ibisweb.miami.edu/record=b1012672\">Edgar Allan Poe : Essays and Reviews</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://ibisweb.miami.edu/record=b3301687\">Edgar Allan Poe Letters Till Now Unpublished - In The Valentine Museum, Richmond Virginia</a><div class=\"book-description\"></div></div><div class=\"media\"></div>',NULL,0,'Basic',NULL,0,0,NULL),(183246,'Welcome','<div class=\"description\">&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/REC-html40/loose.dtd&quot;&gt;\n&lt;html&gt;&lt;body&gt;&lt;p&gt;Welcome to the &lt;strong&gt;Edgar Allan Poe&lt;/strong&gt; LibGuide. Complied within are resources to help research both the man and his writings. &amp;nbsp;&lt;/p&gt;&lt;script src=&quot;http://miami.summon.serialssolutions.com/widgets/box.js&quot; type=&quot;text/javascript&quot; id=&quot;sd2d85920bbea0130f60e1cc1de70d4bc&quot;&gt;&lt;/script&gt;&lt;script type=&quot;text/javascript&quot;&gt;// &lt;![CDATA[new SummonCustomSearchBox({&quot;id&quot;:&quot;#sd2d85920bbea0130f60e1cc1de70d4bc&quot;,&quot;params&quot;:{&quot;s.fvf[]&quot;:[&quot;Discipline,drama&quot;,&quot;Discipline,education&quot;,&quot;Discipline,film&quot;,&quot;Discipline,history &amp; archaeology&quot;,&quot;Discipline,journalism &amp; communications&quot;,&quot;Discipline,languages &amp; literatures&quot;,&quot;Discipline,philosophy&quot;,&quot;Discipline,psychology&quot;,&quot;Discipline,social sciences&quot;,&quot;Discipline,sociology &amp; social history&quot;,&quot;Discipline,visual arts&quot;,&quot;Discipline,women\'s studies&quot;],&quot;keep_r&quot;:true},&quot;colors&quot;:{&quot;tagline&quot;:&quot;#171212&quot;,&quot;links&quot;:&quot;#5b19d2&quot;},&quot;tagline_text&quot;:&quot;Edgar Allan Poe&quot;,&quot;searchbutton_text&quot;:&quot;Search for Poe materials&quot;,&quot;advanced_text&quot;:&quot;More search options&quot;,&quot;advanced&quot;:&quot;true&quot;,&quot;suggest&quot;:&quot;true&quot;,&quot;tagline&quot;:&quot;true&quot;,&quot;popup&quot;:&quot;true&quot;})// ]]&gt;&lt;/script&gt;&lt;p&gt;&lt;/p&gt;&lt;table style=&quot;width: 488px; height: 182px;&quot; border=&quot;0&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td style=&quot;width: 15px;&quot; align=&quot;center&quot;&gt;&lt;p&gt;&lt;img src=&quot;http://localhost/SubjectsPlus/assets//images/82/poe_portrait.jpg&quot; align=&quot;left&quot; height=&quot;146&quot; width=&quot;110&quot;&gt;&lt;/p&gt;&lt;/td&gt;&lt;td&gt;&lt;p&gt;&lt;strong&gt;Edgar Allan Poe &lt;/strong&gt;(born Edgar Poe, January 19, 1809&amp;nbsp;&amp;ndash; October 7, 1849) was an American author, poet, editor and literary critic, considered part of the American Romantic Movement. Best known for his tales of &lt;span class=&quot;mw-redirect&quot;&gt;mystery&lt;/span&gt; and the macabre, Poe was one of the earliest American practitioners of the short story and is considered the inventor of the detective fiction genre. He is further credited with contributing to the emerging genre of science fiction.&lt;sup id=&quot;cite_ref-0&quot; class=&quot;reference&quot;&gt;&lt;a href=&quot;http://en.wikipedia.org/wiki/Edgar_Allan_Poe#cite_note-0&quot;&gt;&lt;span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt;&lt;/a&gt;&lt;/sup&gt; He was the first well-known American writer to try to earn a living through writing alone, resulting in a financially difficult life and career.&quot; - Wikipedia&lt;/p&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;/body&gt;&lt;/html&gt;\n</div><div class=\"media\"><p>Welcome to the <strong>Edgar Allan Poe</strong> LibGuide. Complied within are resources to help research both the man and his writings.  </p><script src=\"http://miami.summon.serialssolutions.com/widgets/box.js\" type=\"text/javascript\" id=\"sd2d85920bbea0130f60e1cc1de70d4bc\"></script><script type=\"text/javascript\">// <![CDATA[new SummonCustomSearchBox({\"id\":\"#sd2d85920bbea0130f60e1cc1de70d4bc\",\"params\":{\"s.fvf[]\":[\"Discipline,drama\",\"Discipline,education\",\"Discipline,film\",\"Discipline,history & archaeology\",\"Discipline,journalism & communications\",\"Discipline,languages & literatures\",\"Discipline,philosophy\",\"Discipline,psychology\",\"Discipline,social sciences\",\"Discipline,sociology & social history\",\"Discipline,visual arts\",\"Discipline,women\'s studies\"],\"keep_r\":true},\"colors\":{\"tagline\":\"#171212\",\"links\":\"#5b19d2\"},\"tagline_text\":\"Edgar Allan Poe\",\"searchbutton_text\":\"Search for Poe materials\",\"advanced_text\":\"More search options\",\"advanced\":\"true\",\"suggest\":\"true\",\"tagline\":\"true\",\"popup\":\"true\"})// ]]></script><p></p><table style=\"width: 488px; height: 182px;\" border=\"0\"><tbody><tr><td style=\"width: 15px;\" align=\"center\"><p><img src=\"http://www.poestories.com/images/poe_portrait.jpg\" align=\"left\" height=\"146\" width=\"110\" /></p></td><td><p><strong>Edgar Allan Poe </strong>(born Edgar Poe, January 19, 1809 &ndash; October 7, 1849) was an American author, poet, editor and literary critic, considered part of the American Romantic Movement. Best known for his tales of <span class=\"mw-redirect\">mystery</span> and the macabre, Poe was one of the earliest American practitioners of the short story and is considered the inventor of the detective fiction genre. He is further credited with contributing to the emerging genre of science fiction.<sup id=\"cite_ref-0\" class=\"reference\"><a href=\"http://en.wikipedia.org/wiki/Edgar_Allan_Poe#cite_note-0\"><span></span><span></span></a></sup> He was the first well-known American writer to try to earn a living through writing alone, resulting in a financially difficult life and career.\" - Wikipedia</p></td></tr></tbody></table></div>',NULL,0,'Basic',NULL,0,0,NULL),(1364502,'Quick Links','<div class=\"links\">{{dab},{2},{New Acquisitions in Religion},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{3},{Department of Religious Studies},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{4},{IBISWEB Home Page},{01}}<div class=\"link-description\"></div></div>',NULL,0,'Basic',NULL,0,0,NULL),(1364503,'Description of the Collection in Islamic Studies','<div class=\"description\">&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/REC-html40/loose.dtd&quot;&gt;\n&lt;html&gt;&lt;body&gt;&lt;p&gt;The purpose of the collection is to support undergraduate instructional needs and the research requirements of the faculty in the area of&amp;nbsp;Islam, including historical, religious, artistic and contemporary social issues.&amp;nbsp; English dominates,&amp;nbsp;and all time periods are covered.&amp;nbsp; Special geographical coverage is given to the rise of Islam in the ancient Near East, as well as to&amp;nbsp;its practice&amp;nbsp;in the USA and the West.&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;img width=&quot;357&quot; src=&quot;http://localhost/SubjectsPlus/assets//images/132/topkapi_palace_-_harem.jpg&quot; height=&quot;417&quot;&gt;&lt;/p&gt;&lt;p&gt;Harem interior, 16th c., Topkapi Palace Museum, Istanbul.&amp;nbsp; (ARTstor)&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;/body&gt;&lt;/html&gt;\n</div>',NULL,0,'Basic',NULL,0,0,NULL),(1364505,'Core Resources','<div class=\"links\">{{dab},{5},{ATLA Religion Database with ATLASerials},{01}}<div class=\"link-description\">A collection of major religious and theology journals by religion scholars in the U.S.<br/></div></div><div class=\"links\">{{dab},{6},{Brill Dictionary of Religion},{01}}<div class=\"link-description\">Online version of the 2006 print edition.<br/></div></div><div class=\"links\">{{dab},{7},{Oxford Islamic Studies Online},{01}}<div class=\"link-description\">Full text access to The Oxford Encyclopedia of the Modern Islamic World, The Oxford History of Islam, The Oxford Dictionary of Islam, two classic interpretations of the Qur\'an, and more.</div></div><div class=\"links\">{{dab},{8},{Encyclopedia of the Qur\'an},{01}}<div class=\"link-description\">An encyclopaedic dictionary of qurʾānic terms, concepts, personalities, place names, cultural history and exegesis extended with essays on themes and subjects within qurʾānic studies.</div></div><div class=\"links\">{{dab},{9},{Encyclopedia of Islam, THREE},{01}}<div class=\"link-description\">Third editionof the Encyclopedia of Islam, vastly expanded and updated, but in process. Portions are added continuously to the database as they become available.</div></div><div class=\"links\">{{dab},{10},{Encyclopedia of Islam},{01}}<div class=\"link-description\">Online edition covering all aspects of Islam in the broadest range of historical and geographic subjects.</div></div><div class=\"links\">{{dab},{11},{Encyclopedia of Women & Islamic Cultures},{01}}<div class=\"link-description\">A survey all facets of life of women in Muslim societies. <br/></div></div><div class=\"links\">{{dab},{12},{Index Islamicus},{01}}<div class=\"link-description\">This database indexes literature on Islam, the Middle East and the Muslim world. It is produced by the Islamic Bibliography Unit at Cambridge University Library.</div></div><div class=\"links\">{{dab},{13},{Bibliography of Asian Studies},{01}}<div class=\"link-description\">An index to predominantly western-language articles and book chapters on all parts of Asia published since 1971.</div></div><div class=\"links\">{{dab},{14},{Oxford Reference Online:  Religion & Philosophy},{01}}<div class=\"link-description\">Part of the Oxford Reference Online series that contain about 100 general and subject dictionaries, and language reference works published by Oxford University Press. The collection is fully-indexed and cross-searchable.<br/><br/></div></div><div class=\"links\">{{dab},{15},{Encyclopaedia Iranica},{01}}<div class=\"link-description\">A multi-disciplinary reference work and research tool designed to record the facts of Iranian history and civilization.<br/></div></div>',NULL,0,'Basic',NULL,0,0,NULL),(1364506,'Supporting Databases','<div class=\"links\">{{dab},{16},{FRANCIS},{01}}<div class=\"link-description\">Indexes over 4000 journals in the disciplines Psychology, Arts and Literature, Philosophy, and Religion.</div></div><div class=\"links\">{{dab},{17},{Iter:  Gateway to the Middle Ages and the Renaissance},{01}}<div class=\"link-description\">Iter features 2 scholarly bibliographic databases, one featuring journal articles, and the other, books.  Period covered is 400-1700.<br/></div></div><div class=\"links\">{{dab},{18},{JSTOR Electronic Press},{01}}<div class=\"link-description\">A non-profit, independent organization that converts and maintains full-text digital versions of the backfiles of major scholarly journals.<br/></div></div><div class=\"links\">{{dab},{19},{Bibliography of Asian Studies},{01}}<div class=\"link-description\">Online version of the Bibliography of Asian Studies (BAS), referencing principally western-language articles and book chapters on all parts of Asia published since 1971.<br/></div></div><div class=\"links\">{{dab},{20},{Anthropology Plus},{01}}<div class=\"link-description\">Covers anthropology, archaeology, and interdisciplinary studies.  Includes Anthropological Index: 1957-present & Anthropological Literature: Late 19th century to present. <br/></div></div><div class=\"links\">{{dab},{21},{AnthroSource},{01}}<div class=\"link-description\">Provides access to the peer reviewed journals, newsletters and bulletins of the American Anthropological Association (AAA).</div></div><div class=\"links\">{{dab},{22},{Sociological Abstracts},{01}}<div class=\"link-description\">It indexes and abstracts research literature published worldwide in journals and other serial publications.</div></div><div class=\"links\">{{dab},{23},{Social Science Citation Index},{01}}<div class=\"link-description\">Provides international, cover-to-cover indexing of the most cited journals in social sciences. The databases is searched via the ISI WEB OF SCIENCE Citation Databases service.<br/></div></div><div class=\"links\">{{dab},{24},{International Medieval Bibliography},{01}}<div class=\"link-description\">Includes International Medieval Bibliography, Bibliographie de Civilisation Médiévale, and International Directory of Medievalists.</div></div><div class=\"links\">{{dab},{25},{Academic Search Premier},{01}}<div class=\"link-description\">A multi-disciplinary full text database containing full text for nearly 4,500 journals, including more than 3,600 peer-reviewed titles.<br/></div></div><div class=\"links\">{{dab},{26},{ProQuest Research Library},{01}}<div class=\"link-description\">An interdisciplinary general reference database containing over 2,500 periodicals--nearly 1,000 of them in ASCII full-text or full-image formats.<br/></div></div><div class=\"links\">{{dab},{27},{Academic OneFile},{01}}<div class=\"link-description\">A source for peer-reviewed, full-text articles from journals and reference sources. Offers extensive coverage of the physical sciences, technology, medicine, social sciences, the arts, theology, literature and other subjects.</div></div><div class=\"links\">{{dab},{28},{Historical Abstracts},{01}}<div class=\"link-description\">Indexes and abstracts journal articles and dissertations on world history from 1450 to present excluding the United States and Canada.<br/></div></div><div class=\"links\">{{dab},{29},{Lexis Nexis Academic},{01}}<div class=\"link-description\"></div></div>',NULL,0,'Basic',NULL,0,0,NULL),(1364507,'Mosque, Isfahan, Iran, c. 1500 (ARTstor)','<div class=\"description\">&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/REC-html40/loose.dtd&quot;&gt;\n&lt;html&gt;&lt;body&gt;&lt;p&gt;&lt;img width=&quot;209&quot; src=&quot;http://localhost/SubjectsPlus/assets//images/132/AMICO_CL_103800788.jpg&quot; height=&quot;298&quot;&gt;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;/body&gt;&lt;/html&gt;\n</div>',NULL,0,'Basic',NULL,0,0,NULL),(1364508,'Web Resources','<div class=\"links\">{{dab},{30},{Abzu},{01}}<div class=\"link-description\">A guide to the study of the Ancient Near East and Ancient Mediterranean via the Internet.</div></div><div class=\"links\">{{dab},{31},{American Religion Data Archive (ARDA)},{01}}<div class=\"link-description\">Includes data on churches and church membership, religious professionals, and religious groups.</div></div><div class=\"links\">{{dab},{32},{Okeanos:  Biblical, Classical, and Ancient Near Eastern Studies},{01}}<div class=\"link-description\">An interdisciplinary resource for the study of the Ancient, Biblical, Classical, and Late Antique Near East.<br/></div></div><div class=\"links\">{{dab},{33},{Columbia University Middle East Studies Internet Resource Guide},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{34},{Islam in the United States},{01}}<div class=\"link-description\">Official State Deparatment Fact Sheet.</div></div><div class=\"links\">{{dab},{35},{Islamic Philosophy Online},{01}}<div class=\"link-description\">A site dedicated to the academic study of the philosophical output of the Muslim World.</div></div><div class=\"links\">{{dab},{36},{McGill University Middle East Subject Guide},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{37},{  Qur\'an},{01}}<div class=\"link-description\">A complete, searchable English text made available by the University of Virginia.</div></div><div class=\"links\">{{dab},{38},{  Sufi Literature Archive},{01}}<div class=\"link-description\">Maintained by the University of North Carolina at Chapel Hill.</div></div><div class=\"links\">{{dab},{39},{USCB Library Internet Resources for Middle East and Islamic Studies},{01}}<div class=\"link-description\">Maintained by the University of California at Santa Barbara.</div></div><div class=\"links\">{{dab},{40},{University of Georgia Research Guide in Islam},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{41},{Wabash Center Internet Guide to Religion:  Islam},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{42},{Yale University Research Guide for Religion:  Islam},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{43},{Carl Ernst, Resources for Islamic Studies},{01}}<div class=\"link-description\">Carl W. Ernst is a specialist in Islamic studies, with a focus on West and South Asia. His published research, based on the study of Arabic, Persian, and Urdu, has been mainly devoted to the study of Islam and Sufism.</div></div><div class=\"links\">{{dab},{44},{Internet Islamic History Sourcebook},{01}}<div class=\"link-description\">A collection of public domain and copy-permitted historical texts, edited by Paul Halsall at Fordham University.</div></div><div class=\"links\">{{dab},{45},{Compendium of Mulsim Texts},{01}}<div class=\"link-description\">Prepared by the University of Southern California Muslim Students Association.</div></div><div class=\"links\">{{dab},{46},{Al-Islam.org},{01}}<div class=\"link-description\">The Ahlul Bayt Digital Islamic Library Project, U.K., and Qom, Iran.  It is a very good Shi\'i website with translations of important religious texts.</div></div><div class=\"links\">{{dab},{47},{Al-Khazina},{01}}<div class=\"link-description\">A comprehensive website with searchable Qur\'ans, virtual hajj, Sufism, maps, and biographies of scholars.</div></div>',NULL,0,'Basic',NULL,0,0,NULL),(1364510,'Dictionaries & Encyclopedias','<div class=\"links\">{{dab},{15},{Encyclopaedia Iranica},{01}}<div class=\"link-description\">A multi-disciplinary reference work and research tool designed to record the facts of Iranian history and civilization.<br/></div></div><div class=\"links\">{{dab},{14},{Oxford Reference Online:  Religion & Philosophy},{01}}<div class=\"link-description\">Part of the Oxford Reference Online series that contain about 100 general and subject dictionaries, and language reference works published by Oxford University Press. The collection is fully-indexed and cross-searchable.<br/></div></div><div class=\"links\">{{dab},{6},{Brill Dictionary of Religion},{01}}<div class=\"link-description\">Online version of the 2006 print edition.<br/></div></div><div class=\"links\">{{dab},{8},{Encyclopedia of the Qur\'an},{01}}<div class=\"link-description\">An encyclopaedic dictionary of qurʾānic terms, concepts, personalities, place names, cultural history and exegesis extended with essays on themes and subjects within qurʾānic studies.<br/></div></div><div class=\"links\">{{dab},{10},{Encyclopedia of Islam},{01}}<div class=\"link-description\">Online edition covering all aspects of Islam in the broadest range of historical and geographic subjects.<br/></div></div><div class=\"links\">{{dab},{11},{Encyclopedia of Women & Islamic Cultures},{01}}<div class=\"link-description\">A survey all facets of life of women in Muslim societies. <br/><br/></div></div><div class=\"links\">{{dab},{48},{  Encyclopedia of Religion},{01}}<div class=\"link-description\">The standard, scholarly encyclopedia in the general field of religion.</div></div><div class=\"links\">{{dab},{49},{Encyclopedia of Islam, THREE},{01}}<div class=\"link-description\">Third editionof the Encyclopedia of Islam, vastly expanded and updated, but in process.  Portions are added continuously to the database as they become available.</div></div><div class=\"links\">{{dab},{50},{  Shi\'ite Encyclopedia},{01}}<div class=\"link-description\">A collection of information which addresses Shia/Sunni inter-school related issues.</div></div>',NULL,0,'Basic',NULL,0,0,NULL),(1364512,'Primary Texts','<div class=\"links\">{{dab},{37},{  Qur\'an},{01}}<div class=\"link-description\">Maintained by the Electronic Text Center at the University of Virginia.</div></div>',NULL,0,'Basic',NULL,0,0,NULL),(1364513,'Maps & Atlases','<div class=\"description\">&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/REC-html40/loose.dtd&quot;&gt;\n&lt;html&gt;&lt;body&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;img width=&quot;619&quot; src=&quot;http://localhost/SubjectsPlus/assets//images/132/map_of_islam.jpg&quot; height=&quot;339&quot;&gt;&lt;/p&gt;&lt;p&gt;The Great Division of Islam&lt;/p&gt;&lt;/body&gt;&lt;/html&gt;\n</div><div class=\"links\">{{dab},{51},{Columbia Gazetteer of the World Online},{01}}<div class=\"link-description\">A full-text database with detailed descriptions and statistics on thousands of countries, cities, mountains, rivers, and every other kind of political area or geographic feature.<br/></div></div><div class=\"links\">{{dab},{52},{AWMP (Ancient World Mapping Center)},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{53},{Perry-Castaneda Library Map Collection},{01}}<div class=\"link-description\">A University of Texas Web site, it provides a well-organized collection of historical and current maps worldwide. All maps available on this server are in the public domain, and may be freely downloaded and copied.<br/><br/></div></div><div class=\"links\">{{dab},{54},{Cultural Atlas of Islam},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{55},{Historical Atlas of Islam},{01}}<div class=\"link-description\"></div></div>',NULL,0,'Basic',NULL,0,0,NULL),(1364515,'Art and Architecture','<div class=\"description\">&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/REC-html40/loose.dtd&quot;&gt;\n&lt;html&gt;&lt;body&gt;&lt;p&gt;&lt;img width=&quot;598&quot; src=&quot;http://localhost/SubjectsPlus/assets//images/132/3858643145_8afa0f51d8.jpg&quot; height=&quot;375&quot;&gt;&lt;/p&gt;&lt;p&gt;Domes of the Blue Mosque, Isbanbul (by Harry Kikstra)&lt;/p&gt;&lt;/body&gt;&lt;/html&gt;\n</div><div class=\"links\">{{dab},{56},{ARTstor},{01}}<div class=\"link-description\">A database of digital images and accompanying scholarly information for use in art history funded by the Andrew Mellon Foundation.<br/></div></div><div class=\"links\">{{dab},{57},{Index of Christian Art},{01}}<div class=\"link-description\">A database of medieval art with full-text records for nearly 80,000 works of art dating from early apostolic times to A.D. 1550, including some Islamic art.<br/></div></div><div class=\"links\">{{dab},{58},{Timeline of Art History  - The Metropolitan Museum of Art},{01}}<div class=\"link-description\">A chronological, geographical, and thematic exploration of the history of art from around the world, as illustrated especially by the Metropolitan Museum of Art\'s collection.  Select a time period, scroll to bottom and select Islamic Art.<br/></div></div><div class=\"links\">{{dab},{59},{Great Buildings},{01}}<div class=\"link-description\">The leading architecture reference site on the web.  Can search by Buildings, Architectural Types (Islamic), or Places (select a country), or do a Quick Search ( \"mosque\").</div></div><div class=\"links\">{{dab},{60},{Islamic Art and Architecture, 650-1250},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{61},{  Art and Architecture of Islam, 1250-1800},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{62},{Dictionary of Islamic Architecture},{01}}<div class=\"link-description\"></div></div><div class=\"links\">{{dab},{63},{Casselman Archive of Islamic and Mudejar Architecture in Spain},{01}}<div class=\"link-description\"></div></div>',NULL,0,'Basic',NULL,0,0,NULL),(1364517,'Biographies','<div class=\"description\">&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/REC-html40/loose.dtd&quot;&gt;\n&lt;html&gt;&lt;body&gt;&lt;p&gt;&lt;img src=&quot;http://localhost/SubjectsPlus/assets//images/132/portraits.jpg&quot;&gt;&lt;/p&gt;&lt;p&gt;Portraits of 3 Ottoman Turkish Rulers from a 17th C. Manuscript (ARTstor)&lt;/p&gt;&lt;/body&gt;&lt;/html&gt;\n</div><div class=\"links\">{{dab},{64},{Biography and Genealogy Master Index},{01}}<div class=\"link-description\">It indexes current,readily available reference sources, as well as retrospective works that cover individuals,both living and deceased,from every field.<br/></div></div>',NULL,0,'Basic',NULL,0,0,NULL),(1639789,'Equestrian Portrait of Sultan Selim II, c. 1570, Isbanbul  (ARTstor)','<div class=\"description\">&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/REC-html40/loose.dtd&quot;&gt;\n&lt;html&gt;&lt;body&gt;&lt;p&gt;&lt;img src=&quot;http://localhost/SubjectsPlus/assets//images/132/equestrian_portrait.jpg&quot;&gt;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;/body&gt;&lt;/html&gt;\n</div>',NULL,0,'Basic',NULL,0,0,NULL),(1639893,'Calligraphy Textile, Topkapi Palace','<div class=\"description\">&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/REC-html40/loose.dtd&quot;&gt;\n&lt;html&gt;&lt;body&gt;&lt;p&gt;&lt;img src=&quot;http://localhost/SubjectsPlus/assets//images/132/calligraphy_textile.jpg&quot;&gt;&lt;/p&gt;&lt;p&gt;Textile Bands made for the Kaaba at Mecca, 16th C., Turkey&amp;nbsp; (ARTstor)&lt;/p&gt;&lt;/body&gt;&lt;/html&gt;\n</div>',NULL,0,'Basic',NULL,0,0,NULL),(1639940,'Topkapi Palace Courtyard','<div class=\"description\">&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/REC-html40/loose.dtd&quot;&gt;\n&lt;html&gt;&lt;body&gt;&lt;p&gt;&lt;img src=&quot;http://localhost/SubjectsPlus/assets//images/132/topkai_palace_courtyard.jpg&quot;&gt;&lt;/p&gt;&lt;p&gt;Painted Tile of Baths of Selim II,&amp;nbsp;C. 1566-75 (ARTstor)&lt;/p&gt;&lt;/body&gt;&lt;/html&gt;\n</div>',NULL,0,'Basic',NULL,0,0,NULL),(8935164,'The Raven','<div class=\"media\"><p>\"In the film... Cusack stars as the 19th-century American poet Edgar Allan Poe. In this fictionalized version of events, Poe must scramble to stop a serial killer from taking innocent lives in the style of Poe\'s often macabre work.\" - Huffington Post</p><p><a href=\"http://www.theravenmovie.com/\">Official Website for The Raven</a></p></div>',NULL,0,'Basic',NULL,0,0,NULL),(8935507,'Primary Resources','<div class=\"links\">{{dab},{72},{Digital Collection at University of Texas at Austin},{01}}<div class=\"link-description\">\"This digital collection was launched to accompany the 2009 Poe Bicentennial exhibition, From Out That Shadow: The Life and Legacy of Edgar Allan Poe\"</div></div><div class=\"links\">{{dab},{73},{New York Public Library Collection},{01}}<div class=\"link-description\">Contains Poe manuscripts, letters from George W. Eveleth artifacts, printed reproductions, facsimiles, and descriptions of Poe documents, and related printed material.</div></div><div class=\"links\">{{dab},{74},{Enoch Pratt Free Library},{01}}<div class=\"link-description\">letters, books, clippings and other memorabilia</div></div><div class=\"links\">{{dab},{75},{Letters at University of Virginia},{01}}<div class=\"link-description\">\"This virtual collection unites letters physically held in the University of Virginia Library Special Collections Department, Charlottesville, Va; the Poe Museum, Richmond, Va.; and the Valentine Museum, Richmond, Va. \"</div></div><div class=\"media\"><p>Here are links to collections of Poe\'s letters, manuscripts, and other memorabilia found in the special collections at other libraries.</p></div>',NULL,0,'Basic',NULL,0,0,NULL),(8937004,'Music','<div class=\"media\"><h3>\"Edgar Allan Poe\" from Snoopy!!! The Musical</h3></div>',NULL,0,'Basic',NULL,0,0,NULL),(8937636,'Introduction','<div class=\"media\"><p>Here you will find research materials about Poe, his life, and literary criticisms on his work.  To find more items you can search our <a href=\"http://new.library.miami.edu/\">library catalog</a> using the following subject search terms:</p><h3>Poe, Edgar Allan, 1809-1849</h3><h3>Poe, Edgar Allan, 1809-1849 -- Biography</h3><h3>Poe, Edgar Allan, 1809-1849 -- Encyclopedias</h3><h3>Poe, Edgar Allan, 1809-1849 -- Criticism and interpretation</h3></div>',NULL,0,'Basic',NULL,0,0,NULL),(8944744,'Articles & Databases','<div class=\"links\">{{dab},{76},{JSTOR},{01}}<div class=\"link-description\">Used by millions for research, teaching, and learning. With more than a thousand academic journals and over 1 million images, letters, and other primary sources, JSTOR is one of the world\'s most trusted sources for academic content.</div></div><div class=\"links\">{{dab},{77},{MLA: Modern Language Association},{01}}<div class=\"link-description\">The MLA Bibliography indexes some one million citations from over 4,000 journals and series published worldwide on modern languages, literatures, folklore, and linguistics.</div></div><div class=\"links\">{{dab},{78},{HathiTrust},{01}}<div class=\"link-description\">Note: Login at Hathi Trust site to download and build collections. From login tab, select University of Miami as the HathiTrust Partner Insititution and login with CaneID username/password.</div></div><div class=\"links\">{{dab},{79},{Literature Resource Center},{01}}<div class=\"link-description\">Literature Resource Center is a current, comprehensive, and reliable online resource for research on literary topics, authors, and their works.</div></div><div class=\"media\"><p>Search the <a href=\"http://new.library.miami.edu/\">library articles tab</a> for articles about Poe. You can also search the following <a href=\"http://new.library.miami.edu/sp/subjects/databases.php?letter=bysub&subject_id=30\">literary subject specific databases</a> for more articles.</p></div>',NULL,0,'Basic',NULL,0,0,NULL),(8944894,'e-books','<div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b3871763~S2\">The tell-tale heart and other writings</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b4493781~S2\">The poetical works of Edgar Allan Poe</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b4493778~S2\">The works of the late Edgar Allan Poe</a><div class=\"book-description\"></div></div><div class=\"media\"><p>Links go to permanent cataog URL with a different link to access the full text.</p></div>',NULL,0,'Basic',NULL,0,0,NULL),(8944899,'Introduction','<div class=\"media\"><p>There are many anthologies which contain some or all of Poe\'s written work.  Poe write many poems and short stories, but also literary criticisms for magazines of his time.  You can also find anthologies that contain Poe\'s personal correspondence with others.  To see a full list of the libraries\' holdings of Poe as an author, do an author search in our <a href=\"http://new.library.miami.edu/\">library catalog</a> with the following search term:</p><h3>Poe, Edgar Allan, 1809-1849</h3></div>',NULL,0,'Basic',NULL,0,0,NULL),(8948266,'audiobook','<div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b3517737~S11\">The Edgar Allan Poe audio collection</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b4455890~S2\">The pit and the pendulum</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://librivox.org/\">LibriVox</a><div class=\"book-description\"></div></div><div class=\"media\"></div>',NULL,0,'Basic',NULL,0,0,NULL),(8950167,'Foreign Language','<div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b4262954~S11\">Cuentos clásicos del norte</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b1524456~S11\">Seven tales. With a French translation and prefatory essay by Charles Baudelaire</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"https://catalog.librivox.org/search.php?title=&author=poe%2C+edgar+allan&status=all&action=Search\">LibriVox</a><div class=\"book-description\"></div></div><div class=\"media\"></div>',NULL,0,'Basic',NULL,0,0,NULL),(8951758,'Web Resources','<div class=\"links\">{{dab},{80},{IMDB - Edgar Allan Poe},{01}}<div class=\"link-description\">List of movies with Edgar Allan Poe crediting Poe as writer</div></div><div class=\"links\">{{dab},{81},{IMDB - Edgar Allan Poe (character)},{01}}<div class=\"link-description\">Movies which portray Poe</div></div><div class=\"links\">{{dab},{82},{Biography.com},{01}}<div class=\"link-description\">Includes text and a one hour online video biography</div></div><div class=\"links\">{{dab},{83},{  Edgar Awards},{01}}<div class=\"link-description\">Each Spring, Mystery Writers of America present the Edgar Awards, widely acknowledged to be the most prestigious awards in the genre.</div></div><div class=\"media\"></div>',NULL,0,'Basic',NULL,0,0,NULL),(8952331,'Music','<div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b4229932~S5\">Threshold of night</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b3958379~S5\">Two one-act operas : The cask of Amontillado</a><div class=\"book-description\"></div></div><div class=\"books\"><a href=\"http://catalog.library.miami.edu/record=b4167510~S5\">Eleven echoes of autumn</a><div class=\"book-description\"></div></div><div class=\"media\"><p>To browse a list of music pertaining to Poe in the library holdings search the <a href=\"http://new.library.miami.edu/media/\">CD/DVDs</a> tab on our library website. </p></div>',NULL,0,'Basic',NULL,0,0,NULL),(8953563,'The Raven','<div class=\"media\"><p>\"Treehouse of Horror\" - The Simpsons, 1990</p></div>',NULL,0,'Basic',NULL,0,0,NULL);
/*!40000 ALTER TABLE `pluslet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pluslet_section`
--

DROP TABLE IF EXISTS `pluslet_section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pluslet_section` (
  `pluslet_section_id` int(11) NOT NULL AUTO_INCREMENT,
  `pluslet_id` int(11) NOT NULL DEFAULT '0',
  `section_id` int(11) NOT NULL,
  `pcolumn` int(11) NOT NULL,
  `prow` int(11) NOT NULL,
  PRIMARY KEY (`pluslet_section_id`),
  KEY `fk_pt_pluslet_id_idx` (`pluslet_id`),
  KEY `fk_pt_tab_id_idx` (`section_id`),
  CONSTRAINT `fk_pt_section_id` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pluslet_section`
--

LOCK TABLES `pluslet_section` WRITE;
/*!40000 ALTER TABLE `pluslet_section` DISABLE KEYS */;
INSERT INTO `pluslet_section` VALUES (1,16,2,0,1),(60,1364502,2144292564,0,1),(61,1364503,2144292564,0,2),(62,1364505,2144292565,0,1),(63,1364506,2144292565,0,2),(64,1639789,2144292565,0,3),(65,1364507,2144292566,0,1),(66,1364508,2144292566,0,2),(67,1364510,2144292567,0,1),(68,1639940,2144292567,0,2),(69,1364512,2144292568,0,1),(70,1639893,2144292568,0,2),(71,1364513,2144292569,0,1),(72,1364515,2144292570,0,1),(73,1364517,2144292571,0,1),(92,182478,866523424,0,0),(93,183246,866523424,0,0),(94,8935164,866523424,0,0),(95,8937004,866523424,0,0),(96,182849,783067248,0,0),(97,8935507,783067248,0,0),(98,8937636,783067248,0,0),(99,8944744,783067248,0,0),(100,182908,27174617,0,0),(101,8951758,27174617,0,0),(102,8952331,27174617,0,0),(103,8953563,27174617,0,0),(104,183171,657887619,0,0),(105,183173,657887619,0,0),(106,8944894,657887619,0,0),(107,8944899,657887619,0,0),(108,8948266,657887619,0,0),(109,8950167,657887619,0,0);
/*!40000 ALTER TABLE `pluslet_section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rank`
--

DROP TABLE IF EXISTS `rank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rank` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rank`
--

LOCK TABLES `rank` WRITE;
/*!40000 ALTER TABLE `rank` DISABLE KEYS */;
INSERT INTO `rank` VALUES (1,0,1,1,1,'');
/*!40000 ALTER TABLE `rank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restrictions`
--

DROP TABLE IF EXISTS `restrictions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restrictions` (
  `restrictions_id` int(10) NOT NULL AUTO_INCREMENT,
  `restrictions` text,
  PRIMARY KEY (`restrictions_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restrictions`
--

LOCK TABLES `restrictions` WRITE;
/*!40000 ALTER TABLE `restrictions` DISABLE KEYS */;
INSERT INTO `restrictions` VALUES (1,'None'),(2,'Restricted'),(3,'On Campus Only'),(4,'Rest--No Proxy');
/*!40000 ALTER TABLE `restrictions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_index` int(11) NOT NULL DEFAULT '0',
  `layout` varchar(255) NOT NULL DEFAULT '4-4-4',
  `tab_id` int(11) NOT NULL,
  PRIMARY KEY (`section_id`),
  KEY `fk_section_tab_idx` (`tab_id`),
  CONSTRAINT `fk_section_tab` FOREIGN KEY (`tab_id`) REFERENCES `tab` (`tab_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2144292573 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
INSERT INTO `section` VALUES (1,0,'4-6-2',1),(2,0,'4-4-4',3),(27174617,1,'4-4-4',70723),(657887619,1,'4-4-4',70830),(783067248,1,'4-4-4',70721),(866523424,1,'4-4-4',70212),(2144292564,0,'6-3-3',548042),(2144292565,0,'4-4-4',548043),(2144292566,0,'4-4-4',548044),(2144292567,0,'4-4-4',548045),(2144292568,0,'4-4-4',548046),(2144292569,0,'4-4-4',548047),(2144292570,0,'4-4-4',548048),(2144292571,0,'4-4-4',548049),(2144292572,0,'4-4-4',548050);
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `source`
--

DROP TABLE IF EXISTS `source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `source` (
  `source_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `source` varchar(255) DEFAULT NULL,
  `rs` int(10) DEFAULT NULL,
  PRIMARY KEY (`source_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `source`
--

LOCK TABLES `source` WRITE;
/*!40000 ALTER TABLE `source` DISABLE KEYS */;
INSERT INTO `source` VALUES (1,'Journals/Magazines',1),(2,'Newspapers',5),(3,'Web Sites',10),(4,'FAQs',15),(5,'Almanacs & Yearbooks',100),(6,'Atlases',100),(7,'Bibliographies',100),(8,'Biographical Information',100),(9,'Concordances',100),(10,'Dictionaries',100),(11,'Encyclopedias',100),(12,'Government Information',100),(13,'Grants/Scholarships/Financial Aid',100),(14,'Handbooks & Guides',100),(15,'Images',100),(16,'Local',100),(17,'Primary Sources',100),(18,'Quotations',100),(19,'Regional',100),(20,'Reviews',100),(21,'Statistics/Data',100),(22,'Directories',100),(23,'Dissertations',100),(24,'Newspapers--International',100),(25,'Newswires',100),(26,'TV Stations',100),(27,'Radio Stations',100),(28,'Transcripts',100),(30,'Audio Files',100),(31,'Organizations',100);
/*!40000 ALTER TABLE `source` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
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
  PRIMARY KEY (`staff_id`),
  KEY `fk_supervisor_staff_id_idx` (`supervisor_id`),
  KEY `fk_staff_user_type_id_idx` (`user_type_id`),
  KEY `fk_staff_department_id_idx` (`department_id`),
  KEY `INDEXSEARCHstaff` (`lname`(255),`fname`(255)),
  CONSTRAINT `fk_staff_user_type_id` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`user_type_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'Admin','Super','SubjectsPlus Admin','5555',1,0,'j.little@miami.edu','',0,1,'5f4dcc3b5aa765d61d8327deb882cf99',1,'talkback|faq|records|eresource_mgr|videos|admin|librarian|supervisor','{\"css\": \"basic\"}','This is the default user with a SubjectsPlus install.  You should delete or rename me before you go live!',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_subject`
--

DROP TABLE IF EXISTS `staff_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_subject` (
  `staff_id` int(11) NOT NULL DEFAULT '0',
  `subject_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`staff_id`,`subject_id`),
  KEY `fk_ss_subject_id_idx` (`subject_id`),
  KEY `fk_ss_staff_id_idx` (`staff_id`),
  CONSTRAINT `fk_ss_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ss_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_subject`
--

LOCK TABLES `staff_subject` WRITE;
/*!40000 ALTER TABLE `staff_subject` DISABLE KEYS */;
INSERT INTO `staff_subject` VALUES (1,1),(1,2);
/*!40000 ALTER TABLE `staff_subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject` (
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
) ENGINE=InnoDB AUTO_INCREMENT=60858 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject`
--

LOCK TABLES `subject` WRITE;
/*!40000 ALTER TABLE `subject` DISABLE KEYS */;
INSERT INTO `subject` VALUES (1,'General',1,'general','',NULL,NULL,NULL,'Subject','2011-03-26 23:16:19',NULL,'{\"maincol\":\"\"}'),(2,'test',0,'test','','default','','','Subject','2015-02-18 20:16:20',NULL,'{\"maincol\":\"\"}'),(10536,'Edgar Allan Poe',0,'Edgar_Allan_Poe',NULL,NULL,'Important links in the world of Poe','',NULL,'2015-02-20 14:46:15',NULL,NULL),(60857,'Islamic Studies',0,'Islamic_Studies',NULL,NULL,'Selected print and electronic resources in the subject of Islam available through the University of Miami Libraries and open access web sites.','',NULL,'2015-02-20 14:42:48',NULL,NULL);
/*!40000 ALTER TABLE `subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject_department`
--

DROP TABLE IF EXISTS `subject_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_department` (
  `idsubject_department` int(11) NOT NULL AUTO_INCREMENT,
  `id_subject` bigint(20) NOT NULL,
  `id_department` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idsubject_department`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_department`
--

LOCK TABLES `subject_department` WRITE;
/*!40000 ALTER TABLE `subject_department` DISABLE KEYS */;
INSERT INTO `subject_department` VALUES (1,2,0,'2015-02-18 20:16:20');
/*!40000 ALTER TABLE `subject_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject_discipline`
--

DROP TABLE IF EXISTS `subject_discipline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_discipline` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='added v2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_discipline`
--

LOCK TABLES `subject_discipline` WRITE;
/*!40000 ALTER TABLE `subject_discipline` DISABLE KEYS */;
/*!40000 ALTER TABLE `subject_discipline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject_subject`
--

DROP TABLE IF EXISTS `subject_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_subject` (
  `id_subject_subject` int(11) NOT NULL AUTO_INCREMENT,
  `subject_parent` bigint(20) NOT NULL,
  `subject_child` bigint(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_subject_subject`),
  KEY `fk_subject_parent_idx` (`subject_parent`),
  KEY `fk_subject_child_idx` (`subject_child`),
  CONSTRAINT `fk_subject_child` FOREIGN KEY (`subject_child`) REFERENCES `subject` (`subject_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_subject_parent` FOREIGN KEY (`subject_parent`) REFERENCES `subject` (`subject_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_subject`
--

LOCK TABLES `subject_subject` WRITE;
/*!40000 ALTER TABLE `subject_subject` DISABLE KEYS */;
/*!40000 ALTER TABLE `subject_subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tab`
--

DROP TABLE IF EXISTS `tab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tab` (
  `tab_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` bigint(20) NOT NULL DEFAULT '0',
  `label` varchar(120) NOT NULL DEFAULT 'Main',
  `tab_index` int(11) NOT NULL DEFAULT '0',
  `external_url` varchar(500) DEFAULT NULL,
  `visibility` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tab_id`),
  KEY `fk_t_subject_id_idx` (`subject_id`),
  CONSTRAINT `fk_t_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=548051 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tab`
--

LOCK TABLES `tab` WRITE;
/*!40000 ALTER TABLE `tab` DISABLE KEYS */;
INSERT INTO `tab` VALUES (1,1,'Main',0,NULL,1),(3,2,'Main',0,'',1),(70212,10536,'Home',0,NULL,1),(70721,10536,'Reference',1,NULL,1),(70723,10536,'Multimedia',2,NULL,1),(70830,10536,'Bibliography',3,NULL,1),(548042,60857,'Islamic Studies Home',0,'',1),(548043,60857,'Find Articles/Databases',1,'',1),(548044,60857,'Web Resources',2,'',1),(548045,60857,'Dictionaries & Encyclopedias',3,'',1),(548046,60857,'Primary Texts',4,'',1),(548047,60857,'Maps & Atlases',5,'',1),(548048,60857,'Art and Architecture',6,'',1),(548049,60857,'Biographies',7,'',1),(548050,60857,'Middle Eastern Studies',8,'',1);
/*!40000 ALTER TABLE `tab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `talkback`
--

DROP TABLE IF EXISTS `talkback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `talkback` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talkback`
--

LOCK TABLES `talkback` WRITE;
/*!40000 ALTER TABLE `talkback` DISABLE KEYS */;
/*!40000 ALTER TABLE `talkback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `title`
--

DROP TABLE IF EXISTS `title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `title` (
  `title_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alternate_title` varchar(255) DEFAULT NULL,
  `description` text,
  `pre` varchar(255) DEFAULT NULL,
  `last_modified_by` varchar(50) DEFAULT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`title_id`),
  KEY `INDEXSEARCHtitle` (`title`,`alternate_title`,`description`(200))
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `title`
--

LOCK TABLES `title` WRITE;
/*!40000 ALTER TABLE `title` DISABLE KEYS */;
INSERT INTO `title` VALUES (1,'Sample Record',NULL,'Here you can enter a description of the record.&nbsp; A description may be overwritten for a given subject by clicking the icon next to the desired subject in the Record screen.<br />',NULL,NULL,'2011-03-27 00:08:54'),(2,'New Acquisitions in Religion',NULL,'',NULL,NULL,'2015-02-20 14:31:03'),(3,'Department of Religious Studies',NULL,'',NULL,NULL,'2015-02-20 14:31:03'),(4,'IBISWEB Home Page',NULL,'',NULL,NULL,'2015-02-20 14:31:03'),(5,'ATLA Religion Database with ATLASerials',NULL,'A collection of major religious and theology journals by religion scholars in the U.S.<br/>',NULL,NULL,'2015-02-20 14:31:04'),(6,'Brill Dictionary of Religion',NULL,'Online version of the 2006 print edition.<br/>',NULL,NULL,'2015-02-20 14:31:04'),(7,'Oxford Islamic Studies Online',NULL,'Full text access to The Oxford Encyclopedia of the Modern Islamic World, The Oxford History of Islam, The Oxford Dictionary of Islam, two classic interpretations of the Qur\'an, and more.',NULL,NULL,'2015-02-20 14:31:04'),(8,'Encyclopedia of the Qur\'an',NULL,'An encyclopaedic dictionary of qurʾānic terms, concepts, personalities, place names, cultural history and exegesis extended with essays on themes and subjects within qurʾānic studies.',NULL,NULL,'2015-02-20 14:31:04'),(9,'Encyclopedia of Islam, THREE',NULL,'Third editionof the Encyclopedia of Islam, vastly expanded and updated, but in process. Portions are added continuously to the database as they become available.',NULL,NULL,'2015-02-20 14:31:04'),(10,'Encyclopedia of Islam',NULL,'Online edition covering all aspects of Islam in the broadest range of historical and geographic subjects.',NULL,NULL,'2015-02-20 14:31:04'),(11,'Encyclopedia of Women & Islamic Cultures',NULL,'A survey all facets of life of women in Muslim societies. <br/>',NULL,NULL,'2015-02-20 14:31:04'),(12,'Index Islamicus',NULL,'This database indexes literature on Islam, the Middle East and the Muslim world. It is produced by the Islamic Bibliography Unit at Cambridge University Library.',NULL,NULL,'2015-02-20 14:31:04'),(13,'Bibliography of Asian Studies',NULL,'An index to predominantly western-language articles and book chapters on all parts of Asia published since 1971.',NULL,NULL,'2015-02-20 14:31:04'),(14,'Oxford Reference Online:  Religion & Philosophy',NULL,'Part of the Oxford Reference Online series that contain about 100 general and subject dictionaries, and language reference works published by Oxford University Press. The collection is fully-indexed and cross-searchable.<br/><br/>',NULL,NULL,'2015-02-20 14:31:05'),(15,'Encyclopaedia Iranica',NULL,'A multi-disciplinary reference work and research tool designed to record the facts of Iranian history and civilization.<br/>',NULL,NULL,'2015-02-20 14:31:05'),(16,'FRANCIS',NULL,'Indexes over 4000 journals in the disciplines Psychology, Arts and Literature, Philosophy, and Religion.',NULL,NULL,'2015-02-20 14:31:05'),(17,'Iter:  Gateway to the Middle Ages and the Renaissance',NULL,'Iter features 2 scholarly bibliographic databases, one featuring journal articles, and the other, books.  Period covered is 400-1700.<br/>',NULL,NULL,'2015-02-20 14:31:05'),(18,'JSTOR Electronic Press',NULL,'A non-profit, independent organization that converts and maintains full-text digital versions of the backfiles of major scholarly journals.<br/>',NULL,NULL,'2015-02-20 14:31:05'),(19,'Bibliography of Asian Studies',NULL,'Online version of the Bibliography of Asian Studies (BAS), referencing principally western-language articles and book chapters on all parts of Asia published since 1971.<br/>',NULL,NULL,'2015-02-20 14:31:05'),(20,'Anthropology Plus',NULL,'Covers anthropology, archaeology, and interdisciplinary studies.  Includes Anthropological Index: 1957-present & Anthropological Literature: Late 19th century to present. <br/>',NULL,NULL,'2015-02-20 14:31:05'),(21,'AnthroSource',NULL,'Provides access to the peer reviewed journals, newsletters and bulletins of the American Anthropological Association (AAA).',NULL,NULL,'2015-02-20 14:31:06'),(22,'Sociological Abstracts',NULL,'It indexes and abstracts research literature published worldwide in journals and other serial publications.',NULL,NULL,'2015-02-20 14:31:06'),(23,'Social Science Citation Index',NULL,'Provides international, cover-to-cover indexing of the most cited journals in social sciences. The databases is searched via the ISI WEB OF SCIENCE Citation Databases service.<br/>',NULL,NULL,'2015-02-20 14:31:06'),(24,'International Medieval Bibliography',NULL,'Includes International Medieval Bibliography, Bibliographie de Civilisation Médiévale, and International Directory of Medievalists.',NULL,NULL,'2015-02-20 14:31:06'),(25,'Academic Search Premier',NULL,'A multi-disciplinary full text database containing full text for nearly 4,500 journals, including more than 3,600 peer-reviewed titles.<br/>',NULL,NULL,'2015-02-20 14:31:06'),(26,'ProQuest Research Library',NULL,'An interdisciplinary general reference database containing over 2,500 periodicals--nearly 1,000 of them in ASCII full-text or full-image formats.<br/>',NULL,NULL,'2015-02-20 14:31:06'),(27,'Academic OneFile',NULL,'A source for peer-reviewed, full-text articles from journals and reference sources. Offers extensive coverage of the physical sciences, technology, medicine, social sciences, the arts, theology, literature and other subjects.',NULL,NULL,'2015-02-20 14:31:06'),(28,'Historical Abstracts',NULL,'Indexes and abstracts journal articles and dissertations on world history from 1450 to present excluding the United States and Canada.<br/>',NULL,NULL,'2015-02-20 14:31:06'),(29,'Lexis Nexis Academic',NULL,'',NULL,NULL,'2015-02-20 14:31:07'),(30,'Abzu',NULL,'A guide to the study of the Ancient Near East and Ancient Mediterranean via the Internet.',NULL,NULL,'2015-02-20 14:31:07'),(31,'American Religion Data Archive (ARDA)',NULL,'Includes data on churches and church membership, religious professionals, and religious groups.',NULL,NULL,'2015-02-20 14:31:07'),(32,'Okeanos:  Biblical, Classical, and Ancient Near Eastern Studies',NULL,'An interdisciplinary resource for the study of the Ancient, Biblical, Classical, and Late Antique Near East.<br/>',NULL,NULL,'2015-02-20 14:31:07'),(33,'Columbia University Middle East Studies Internet Resource Guide',NULL,'',NULL,NULL,'2015-02-20 14:31:07'),(34,'Islam in the United States',NULL,'Official State Deparatment Fact Sheet.',NULL,NULL,'2015-02-20 14:31:07'),(35,'Islamic Philosophy Online',NULL,'A site dedicated to the academic study of the philosophical output of the Muslim World.',NULL,NULL,'2015-02-20 14:31:07'),(36,'McGill University Middle East Subject Guide',NULL,'',NULL,NULL,'2015-02-20 14:31:07'),(37,'The Qur\'an',NULL,'A complete, searchable English text made available by the University of Virginia.',NULL,NULL,'2015-02-20 14:31:08'),(38,'  Qur\'an',NULL,'A complete, searchable English text made available by the University of Virginia.','The',NULL,'2015-02-20 14:31:08'),(39,'The Sufi Literature Archive',NULL,'Maintained by the University of North Carolina at Chapel Hill.',NULL,NULL,'2015-02-20 14:31:08'),(40,'  Sufi Literature Archive',NULL,'Maintained by the University of North Carolina at Chapel Hill.','The',NULL,'2015-02-20 14:31:08'),(41,'USCB Library Internet Resources for Middle East and Islamic Studies',NULL,'Maintained by the University of California at Santa Barbara.',NULL,NULL,'2015-02-20 14:31:08'),(42,'University of Georgia Research Guide in Islam',NULL,'',NULL,NULL,'2015-02-20 14:31:08'),(43,'Wabash Center Internet Guide to Religion:  Islam',NULL,'',NULL,NULL,'2015-02-20 14:31:09'),(44,'Yale University Research Guide for Religion:  Islam',NULL,'',NULL,NULL,'2015-02-20 14:31:09'),(45,'Carl Ernst, Resources for Islamic Studies',NULL,'Carl W. Ernst is a specialist in Islamic studies, with a focus on West and South Asia. His published research, based on the study of Arabic, Persian, and Urdu, has been mainly devoted to the study of Islam and Sufism.',NULL,NULL,'2015-02-20 14:31:09'),(46,'Internet Islamic History Sourcebook',NULL,'A collection of public domain and copy-permitted historical texts, edited by Paul Halsall at Fordham University.',NULL,NULL,'2015-02-20 14:31:09'),(47,'Compendium of Mulsim Texts',NULL,'Prepared by the University of Southern California Muslim Students Association.',NULL,NULL,'2015-02-20 14:31:09'),(48,'Al-Islam.org',NULL,'The Ahlul Bayt Digital Islamic Library Project, U.K., and Qom, Iran.  It is a very good Shi\'i website with translations of important religious texts.',NULL,NULL,'2015-02-20 14:31:09'),(49,'Al-Khazina',NULL,'A comprehensive website with searchable Qur\'ans, virtual hajj, Sufism, maps, and biographies of scholars.',NULL,NULL,'2015-02-20 14:31:09'),(50,'The Encyclopedia of Religion',NULL,'The standard, scholarly encyclopedia in the general field of religion.',NULL,NULL,'2015-02-20 14:31:09'),(51,'  Encyclopedia of Religion',NULL,'The standard, scholarly encyclopedia in the general field of religion.','The',NULL,'2015-02-20 14:31:09'),(52,'Encyclopedia of Islam, THREE',NULL,'Third editionof the Encyclopedia of Islam, vastly expanded and updated, but in process.  Portions are added continuously to the database as they become available.',NULL,NULL,'2015-02-20 14:31:10'),(53,'A Shi\'ite Encyclopedia',NULL,'A collection of information which addresses Shia/Sunni inter-school related issues.',NULL,NULL,'2015-02-20 14:31:10'),(54,'  Shi\'ite Encyclopedia',NULL,'A collection of information which addresses Shia/Sunni inter-school related issues.','A',NULL,'2015-02-20 14:31:10'),(55,'Columbia Gazetteer of the World Online',NULL,'A full-text database with detailed descriptions and statistics on thousands of countries, cities, mountains, rivers, and every other kind of political area or geographic feature.<br/>',NULL,NULL,'2015-02-20 14:31:10'),(56,'AWMP (Ancient World Mapping Center)',NULL,'',NULL,NULL,'2015-02-20 14:31:10'),(57,'Perry-Castaneda Library Map Collection',NULL,'A University of Texas Web site, it provides a well-organized collection of historical and current maps worldwide. All maps available on this server are in the public domain, and may be freely downloaded and copied.<br/><br/>',NULL,NULL,'2015-02-20 14:31:10'),(58,'Cultural Atlas of Islam',NULL,'',NULL,NULL,'2015-02-20 14:31:10'),(59,'Historical Atlas of Islam',NULL,'',NULL,NULL,'2015-02-20 14:31:10'),(60,'ARTstor',NULL,'A database of digital images and accompanying scholarly information for use in art history funded by the Andrew Mellon Foundation.<br/>',NULL,NULL,'2015-02-20 14:31:10'),(61,'Index of Christian Art',NULL,'A database of medieval art with full-text records for nearly 80,000 works of art dating from early apostolic times to A.D. 1550, including some Islamic art.<br/>',NULL,NULL,'2015-02-20 14:31:10'),(62,'Timeline of Art History  - The Metropolitan Museum of Art',NULL,'A chronological, geographical, and thematic exploration of the history of art from around the world, as illustrated especially by the Metropolitan Museum of Art\'s collection.  Select a time period, scroll to bottom and select Islamic Art.<br/>',NULL,NULL,'2015-02-20 14:31:11'),(63,'Great Buildings',NULL,'The leading architecture reference site on the web.  Can search by Buildings, Architectural Types (Islamic), or Places (select a country), or do a Quick Search ( \"mosque\").',NULL,NULL,'2015-02-20 14:31:11'),(64,'Islamic Art and Architecture, 650-1250',NULL,'',NULL,NULL,'2015-02-20 14:31:11'),(65,'The Art and Architecture of Islam, 1250-1800',NULL,'',NULL,NULL,'2015-02-20 14:31:11'),(66,'  Art and Architecture of Islam, 1250-1800',NULL,'','The',NULL,'2015-02-20 14:31:12'),(67,'Dictionary of Islamic Architecture',NULL,'',NULL,NULL,'2015-02-20 14:31:12'),(68,'Casselman Archive of Islamic and Mudejar Architecture in Spain',NULL,'',NULL,NULL,'2015-02-20 14:31:12'),(69,'Biography and Genealogy Master Index',NULL,'It indexes current,readily available reference sources, as well as retrospective works that cover individuals,both living and deceased,from every field.<br/>',NULL,NULL,'2015-02-20 14:31:12'),(70,'The Literature Network - Edgar Allan Poe',NULL,'Full text to many of Poe\'s writings.',NULL,NULL,'2015-02-20 14:45:33'),(71,'  Literature Network - Edgar Allan Poe',NULL,'Full text to many of Poe\'s writings.','The',NULL,'2015-02-20 14:45:34'),(72,'Poe Studies Association',NULL,'\"The Poe Studies Association... provides a forum for the scholarly and informal exchange of information on Edgar Allan Poe, his life, and works.\" - Poe Studies Association',NULL,NULL,'2015-02-20 14:45:34'),(73,'Poe Museum',NULL,'\"The Poe Museum provides a retreat into early 19th century Richmond where Edgar Allan Poe lived and worked.\" - Poe Museum',NULL,NULL,'2015-02-20 14:45:34'),(74,'Edgar Allan Poe National Historic Site',NULL,'Poe\'s Pennsylvania home',NULL,NULL,'2015-02-20 14:45:34'),(75,'Edgar Allan Poe society of Baltimore',NULL,'\"Since 1977, the Poe Society has returned its efforts to focus on the annual commemorative lecture and associated publications.\"',NULL,NULL,'2015-02-20 14:45:34'),(76,'Bronx Historical Society',NULL,'Proprietors of one of Poe\'s residence\'s and short documentary.',NULL,NULL,'2015-02-20 14:45:34'),(77,'Knowing Poe',NULL,'Made for classroom setting, but containing a lot of useful information and links for all.',NULL,NULL,'2015-02-20 14:45:34'),(78,'Digital Collection at University of Texas at Austin',NULL,'\"This digital collection was launched to accompany the 2009 Poe Bicentennial exhibition, From Out That Shadow: The Life and Legacy of Edgar Allan Poe\"',NULL,NULL,'2015-02-20 14:45:34'),(79,'New York Public Library Collection',NULL,'Contains Poe manuscripts, letters from George W. Eveleth artifacts, printed reproductions, facsimiles, and descriptions of Poe documents, and related printed material.',NULL,NULL,'2015-02-20 14:45:34'),(80,'Enoch Pratt Free Library',NULL,'letters, books, clippings and other memorabilia',NULL,NULL,'2015-02-20 14:45:35'),(81,'Letters at University of Virginia',NULL,'\"This virtual collection unites letters physically held in the University of Virginia Library Special Collections Department, Charlottesville, Va; the Poe Museum, Richmond, Va.; and the Valentine Museum, Richmond, Va. \"',NULL,NULL,'2015-02-20 14:45:35'),(82,'JSTOR',NULL,'Used by millions for research, teaching, and learning. With more than a thousand academic journals and over 1 million images, letters, and other primary sources, JSTOR is one of the world\'s most trusted sources for academic content.',NULL,NULL,'2015-02-20 14:45:35'),(83,'MLA: Modern Language Association',NULL,'The MLA Bibliography indexes some one million citations from over 4,000 journals and series published worldwide on modern languages, literatures, folklore, and linguistics.',NULL,NULL,'2015-02-20 14:45:35'),(84,'HathiTrust',NULL,'Note: Login at Hathi Trust site to download and build collections. From login tab, select University of Miami as the HathiTrust Partner Insititution and login with CaneID username/password.',NULL,NULL,'2015-02-20 14:45:35'),(85,'Literature Resource Center',NULL,'Literature Resource Center is a current, comprehensive, and reliable online resource for research on literary topics, authors, and their works.',NULL,NULL,'2015-02-20 14:45:35'),(86,'IMDB - Edgar Allan Poe',NULL,'List of movies with Edgar Allan Poe crediting Poe as writer',NULL,NULL,'2015-02-20 14:45:35'),(87,'IMDB - Edgar Allan Poe (character)',NULL,'Movies which portray Poe',NULL,NULL,'2015-02-20 14:45:35'),(88,'Biography.com',NULL,'Includes text and a one hour online video biography',NULL,NULL,'2015-02-20 14:45:35'),(89,'The Edgar Awards',NULL,'Each Spring, Mystery Writers of America present the Edgar Awards, widely acknowledged to be the most prestigious awards in the genre.',NULL,NULL,'2015-02-20 14:45:35'),(90,'  Edgar Awards',NULL,'Each Spring, Mystery Writers of America present the Edgar Awards, widely acknowledged to be the most prestigious awards in the genre.','The',NULL,'2015-02-20 14:45:36'),(91,'Fantastic Fiction',NULL,'Edgar Allan Poe bibliography.  Also contains list of anthologies containing Edgar Allan Poe\'s stories.',NULL,NULL,'2015-02-20 14:45:36'),(92,'Graham\'s Magazine',NULL,'This archive contains Poe\'s articles from the journal.  When searching remember that he went by \"Edgar A. Poe\" in life.',NULL,NULL,'2015-02-20 14:45:36');
/*!40000 ALTER TABLE `title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uml_refstats`
--

DROP TABLE IF EXISTS `uml_refstats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uml_refstats` (
  `refstats_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `note` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`refstats_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uml_refstats`
--

LOCK TABLES `uml_refstats` WRITE;
/*!40000 ALTER TABLE `uml_refstats` DISABLE KEYS */;
/*!40000 ALTER TABLE `uml_refstats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uml_refstats_location`
--

DROP TABLE IF EXISTS `uml_refstats_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uml_refstats_location` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uml_refstats_location`
--

LOCK TABLES `uml_refstats_location` WRITE;
/*!40000 ALTER TABLE `uml_refstats_location` DISABLE KEYS */;
INSERT INTO `uml_refstats_location` VALUES (1,'Information Desk (Richter)'),(2,'Circulation Desk (Richter)'),(3,'Digital Media Lab'),(4,'Architecture'),(5,'Business'),(6,'CHC'),(7,'Music'),(8,'RSMAS'),(9,'Special Collections'),(10,'Other (include ntoe)');
/*!40000 ALTER TABLE `uml_refstats_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uml_refstats_mode`
--

DROP TABLE IF EXISTS `uml_refstats_mode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uml_refstats_mode` (
  `mode_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`mode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uml_refstats_mode`
--

LOCK TABLES `uml_refstats_mode` WRITE;
/*!40000 ALTER TABLE `uml_refstats_mode` DISABLE KEYS */;
INSERT INTO `uml_refstats_mode` VALUES (1,'In Person'),(2,'Phone'),(3,'Email'),(4,'IM');
/*!40000 ALTER TABLE `uml_refstats_mode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uml_refstats_type`
--

DROP TABLE IF EXISTS `uml_refstats_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uml_refstats_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uml_refstats_type`
--

LOCK TABLES `uml_refstats_type` WRITE;
/*!40000 ALTER TABLE `uml_refstats_type` DISABLE KEYS */;
INSERT INTO `uml_refstats_type` VALUES (1,'Computer Hardware'),(2,'Computer Software'),(3,'Directional'),(4,'Printers/Copiers'),(5,'Reference');
/*!40000 ALTER TABLE `uml_refstats_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_type`
--

DROP TABLE IF EXISTS `user_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_type` (
  `user_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(100) NOT NULL,
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_type`
--

LOCK TABLES `user_type` WRITE;
/*!40000 ALTER TABLE `user_type` DISABLE KEYS */;
INSERT INTO `user_type` VALUES (1,'Staff'),(2,'Machine'),(3,'Student');
/*!40000 ALTER TABLE `user_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video`
--

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-09  8:56:06
