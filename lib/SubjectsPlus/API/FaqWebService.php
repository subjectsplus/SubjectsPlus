<?php
namespace SubjectsPlus\API;
/**
 * FaqWebService - this class represents the faq web service
 *
 * @package SubjectsPlus API
 * @author dgonzalez
 * @copyright Copyright (c) 2012
 * @date November 2012
 * @version $Id$
 * @access public
 */
class FaqWebService extends WebService implements InterfaceWebService
{
	/**
	 * FaqWebService::__construct() - pass parameters to parent construct and
	 * set the service and tag properties
	 *
	 * @param array $lobjUrlParams
	 * @param sp_DBConnector $lobjDBConnector
	 */
	function __construct($lobjUrlParams, $lobjDBConnector)
	{
		//need to implement! will echo error message!
		header("HTTP/1.1 501 No Implementation");
		die("Sorry. FAQ service is not available yet. Check back later.");

		parent::__construct($lobjUrlParams, $lobjDBConnector);
		$this->mstrService = 'faqs';
		$this->mstrTag = 'faq';
	}

	/**
	 * FaqWebService::sanitizeParams() - goes through passed array parameter
	 * and sanitizes elements that are valid url parameters
	 *
	 * @param array $lobjParams
	 * @return array
	 */
	function sanitizeParams(Array $lobjParams)
	{
		$lobjFinalParams = array();

		//need to implement! will return empty!
		return $lobjFinalParams;

		foreach($lobjParams as $lstrKey => $lstrValue)
		{
			switch(strtolower($lstrKey))
			{
				case 'department':
					$lobjSplit = explode(',', $lstrValue);

					foreach($lobjSplit as &$lstrUnScrubbed)
					{
						$lstrUnScrubbed = scrubData($lstrUnScrubbed, 'integer');
					}

					$lobjFinalParams['department'] = $lobjSplit;
					break;
				case 'email':
					$lobjSplit = explode(',', $lstrValue);

					foreach($lobjSplit as &$lstrUnScrubbed)
					{
						$lstrUnScrubbed = scrubData($lstrUnScrubbed);
					}

					$lobjFinalParams['email'] = $lobjSplit;
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
	 * FaqWebService::generateQuery() - returns a generated query based on all
	 * url parameters that will return desired faq records
	 *
	 * @param mixed $lobjParams
	 * @return
	 */
	function generateQuery(Array $lobjParams)
	{
		$lstrQuery = '';

		//need to implement! will return blank!
		return $lstrQuery;

		$lstrQuery = 'SELECT lname, fname, title, tel, email, bio
					FROM staff';

		$lobjConditions = array();

		foreach($lobjParams as $lstrKey => $lobjValues)
		{
			switch($lstrKey)
			{
				case 'department':
					$lobjCondition = array();

					foreach ($lobjValues as $lintDepartmentID)
					{
						array_push($lobjCondition, "department_id = '$lintDepartmentID'\n");
					}

					$lstrCombine = implode(' OR ', $lobjCondition);

					array_push($lobjConditions, $lstrCombine);
					break;
				case 'email':
					$lobjCondition = array();

					foreach ($lobjValues as $lstrEmail)
					{
						array_push($lobjCondition, "email = '$lstrEmail'\n");
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
		}

		if(isset($lobjParams['max']))
		{
			$lstrQuery .= " LIMIT 0,{$lobjParams['max']}";
		}

		return $lstrQuery;
	}
}


?>