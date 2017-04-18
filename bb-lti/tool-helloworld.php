<?php
header('X-Frame-Options: ALLOW-FROM http://localhost:9876');
header("Content-Security-Policy: http://localhost:9876");
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
//Where the magic happens!
if ($lti->valid) {
    $course_id = $_REQUEST["context_label"];

    if (strcmp($course_id, '123456') == 0){
        header("Location: http://sp.library.miami.edu/subjects/guide.php?subject=WomensGenderStudies"); /* Redirect browser */
        exit();
    }else{
        header("Location: http://sp.library.miami.edu/subjects"); /* Redirect browser */
        exit();
    }

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
    header("Location: https://library.miami.edu"); /* Redirect browser */
    exit();
}
?>
</body>

</html>