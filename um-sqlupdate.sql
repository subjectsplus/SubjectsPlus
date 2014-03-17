
-- --------------------------------------------------------

--
-- Table structure for table `uml_refstats`
--

CREATE TABLE IF NOT EXISTS `uml_refstats` (
  `refstats_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `note` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`refstats_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `uml_refstats`
--


-- --------------------------------------------------------

--
-- Table structure for table `uml_refstats_location`
--

CREATE TABLE IF NOT EXISTS `uml_refstats_location` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `uml_refstats_location`
--

INSERT INTO `uml_refstats_location` (`location_id`, `label`) VALUES
(1, 'Information Desk (Richter)'),
(2, 'Circulation Desk (Richter)'),
(3, 'Digital Media Lab'),
(4, 'Architecture'),
(5, 'Business'),
(6, 'CHC'),
(7, 'Music'),
(8, 'RSMAS'),
(9, 'Special Collections'),
(10, 'Other (include ntoe)');

-- --------------------------------------------------------

--
-- Table structure for table `uml_refstats_mode`
--

CREATE TABLE IF NOT EXISTS `uml_refstats_mode` (
  `mode_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`mode_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `uml_refstats_mode`
--

INSERT INTO `uml_refstats_mode` (`mode_id`, `label`) VALUES
(1, 'In Person'),
(2, 'Phone'),
(3, 'Email'),
(4, 'IM');

-- --------------------------------------------------------

--
-- Table structure for table `uml_refstats_type`
--

CREATE TABLE IF NOT EXISTS `uml_refstats_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `uml_refstats_type`
--

INSERT INTO `uml_refstats_type` (`type_id`, `label`) VALUES
(1, 'Computer Hardware'),
(2, 'Computer Software'),
(3, 'Directional'),
(4, 'Printers/Copiers'),
(5, 'Reference');
