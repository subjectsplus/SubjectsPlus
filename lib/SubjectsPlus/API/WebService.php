<?php
namespace SubjectsPlus\API;
use SubjectsPlus\Control\Querier;
/**
 * sp_WebService - this class is the parent of all web services. Contains web service
 * properties and general methods that the children can use or override if necessary.
 *
 * @package SubjectsPlus API
 * @author dgonzalez
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @date November 2012
 * @access public
 */
abstract class WebService
{
	protected $mobjData = array();
	protected $mobjUrlParams = array();
	protected $mstrService = '';
	protected $mstrTag = '';
	protected $mstrFormat = '';
	protected $mstrOutput = '';
	protected $mobjDBConnector = null;

	/**
	 * sp_WebService::__construct() - method to set url parameters and db connector
	 *
	 * @param array $lobjUrlParams
	 * @param DBConnector $lobjDBConnector
	 */
	function __construct($lobjUrlParams, $lobjDBConnector)
	{
		$this->mobjUrlParams = $lobjUrlParams;
		$this->mobjDBConnector = $lobjDBConnector;
	}

	/**
	 * sp_WebService::setData() - this method sets the data that will be outputted for
	 * the web service with a get method
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

		$this->mobjData[$this->mstrTag] = $lobjResults;
	}

	/**
	 * sp_WebService::formatOutput() - saves formatted output into property based
	 * on format requested in url parameters
	 *
	 * @return void
	 */
	public function formatOutput()
	{
		$lstrFormat = '';

		if(isset($this->mobjUrlParams['output']))
		{
			$lstrFormat = $this->mobjUrlParams['output'];
		}

		$this->mstrFormat = scrubData(strtolower($lstrFormat));

		switch($this->mstrFormat)
		{
			case "xml":
				$this->mstrOutput = "<{$this->mstrService}>";
				$this->mstrOutput .= $this->getXMLFormat($this->mobjData);
				$this->mstrOutput .= "</{$this->mstrService}>";
				break;
			case "json":
				$this->mstrOutput = $this->getJSONFormat();
				break;
			default:
				$this->mstrOutput = $this->getJSONFormat();
				break;
		}
	}

	/**
	 * sp_WebService::getXMLFormat() - goes through array and generates a valid xml
	 * string based on passed tag and keys in array. This is a recursive function
	 *
	 * @param array $lobjData
	 * @param string $lstrTag
	 * @return string
	 */
	static public function getXMLFormat($lobjData, $lstrTag = '')
	{
		$xml = '';

		//if the passed data array is empty
		if(empty($lobjData))
		{
			//if there is a tag for the empty data array, make an empty XML element with given tag
			if($lstrTag != '')
			{
				return "<$lstrTag></$lstrTag>";
			}else
			{
				//return an empty string if there is no tag
				return '';
			}
		}

		//go through the data array and get the key and value of each element
		foreach ($lobjData as $key => $value)
		{
			//if the key is a string, use as XML tag
			if(is_string($key))
			{
				//if the value is an arry, wrap the array converted to XML in the
				//XML tag that was passed to function if not blank
				if (is_array($value))
				{
					//open XML tag
					if($lstrTag != '')
					{
						$xml .= "<$lstrTag>";
					}

					//convert the value array with the given key of the element
					$xml .= self::getXMLFormat($value, $key);

					//close XML tag
					if($lstrTag != '')
					{
						$xml .= "</$lstrTag>";
					}
				}else
				{
					//if value is not an array, enclose the scrubbed value in the
					//array key as a XML tag
					$xml .= "<$key>" . self::prepareXMLValue($value) . "</$key>";
				}
			}else{
				//if the key is not a string, assumed it is a regular array with
				//integer indexed array

				//add function passed lstrTag as XML tag
				$xml .= "<$lstrTag>";

				//convert value of element either as array to XML or scrubbed string
				if (is_array($value))
				{
					$xml .= self::getXMLFormat($value);
				}else
				{
					$xml .= self::prepareXMLValue($value);
				}

				//close XML tag
				$xml .= "</$lstrTag>";
			}
	  	}

		//return new formatted xml string
	    return $xml;
	}

	/**
	 * sp_WebService::prepareValue() - checks if passed value needs to be contained
	 * inside a CDATA tag and then decodes any html special chars.
	 *
	 * @param string $lstrValue
	 * @return string
	 */
	static public function prepareXMLValue($lstrValue)
	{
		$lstrValue = str_ireplace('&nbsp;', ' ', $lstrValue);
		$lstrOutput = '';

		if(strstr($lstrValue,'&') || strstr($lstrValue, '<') || strstr($lstrValue, '>'))
		{
			$lstrOutput .= '<![CDATA[';
			$lstrOutput .= htmlspecialchars_decode(trim($lstrValue), ENT_QUOTES);
			$lstrOutput .= ']]>';
		}else{
			$lstrOutput = htmlspecialchars_decode(trim($lstrValue), ENT_QUOTES);
		}

		return $lstrOutput;
	}

	/**
	 * sp_WebService::getJSONFormat() - uses json encode to convert data property
	 * array to a json encoded string
	 *
	 * @return string
	 */
	protected function getJSONFormat()
	{
		return json_encode($this->mobjData);
	}

	/**
	 * sp_WebService::getOutput() - returns private property 'output'
	 *
	 * @return string
	 */
	public function getOutput()
	{
		return $this->mstrOutput;
	}

	/**
	 * sp_WebService::getFormat() - returns private property 'format'
	 *
	 * @return string
	 */
	public function getFormat()
	{
		return $this->mstrFormat;
	}
}

?>