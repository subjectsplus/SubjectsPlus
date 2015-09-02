<div id="subjectSpecialistPluslet" class="subjectSpecialistPluslet">


    <?php if($this->staffPhoto != "off") {
        echo  "<div class=\"staff-image\"><img id='staffPhoto' class=\"staff-photo\" src='{$this->staffPhoto}' /></div>
               <div class=\"staff-content\">";
    }
    else {
        echo  "<div>";
    }
    ?>


    <ul class="staff-details">
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

    <ul class="staff-social">
        <?php if($this->staffFacebook != "") {

            echo "<li><a href='http://facebook.com/{$this->staffFacebook}'><i class='fa fa-facebook-square'></i></a></li>";
        } ?>

        <?php if($this->staffTwitter != "") {

            echo "<li><a href='http://twitter.com/{$this->staffTwitter}'><i class='fa fa-twitter-square'></i></a></li>";
        } ?>

        <?php if($this->staffPinterest != "") {

            echo "<li><a href='http://pinterest.com/{$this->staffPinterest}'><i class='fa fa-pinterest-square'></i></a></li>";
        } ?>

    </ul>

    </div>


</div>