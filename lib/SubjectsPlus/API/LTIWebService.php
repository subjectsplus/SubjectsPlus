<?php
namespace SubjectsPlus\API;

/**
 * LTIWebService - this class represents the LTI web service
 *
 * @package SubjectsPlus API
 * @author acarrasco
 * @copyright Copyright (c) 2019
 * @date August 2019
 * @version 1.0
 * @access public
 */

class LTIWebService extends WebService implements InterfaceWebService {

	/**
	 * LTIWebService::__construct() - pass parameters to parent construct and
	 * set the service and tag properties
	 *
	 * @param array $lobjUrlParams
	 * @param DBConnector $lobjDBConnector
	 */
	function __construct($lobjUrlParams, $lobjDBConnector)
	{
		parent::__construct($lobjUrlParams, $lobjDBConnector);
		$this->mstrService = 'lti';
		$this->mstrTag = 'lti';
	}

	public function sanitizeParams( array $lobjParams ) {
		$lobjFinalParams = array();

		foreach($lobjParams as $lstrKey => $lstrValue)
		{
			switch(strtolower($lstrKey))
			{
				case 'get':
					$lobjSplit = explode(',', $lstrValue);

					foreach($lobjSplit as &$lstrUnScrubbed)
					{
						$lstrUnScrubbed = scrubData($lstrUnScrubbed);
					}

					$lobjFinalParams['get'] = $lobjSplit;
					break;
			}
		}

		return $lobjFinalParams;
	}

	public function generateQuery( array $lobjParams ) {
		// TODO: Implement generateQuery() method.
	}
}