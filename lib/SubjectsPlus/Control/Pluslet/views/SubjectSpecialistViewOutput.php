<div id="subjectSpecialistPluslet">

    <?php if($this->staffPhoto != "off") {
        echo  "<span id='staffPhoto'>{$this->staffPhoto}</span>";
    } ?>



    <ul>
        <li><?php echo $this->staffName; ?></li>

        <?php if($this->staffTitle != "off") {
            echo "<li>".$this->staffTitle."</li>";
        } ?>

        <?php if($this->staffEmail != "off") {
            echo "<li><a href='mailto:{$this->staffEmail}'>{$this->staffEmail}</a></li>";
        } ?>

        <?php if($this->staffPhone != "off") {
            echo "<li>{$this->staffPhone}</li>";
        } ?>

    </ul>
    <ul>
        <li><a href='http://facebook.com/<?php echo $this->staffFacebook; ?>'><i class='fa fa-facebook-square fa-3x'></i></a></li>
        <li><a href='http://twitter.com/<?php echo $this->staffTwitter; ?>'><i class='fa fa-twitter-square fa-3x'></i></a></li>
        <li><a href='http://pinterest.com/<?php echo $this->staffPinterest; ?>'><i class='fa fa-pinterest-square fa-3x'></i></a></li>
    </ul>


</div>