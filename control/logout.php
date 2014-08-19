<?php

include("includes/config.php");

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

header("Location:login.php");
?> 