<?php
namespace SubjectsPlus\API;
use SubjectsPlus\Control\Querier;

/**
 * sp_WebService - This class handles all web services using RESTful
 *
 * @package SubjectsPlus API
 * @author dgonzalez
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @date November 2012
 * @access public
 */

class WebServiceHandler
{
	protected $mstrMethod = '';
	protected $mstrService = '';
	protected $mobjUrlParams = array();
	protected $mobjDBConnector = null;
	protected $mobjWebService = null;

	/**
	 * sp_WebServiceHandler::__construct() - setup properties
	 *
	 * @param string $uname
	 * @param string $pword
	 * @param string $dbName_SPlus
	 * @param string $hname
	 */
	function __construct($uname, $pword, $dbName_SPlus, $hname)
	{
		$this->mstrMethod = $_SERVER['REQUEST_METHOD'];

		$lobjTemp = explode('/', $_SERVER['REQUEST_URI']);

		for($i = (count($lobjTemp) - 1); $i >= 0; $i--)
		{
			if(strtolower($lobjTemp[$i]) == 'api')
			{
				$this->mstrService = $lobjTemp[$i + 1];

				$lobjTemp = array_slice(explode('/', $_SERVER['REQUEST_URI']), $i + 2);

				break;
			}
		}

		for($i = 0; $i < count($lobjTemp); $i = $i + 2)
		{
			if(isset($lobjTemp[$i + 1]))
			{
				$this->mobjUrlParams[strtolower($lobjTemp[$i])] = urldecode($lobjTemp[$i + 1]);
			}
		}

		try {
			$this->mobjDBConnector = new Querier();
		} catch (Exception $e) {
			die($e);
		}
	}

	/**
	 * sp_WebServiceHandler::doService() - execute web service, whether get, post, put, or delete
	 *
	 * This method determines what service is required and if service is not supported,
	 * documentation is provided.
	 *
	 * @return void
	 */
	public function doService()
	{
		global $api_enabled;
		global $api_key;

		if( !isset( $api_enabled ) || $api_enabled === FALSE ) exit;

		if($this->mstrService == '' )
		{
			$this->displayDocumentation();
			exit;
		}

		if( ( !isset( $this->mobjUrlParams['key'] ) || !isset( $api_key ) || $this->mobjUrlParams['key'] != $api_key ) )
		{
			$this->displayDocumentation( TRUE );
			exit;
		}

		$lstrClass = ucwords($this->mstrService). 'WebService';

		if(file_exists(dirname(__FILE__) . '/' . $lstrClass . '.php'))
		{
			$lstrClass = "SubjectsPlus\\API\\" . $lstrClass;
			$lobjWebService = new $lstrClass($this->mobjUrlParams, $this->mobjDBConnector);
		}else{
			$this->displayDocumentation(true);
			exit;
		}

		switch($this->mstrMethod)
		{
			case "GET":
				$lobjWebService->setData();
				$lobjWebService->formatOutput();
				$this->mobjWebService = $lobjWebService;
				break;
			default:
				echo "only GET Request Supported";
				exit;
		}
	}

	/**
	 * sp_WebServiceHandler::displayOutput() - echo outs web service output and
	 * determine appropriate header for the format type
	 *
	 * @return void
	 */
	public function displayOutput()
	{
		$lstrFormat = $this->mobjWebService->getFormat();

		switch($lstrFormat)
		{
			case "xml":
				header('Content-type: text/xml');
				echo $this->mobjWebService->getOutput();
				break;
			case "json":
				header('Content-type: application/json');
				echo $this->mobjWebService->getOutput();
				break;
			default:
				header('Content-type: application/json');
				echo $this->mobjWebService->getOutput();
				break;
		}
	}

	/**
	 * sp_WebServiceHandler::displayDocumentation() - display documentation for web service
	 * and depending on parameter, if http code for bad request is added.
	 *
	 * @param boolean $lboolBadRequest
	 * @return void
	 */
	public function displayDocumentation($lboolBadRequest = false)
	{
		if($lboolBadRequest)
		{
			header("HTTP/1.1 400 Bad Request");

			print "<h1>Bad Request! Here's how you use this thing.</h1>\n";
		}else
		{
			print "<h1>No service selected.  Here's how you use this thing.</h1>";
		}

		print "<pre><strong>You need to send the API security key in order for the API to work.</strong>";
		print "\nThe key can be viewed in the 'Config Site' page, under the Admin tab. (REMEMBER that security key might need to be url encoded to be passed)</pre>";
		print "<pre>You Can Query Like This:\n/sp/api/service/parameter-name/parameter-value/key/api-key\n\n";
		print "Results can be returned as xml or json (default).  E.g.:\nsp/api/staff/output/xml/key/api-key\n\n";
		print "staff\n  * enter email address to return results.  Separate multiple addresses with commas.  Examples:\n";
		print "  sp/api/staff/email/you@miami.edu/key/api-key\n  sp/api/staff/email/you@miami.edu,me@miami.edu/key/api-key\n";
		print "  * select a department by id\n  sp/api/staff/department/99/key/api-key\n  * set a limit\n  sp/api/staff/department/99/max/5/key/api-key\n\n";
		print "talkback\n  * show all talkbacks submitted between start date and end date\n  sp/api/talkback/startdate/2013-04-01/enddate/2013-04-30/key/api-key\n";
		print "  * show all talkbacks submitted on one date\n  sp/api/talkback/startdate/2013-04-01/key/api-key\n";
		print "  * enter max number of returns\n  sp/api/talkback/max/10/key/api-key\n\ndatabase\n  * Lots of options:\n";
		print "  sp/api/database/letter/A/key/api-key -- show items beginning with A\n  sp/api/database/letter/Num -- show numbers\n";
		print "  sp/api/database/search/Science/key/api-key -- show all items with Science in title\n";
		print "  sp/api/database/subject_id/10/key/api-key -- show all databases associated with that subject id\n";
		print "  sp/api/database/type/Reference/key/api-key -- show all items with that ctag\n\n";
		print "  * enter max number of returns\n  sp/api/database/type/Reference/max/10/key/api-key";
		print "\n\nguides\n* Lots of options:\n";
		print "  sp/api/guides/subject_id/22/key/api-key -- show all guides associated with that subject id\n";
		print "  sp/api/guides/shortform/Nursing/key/api-key -- show all guides associated with that shortform\n";
		print "  sp/api/guides/type/Subject/key/api-key -- show all guides of that type\n";
		print "\n  * enter max number of returns\n  sp/api/guides/type/Subject/max/10/key/api-key\n\nfaq\n  * coming soon";
		print "\n\n\n  * If web service is not working correctly, the most common problem is that the .htaccess file has the wrong 'RewriteBase' path.";
		print "\n    It should reflect the path that is after your websites url. E.g. if you have www.mywebsite.com/dir1/sp/api then .htaccess file should have 'RewriteBase' path of /dir1/sp/api/";
		print "</pre>";
	}
}

?>