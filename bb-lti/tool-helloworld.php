<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

require_once '../lib/ims-blti/blti.php';
$lti = new BLTI("abel", false, false);

session_start();
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Building Tools With The Learning Tools Operability Specification</title>
</head>

<body>

<?php
if ($lti->valid) {
?>
<h2>Hello, World!</h2>
<p>We have implemented a basic LTI tool!</p>
<h3>A basic dump of POST parameters:</h3>
<pre>
 <?php
 foreach($_POST as $key => $value) {
     print "$key=$value\n";
 }
 ?>
     </pre>

    <?php
} else {
    ?>
    <h2>This was not a valid LTI launch</h2>
    <p>Error message: <?= $lti->message ?></p>
    <?php
}
?>
</body>

</html>