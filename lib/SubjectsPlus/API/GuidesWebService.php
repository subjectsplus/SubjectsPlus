<?php
namespace SubjectsPlus\API;
use SubjectsPlus\Control\Querier;
/**
 * GuidesWebService - this class represents guides web service
 *
 * @package SubjectsPlus API
 * @author dgonzalez
 * @copyright Copyright (c) 2012
 * @date November 2012
 * @version $Id$
 * @access public
 */
/**
 * GuidesWebService
 *
 * @package
 * @author dgonzalez
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
/**
 * GuidesWebService
 *
 * @package
 * @author dgonzalez
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class GuidesWebService extends WebService implements InterfaceWebService
{
	/**
	 * GuidesWebService::__construct() - pass parameters to parent construct and
	 * set the service and tag properties
	 *
	 * @param array $lobjUrlParams
	 * @param DBConnector $lobjDBConnector
	 */
	function __construct($lobjUrlParams, $lobjDBConnector)
	{
		parent::__construct($lobjUrlParams, $lobjDBConnector);
		$this->mstrService = 'guides';
		$this->mstrTag = 'guide';
	}

	/**
	 * GuidesWebService::setData() - this method overrides the parent method because
	 * the guides webservice requires more queries to set all the data necessary for
	 * guides web service
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

		$this->mobjData[$this->mstrTag] = array();

		if(!$lobjResults)
		{
			return;
		}

		foreach($lobjResults as $lobjRow)
		{
			if(!empty($lobjRow))
			{
				array_push($this->mobjData[$this->mstrTag], $this->createGuideArray($lobjRow));
			}
		}
	}

	/**
	 * GuidesWebService::sanitizeParams() - goes through passed array parameter
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
				case 'subject_id':
					$lobjSplit = explode(',', $lstrValue);

					foreach($lobjSplit as &$lstrUnScrubbed)
					{
						$lstrUnScrubbed = scrubData($lstrUnScrubbed, 'integer');
					}

					$lobjFinalParams['subject_id'] = $lobjSplit;
					break;
				case 'shortform':
					$lobjSplit = explode(',', $lstrValue);

					foreach($lobjSplit as &$lstrUnScrubbed)
					{
						$lstrUnScrubbed = scrubData($lstrUnScrubbed);
					}

					$lobjFinalParams['shortform'] = $lobjSplit;
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
	 * GuidesWebService::generateQuery() - returns a generated query based on all
	 * url parameters that will return desired talkback records
	 *
	 * @param array $lobjParams
	 * @return string
	 */
	function generateQuery(Array $lobjParams)
	{
		$lstrQuery = 'SELECT subject_id, subject as \'title\', description, keywords, shortform
					FROM subject';

		$lobjConditions = array();

		foreach($lobjParams as $lstrKey => $lobjValues)
		{
			switch($lstrKey)
			{
				case 'subject_id':
					$lobjCondition = array();

					foreach ($lobjValues as $lintSubjectID)
					{
						array_push($lobjCondition, "subject_id = '$lintSubjectID'\n");
					}

					$lstrCombine = implode(' OR ', $lobjCondition);

					array_push($lobjConditions, $lstrCombine);
					break;
				case 'shortform':
					$lobjCondition = array();

					foreach ($lobjValues as $lstrShortForm)
					{
						array_push($lobjCondition, "shortform = '$lstrShortForm'\n");
					}

					$lstrCombine = implode(' OR ', $lobjCondition);

					array_push($lobjConditions, $lstrCombine);
					break;
				case 'type':
					$lobjCondition = array();

					foreach ($lobjValues as $lstrType)
					{
						array_push($lobjCondition, "type = '$lstrType'\n");
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

		$lstrQuery .= "\nORDER BY subject\n";

		if(isset($lobjParams['max']))
		{
			$lstrQuery .= " LIMIT 0,{$lobjParams['max']}";
		}

		return $lstrQuery;
	}

	/**
	 * GuidesWebService::createGuideArray() - creates a guide array based on passed
	 * row array (a SQL row) by setting element keys and elements that require another
	 * SQL query
	 *
	 * @param array $lobjRow
	 * @return array
	 */
	protected function createGuideArray(Array $lobjRow)
	{
		$lobjGuide = array();

		$lobjGuide['title'] = $lobjRow['title'];
		$lobjGuide['description'] = $lobjRow['description'];
		$lobjGuide['authors'] = array();
		$lobjGuide['authors']['author'] = $this->getAuthors(intval($lobjRow['subject_id']));

		$lobjGuide['disciplines'] = array();
		$lobjGuide['disciplines']['discipline'] = $this->getDisciplines( 'subject', intval($lobjRow['subject_id']));

		$lobjGuide['url'] = PATH_TO_SP . 'subjects/guide.php?subject=' . $lobjRow['shortform'];
		$lobjGuide['date_created'] = $this->getDateCreated(intval($lobjRow['subject_id']));
		$lobjGuide['last_modified'] = $this->getDateModified(intval($lobjRow['subject_id']));

		if(strstr($lobjRow['keywords'], ',') === false)
		{
			$lobjGuide['keywords'] = array();
		}else{
			$lobjKeywords['keyword'] = explode(',', $lobjRow['keywords']);
			$lobjGuide['keywords'] = $lobjKeywords;
		}

		return $lobjGuide;
	}

	/**
	 * GuidesWebService::getAuthors() - gets all authors associated with passed subject
	 * id and returns an array of author arrays
	 *
	 * @param int $lintSubjectID
	 * @return array
	 */
	protected function getAuthors($lintSubjectID)
	{
		$lstrQuery = "SELECT  s.staff_id, CONCAT(fname, ' ', lname) as 'name', tel, email, title
					FROM staff as s
					INNER JOIN staff_subject as ss
					ON s.staff_id = ss.staff_id
					WHERE ss.subject_id = $lintSubjectID";

		$lobjQuerier = new Querier();

		$lobjResults = $lobjQuerier->query($lstrQuery, \PDO::FETCH_ASSOC);

		if(!$lobjResults)
		{
			return array();
		}

		$lobjAuthors = array();

		foreach($lobjResults as $lobjRow)
		{
			$lobjAuthor = array();

			$lobjAuthor['name'] = $lobjRow['name'];

			global $tel_prefix;

			if(isset($tel_prefix))
			{
				$lobjAuthor['tel'] = $tel_prefix . $lobjRow['tel'];
			}else{
				$lobjAuthor['tel'] = $lobjRow['tel'];
			}

			$lobjAuthor['email'] = $lobjRow['email'];

			$lobjSplit = explode('@', $lobjAuthor['email']);

			$lstrUser = $lobjSplit[0];

			$lobjAuthor['photo'] = PATH_TO_SP . 'assets/users/_' . $lstrUser . '/headshot.jpg';

			$lobjAuthor['title'] = $lobjRow['title'];

			$lobjAuthor['disciplines'] = array();
			$lobjAuthor['disciplines']['discipline'] = $this->getDisciplines( 'author', intval($lobjRow['staff_id']));

			array_push($lobjAuthors, $lobjAuthor);
		}

		return $lobjAuthors;
	}

	/**
	 * GuidesWebService::getDiscplines() - gets all disciplines associated with passed flag ( 'subject' or 'author')
	 * id and returns an array of disciplines arrays
	 *
	 * @param string $lstrFlag
	 * @param int $lintID
	 * @return array
	 */
	protected function getDisciplines( $lstrFlag, $lintID )
	{
		switch($lstrFlag)
		{
			case 'subject':
				$lstrQuery = "SELECT `discipline`
						FROM `subject` AS s INNER JOIN `subject_discipline` AS sd
						ON s.subject_id = sd.subject_id
						INNER JOIN `discipline` AS d
						ON sd.discipline_id = d.discipline_id
						WHERE s.subject_id = $lintID";
				break;
			case 'author':
			$lstrQuery = "SELECT `discipline`
							FROM `staff` AS s INNER JOIN `staff_subject` AS ss
							ON s.`staff_id` = ss.`staff_id`
							INNER JOIN `subject` AS su
							ON ss.`subject_id` = su.`subject_id`
							INNER JOIN `subject_discipline` AS sd
							ON su.`subject_id` = sd.`subject_id`
							INNER JOIN `discipline` AS d
							ON sd.`discipline_id` = d.`discipline_id`
							WHERE s.`staff_id` = $lintID";
				break;
			default:
				return array();
		}

		$lobjQuerier = new Querier();

		$lobjResults = $lobjQuerier->query($lstrQuery, \PDO::FETCH_ASSOC);

		if(!$lobjResults)
		{
			return array();
		}

		$lobjDiscipline = array();

		foreach($lobjResults as $lobjRow)
		{
			array_push($lobjDiscipline, $lobjRow['discipline']);
		}

		$lobjDiscipline = array_unique($lobjDiscipline);

		return $lobjDiscipline;
	}

	/**
	 * GuidesWebService::getDateCreated() - gets the date the passed subject id
	 * was created
	 *
	 * @param int $lintSubjectID
	 * @return string
	 */
	protected function getDateCreated($lintSubjectID)
	{
		$lstrQuery = "SELECT c.date_added
					FROM chchchanges as c
					WHERE c.record_id = $lintSubjectID
					AND c.ourtable = 'guide'
					AND c.message LIKE '%insert%'";

		$lobjQuerier = new Querier();

		$lobjResults = $lobjQuerier->query($lstrQuery, \PDO::FETCH_ASSOC);

		if(!$lobjResults)
		{
			return '';
		}

		return $lobjResults[0]['date_added'];
	}

	/**
	 * GuidesWebService::getDateModified() - gets the date the passed subject_id
	 * was last modified
	 *
	 * @param int $lintSubjectID
	 * @return string
	 */
	protected function getDateModified($lintSubjectID)
	{
		$lstrQuery = "SELECT c.date_added
					FROM chchchanges as c
					WHERE c.record_id = $lintSubjectID
					AND c.ourtable = 'guide'
					ORDER BY date_added DESC
					LIMIT 1";

		$lobjQuerier = new Querier();

		$lobjResults = $lobjQuerier->query($lstrQuery, \PDO::FETCH_ASSOC);

		if(!$lobjResults)
		{
			return '';
		}

		return $lobjResults[0]['date_added'];
	}
}

?>