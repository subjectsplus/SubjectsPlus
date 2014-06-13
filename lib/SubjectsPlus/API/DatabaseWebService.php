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
					eres_display, display_note, pre, citation_guide, ctags, helpguide
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