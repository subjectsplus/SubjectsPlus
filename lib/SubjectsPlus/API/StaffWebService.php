<?php
namespace SubjectsPlus\API;
use SubjectsPlus\Control\Querier;

class StaffWebService extends WebService implements InterfaceWebService
{
	/**
	 * StaffWebService::__construct() - pass parameters to parent construct and
	 * set the service and tag properties
	 *
	 * @param array $lobjUrlParams
	 * @param DBConnector $lobjDBConnector
	 */
	function __construct($lobjUrlParams, $lobjDBConnector)
	{
		parent::__construct($lobjUrlParams, $lobjDBConnector);
		$this->mstrService = 'staff';
		$this->mstrTag = 'staff-member';
	}

	/**
	 * StaffWebService::setData() - this method overrides the parent method because
	 * the staff webservice requires an append to the tel field
	 *
	 * @return void
	 */
	public function setData()
	{
		$lobjParams = $this->mobjUrlParams;

		$lobjParams = $this->sanitizeParams($lobjParams);

		if($lobjParams === false)
		{
			die;
		}

		$lstrQuery = $this->generateQuery($lobjParams) or die;

		$lobjQuerier = new Querier();

		$lobjResults = $lobjQuerier->query($lstrQuery, \PDO::FETCH_ASSOC);

		if(!$lobjResults) $lobjResults = array();

		global $tel_prefix;

		foreach($lobjResults as &$lobjRow)
		{
			if(isset($tel_prefix))
			{
				$lobjRow['tel'] = $tel_prefix . $lobjRow['tel'];
			}
		}

		$this->mobjData[$this->mstrTag] = $lobjResults;
	}

	function sanitizeParams(Array $lobjParams)
	{
		$lobjFinalParams = array();

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

	function generateQuery(Array $lobjParams)
	{
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