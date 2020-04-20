<?php

namespace SubjectsPlus\API;

/**
 * LtiWebService - this class represents the LTI web service
 *
 * @package SubjectsPlus API
 * @author acarrasco
 * @copyright Copyright (c) 2019
 * @date August 2019
 * @version 1.0
 * @access public
 */
class LtiWebService extends WebService implements InterfaceWebService {

	/**
	 * LtiWebService::__construct() - pass parameters to parent construct and
	 * set the service and tag properties
	 *
	 * @param array $lobjUrlParams
	 * @param DBConnector $lobjDBConnector
	 */
	function __construct( $lobjUrlParams, $lobjDBConnector ) {
		global $lti_enabled;
		if ( isset( $lti_enabled ) ) {
			if ( $lti_enabled ) {
				parent::__construct( $lobjUrlParams, $lobjDBConnector );
				$this->mstrService = 'lti';
				$this->mstrTag     = 'lti';
			}
		}

	}

	/**
	 * LtiWebService::sanitizeParams() -  - goes through passed array parameter
	 * and sanitizes elements that are valid url parameters
	 *
	 * @param array $lobjParams
	 *
	 * @return array
	 */
	public function sanitizeParams( array $lobjParams ) {
		$lobjFinalParams = array();

		foreach ( $lobjParams as $lstrKey => $lstrValue ) {
			switch ( strtolower( $lstrKey ) ) {
				case 'get':
					$lobjSplit = explode( ',', $lstrValue );

					foreach ( $lobjSplit as &$lstrUnScrubbed ) {
						$lstrUnScrubbed = scrubData( $lstrUnScrubbed );
					}

					$lobjFinalParams['get'] = $lobjSplit;
					break;
			}
		}

		return $lobjFinalParams;
	}

	/**
	 * LtiWebService::generateQuery() - returns a generated query based on all
	 * url parameters that will return desired Lti hits records
	 *
	 * @param array $lobjParams
	 *
	 * @return string
	 */
	public function generateQuery( array $lobjParams ) {

		$lobjConditions = array();

		$lstrQuery = "SELECT DATE(from_unixtime(date)) AS Date,
       tab_name                  AS 'Course Code',
       link_url                  AS 'Associated Guides',
       link_title                AS 'Research Guide Id',
       count(tab_name)           AS 'Hits Count',
       (SELECT IFNULL(GROUP_CONCAT(subject SEPARATOR '*--*'),'') from subject where FIND_IN_SET(subject_id, link_title)) AS 'Associated Guides Title'
FROM stats";

		foreach ( $lobjParams as $lstrKey => $lobjValues ) {

			switch ( $lstrKey ) {
				case "get":
					array_push( $lobjConditions, "event_type like '%lti%'" );
					foreach ( $lobjValues as $lstrQualifier ) {
						switch ( strtolower( $lstrQualifier ) ) {
							case "previous-month-hits":
								$init_date = new \DateTime( 'FIRST DAY OF PREVIOUS MONTH' );
								$init_date = strtotime( $init_date->format( 'Y-m-d' ) );
								$end_date  = new \DateTime( 'LAST DAY OF PREVIOUS MONTH' );
								$end_date->add(new \DateInterval('P1D'));
								$end_date  = strtotime( $end_date->format( 'Y-m-d' ) );
								array_push( $lobjConditions, "stats.date > $init_date" );
								array_push( $lobjConditions, "stats.date < $end_date" );
								break;
						}
						break;
					}
			}
		}

		if ( count( $lobjConditions ) > 0 ) {
			$lstrQuery .= "\nWHERE (" . implode( ') AND (', $lobjConditions );
			$lstrQuery .= ')';
		}

		$lstrQuery = $lstrQuery . " 
GROUP BY tab_name
ORDER BY stats.date";

		return $lstrQuery;
	}
}
