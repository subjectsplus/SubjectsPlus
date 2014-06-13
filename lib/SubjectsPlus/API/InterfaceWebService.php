<?php
namespace SubjectsPlus\API;

/**
 * WebService - this is an interface describing the methods a web service needs to
 * implement to be a valid web service
 *
 * @package SubjectsPlus API
 * @author dgonzalez
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @date November 2012
 * @access public
 */
interface InterfaceWebService
{
	//construct must except defined parameters
	public function __construct($lobjUrlParams, $lobjDBConnector);

	//must implement this method to sanitize url parameters
	public function sanitizeParams(Array $lobjParams);

	//must implement this method to generate query that will return web service data
	public function generateQuery(Array $lobjParams);
}

?>