<div id="subjectSpecialistPluslet">

    <?php

    foreach ($this->staffArray as $value) {

        // get username from email
        $truncated_email = explode("@", $value[2]);

        $staff_picture = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";

        // Output Picture and Contact Info
        $this->_body .= "
        <div class=\"clearboth\"><img src=\"$staff_picture\" alt=\"Picture: $value[1] $value[0]\"  class=\"staff_photo2\" align=\"left\" />
        <p><a href=\"mailto:$value[2]\">$value[1] $value[0]</a><br />$value[4]<br />
        Tel: $this->tel_prefix $value[3]</p>\n</div>\n";
    }



    ?>



</div>