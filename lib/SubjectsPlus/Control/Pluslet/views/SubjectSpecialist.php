<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 8/17/16
 * Time: 4:29 PM
 */
?>


<div class="subject-specialist-content" data-pluslet-id="<?php echo $this->_pluslet_id; ?>">
<?php
    $settings = array();
    foreach($this->_editors as $staff):
        $settings = $this->getSubjectSpeicalistSettings($staff, $this->_array_keys, $this->_data_array);
        $this->setSubjectSpecialist($settings);
?>

    <div class="subject-specialists" data-staff-id="<?php echo $this->staff_id; ?>">
        <div class="specialist-photo" data-show-photo="<?php echo $this->showPhoto; ?>" ><img src="<?php echo $this->img_url;?>" /> </div>
        <div class="specialist-info show-photo-full">
            <h4 data-show-name="<?php echo $this->showName; ?>"><?php echo $this->fullname; ?></h4>
            <ul class="staff-details">
                <li data-show-title="<?php echo $this->showTitle; ?>"><?php echo $this->title; ?></li>
                <li data-show-email="<?php echo $this->showEmail; ?>"><a href="mailto:<?php echo $this->email; ?>"><?php echo $this->email; ?></a></li>
                <li data-show-phone="<?php echo $this->showPhone; ?>"><?php echo $this->tel_prefix; ?> <?php echo $this->tel; ?></li>
                <li>
                    <div class="staff-social" data-show-facebook="<?php echo $this->showFacebook; ?>">
                        <a href="https://facebook.com/<?php echo $this->facebook; ?>"><i class="fa fa-facebook"></i></a>
                    </div>
                    <div class="staff-social" data-show-twitter="<?php echo $this->showTwitter ; ?>">
                        <a href="https://twitter.com/<?php echo $this->twitter; ?>"><i class="fa fa-twitter"></i></a>
                    </div>
                    <div class="staff-social" data-show-pinterest="<?php echo $this->showPinterest; ?>">
                        <a href="https://pinterest.com/<?php echo $this->pinterest; ?>"><i class="fa fa-pinterest"></i></a>
                    </div>
                    <div class="staff-social" data-show-instagram="<?php echo $this->showInstagram; ?>">
                        <a href="https://instagram.com/<?php echo $this->instagram; ?>"><i class="fa fa-instagram"></i></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <?php endforeach; ?>


    <div class="subject-specialist-description">
        <?php echo $this->_body; ?>
    </div>
</div>



<script>
    if(typeof subjectSpecialist == 'function' ) {
        var ss = subjectSpecialist();
        ss.init();
    }
</script>