<?php
/**
 * Configuration file for CSRF Protector z
 */
return array(
	"CSRFP_TOKEN" => "45ac9813740caf796e81f1e95b0f807a69496af5",
	"noJs" => true,
	"logDirectory" => "../log",
	"failedAuthAction" => array(
		"GET" => 0,
		"POST" => 0),
	"errorRedirectionPage" => "",
	"customErrorMessage" => "",
	"jsPath" => "../js/csrfprotector.js",
	"jsUrl" => "http://localhost/test/csrf/js/csrfprotector.js",
	"tokenLength" => 10,
	"disabledJavascriptMessage" => "",
	 "verifyGetFor" => array()
);
