<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 7/7/2017
 * Time: 11:56 AM
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multiple Guides</title>
</head>
<body>
<h3>Hi!</h3>
<hr>
<p>We think the following guides might be of interest</p>
<ul>
    <?php foreach($results as $guide_title => $guide_link): ?>
        <li>
            <a target="_blank" href="<?php echo $guide_link;?>"><?php echo $guide_title;?></a>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>