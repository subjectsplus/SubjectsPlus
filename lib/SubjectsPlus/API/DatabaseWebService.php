<?php
namespace SubjectsPlus\API;
/**
 * DatabaseWebService - this class represents the database web service
 *
 * @package SubjectsPlus API
 * @author dgonzalez
 * @copyright Copyright (c) 2012
 * @date November 2012
 * @version $Id$
 * @access public
 */


class DatabaseWebService extends WebService implements InterfaceWebService
{
	/**
	 * DatabaseWebService::__construct() - pass parameters to parent construct and
	 * set the service and tag properties
	 *
	 * @param array $lobjUrlParams
	 * @param DBConnector $lobjDBConnector
	 */
	function __construct($lobjUrlParams, $lobjDBConnector)
	{
		parent::__construct($lobjUrlParams, $lobjDBConnector);
		$this->mstrService = 'databases';
		$this->mstrTag = 'database';
	}

	/**
	 * DatabaseWebService::sanitizeParams() -  - goes through passed array parameter
	 * and sanitizes elements that are valid url parameters
	 *
	 * @param array $lobjParams
	 * @return array
	 */
	function sanitizeParams(Array $lobjParams)
	{
		$lobjFinalParams = array();

		foreach($lobjParams as $lstrKey => $lstrValue)
		{
			switch(strtolower($lstrKey))
			{
				case 'letter':
					$lobjSplit = explode(',', $lstrValue);

					foreach($lobjSplit as &$lstrUnScrubbed)
					{
						$lstrUnScrubbed = scrubData($lstrUnScrubbed);
					}

					$lobjFinalParams['letter'] = $lobjSplit;
					break;
				case 'search':
					$lobjSplit = explode(',', $lstrValue);

					foreach($lobjSplit as &$lstrUnScrubbed)
					{
						$lstrUnScrubbed = scrubData($lstrUnScrubbed);
					}

					$lobjFinalParams['search'] = $lobjSplit;
					break;
				case 'subject_id':
					$lobjSplit = explode(',', $lstrValue);

					foreach($lobjSplit as &$lstrUnScrubbed)
					{
						$lstrUnScrubbed = scrubData($lstrUnScrubbed, 'integer');
					}

					$lobjFinalParams['subject_id'] = $lobjSplit;
					break;
				case 'type':
					$lobjSplit = explode(',', $lstrValue);

					foreach($lobjSplit as &$lstrUnScrubbed)
					{
						$lstrUnScrubbed = scrubData($lstrUnScrubbed);
					}

					$lobjFinalParams['type'] = $lobjSplit;
					break;
				case 'max':
					$lstrValue = scrubData($lstrValue, 'integer');

					$lobjFinalParams['max'] = $lstrValue;
					break;
			}
		}

		return $lobjFinalParams;
	}

	/**
	 * DatabaseWebService::generateQuery() - returns a generated query based on all
	 * url parameters that will return desired database records
	 *
	 * @param array $lobjParams
	 * @return string
	 */
	function generateQuery(Array $lobjParams)
	{
		$lstrQuery = 'SELECT distinct left(title,1) as initial, title, alternate_title, description, location, access_restrictions, title.title_id,
					eres_display, display_note, pre, citation_guide, ctags, helpguide, 
					IFNULL(
                        (SELECT GROUP_CONCAT(TRIM(subject) SEPARATOR \';\')
                         FROM rank,
                              subject,
                              source so
                         WHERE rank.subject_id = subject.subject_id
                           AND rank.source_id = so.source_id                           
                           AND active = 1
                           AND (subject.type = \'Subject\' || subject.type = \'Placeholder\')
                           AND title_id = title.title_id
                         ORDER BY subject)
                    , "") as subjects
					FROM title, restrictions, location, location_title, source, rank';

		$lobjConditions = array();

		foreach($lobjParams as $lstrKey => $lobjValues)
		{
			switch($lstrKey)
			{
				case 'letter':
					$lobjCondition = array();

					foreach ($lobjValues as $lstrQualifier)
					{
						switch (strtolower($lstrQualifier)) {
							case "num":
					            array_push($lobjCondition, "left(title, 1)  REGEXP '[[:digit:]]+'\n");
					            break;
							case "all":
								array_push($lobjCondition, "title != ''\n");
								break;
							case "all_letters_list":
								return "SELECT distinct UCASE(left(title,1)) AS initial
                    FROM location l, location_title lt, title t
                    WHERE l.location_id = lt.location_id AND lt.title_id = t.title_id
                    AND eres_display = 'Y'
                    AND left(title,1) REGEXP '[A-Z0-9]'
                    ORDER BY initial";
							case "all_by_subject_db_filter":
								return "SELECT s.subject_id, s.subject, s.type
FROM subject as s WHERE exists(
SELECT t.title, l.record_status, r.title_id, r.rank_id, r.description_override
FROM rank r, location_title lt, location l, title t
    WHERE subject_id = s.subject_id
    AND lt.title_id = r.title_id
    AND l.location_id = lt.location_id
    AND t.title_id = lt.title_id
    AND l.eres_display = 'Y'
    AND l.record_status = 'Active'
    AND r.dbbysub_active = 1)
AND s.active = 1
ORDER BY s.subject";
								break;
							default:
								array_push($lobjCondition, "title LIKE '$lstrQualifier%'\n");
								break;
						}
					}

					$lstrCombine = implode(' OR ', $lobjCondition);

					array_push($lobjConditions, $lstrCombine);
					break;
				case 'search':
					$lobjCondition = array();

					foreach ($lobjValues as $lstrSearch)
					{
						array_push($lobjCondition, "title LIKE '%$lstrSearch%'\n");
					}

					$lstrCombine = implode(' OR ', $lobjCondition);

					array_push($lobjConditions, $lstrCombine);
					break;
				case 'type':
					$lobjCondition = array();

					foreach ($lobjValues as $lstrType)
					{
						array_push($lobjCondition, "ctags LIKE '%$lstrType%'\n");
					}

					$lstrCombine = implode(' OR ', $lobjCondition);

					array_push($lobjConditions, $lstrCombine);
					break;
				case 'subject_id':
					$lobjCondition = array();

					foreach ($lobjValues as $lintSubjectID)
					{
						array_push($lobjCondition, "subject_id = $lintSubjectID\n");
					}

					$lstrCombine = implode(' OR ', $lobjCondition);

					array_push($lobjConditions, $lstrCombine);
					break;
			}
		}

		if(count($lobjConditions) > 0)
		{
			$lstrQuery .= "\nWHERE (" . implode(') AND (', $lobjConditions);
			$lstrQuery .= ')';
			$lstrQuery .= " AND title.title_id = location_title.title_id
				    AND location.location_id = location_title.location_id
				    AND restrictions_id = access_restrictions
				    AND eres_display = 'Y'
				    AND rank.title_id = title.title_id AND source.source_id = rank.source_id
				    ORDER BY title\n";
		}else
		{
			$lstrQuery .= " WHERE title.title_id = location_title.title_id
				    AND location.location_id = location_title.location_id
				    AND restrictions_id = access_restrictions
				    AND eres_display = 'Y'
				    AND rank.title_id = title.title_id AND source.source_id = rank.source_id
				    ORDER BY title\n";
		}

		if(isset($lobjParams['max']))
		{
			$lstrQuery .= " LIMIT 0,{$lobjParams['max']}";
		}

		return $lstrQuery;
	}
}

?>