<div id="subjectSpecialistPluslet" class="pure-g">


    <?php if($this->staffPhoto != "off") {
        echo  "<div class=\"pure-u-1-2\"><span id='staffPhoto'>{$this->staffPhoto}</span></div>
               <div class=\"pure-u-1-2\">";
    }
    else {
        echo  "<div class=\"pure-u-1\">";
    }
    ?>

    
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
        <?php if($this->staffFacebook) {

            echo "<li><a href='http://facebook.com/{$this->staffFacebook}'><i class='fa fa-facebook-square fa-3x'></i></a></li>";
        } ?>

        <?php if($this->staffTwitter) {

            echo "<li><a href='http://twitter.com/{$this->staffTwitter}'><i class='fa fa-twitter-square fa-3x'></i></a></li>";
        } ?>

        <?php if($this->staffPinterest) {

            echo "<li><a href='http://pinterest.com/{$this->staffPinterest}'><i class='fa fa-pinterest-square fa-3x'></i></a></li>";
        } ?>

    </ul>


</div>