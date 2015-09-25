<div class="subjectSpecialistPluslet">
    <div id="subjectSpecialistPluslet" class="pure-g">



    <?php if ( isset($this->staffPhoto) ) {
        echo  "<div class=\"staff-image\"><img id='staffPhoto{$this->staffId}' class=\"staff-photo\" src='{$this->staffPhoto}' /></div>
               <div class=\"staff-content\">";
    }
    else {
        echo  "<div>";
    }
    ?>


    <ul class="staff-details">
        <?php if( isset($this->staffName) ) {
            echo "<li>".$this->staffName."</li>";
        } ?>


        <?php if( isset($this->staffTitle) ) {
            echo "<li>".$this->staffTitle."</li>";
        } ?>

        <?php if( isset($this->staffEmail) ) {
            echo "<li><a href='mailto:{$this->staffEmail}'>{$this->staffEmail}</a></li>";
        } ?>

        <?php if( isset($this->staffPhone) ) {
            echo "<li>{$this->staffPhone}</li>";
        } ?>

    </ul>

    <ul class="staff-social">
        <?php if( isset($this->staffFacebook) ) {

            echo "<li><a href='http://facebook.com/{$this->staffFacebook}'><i class='fa fa-facebook-square'></i></a></li>";
        } ?>

        <?php if( isset($this->staffTwitter) ) {

            echo "<li><a href='http://twitter.com/{$this->staffTwitter}'><i class='fa fa-twitter-square'></i></a></li>";
        } ?>

        <?php if( isset($this->staffPinterest) ) {

            echo "<li><a href='http://pinterest.com/{$this->staffPinterest}'><i class='fa fa-pinterest-square'></i></a></li>";
        } ?>

        <?php if( isset($this->staffInstagram) ) {

            echo "<li><a href='http://instagram.com/{$this->staffInstagram}'><i class='fa fa-instagram'></i></a></li>";
        } ?>
    </ul>

</div>

</div>