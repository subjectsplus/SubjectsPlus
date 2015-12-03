<?php

include("includes/config.php");

global $use_shibboleth;
global $shibboleth_logout;


// Initialize the session.
session_start();

// Unset all of the session variables.
$_SESSION = array();

// Also delete the session cookie.

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Finally, destroy the session.
session_destroy();

if ($use_shibboleth == TRUE) {
	header("Location:$shibboleth_logout");
} else {
	header("Location:login.php");	
}

?> 