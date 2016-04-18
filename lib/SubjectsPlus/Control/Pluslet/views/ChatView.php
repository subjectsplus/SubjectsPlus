<?php
if( (empty($this->getJid())) || (empty($this->getSrc())) ) {

    echo "<p>Please have your SubjectsPlus Admin add the settings for your institution's Chat account.</p>";
    echo "<p>The file is located at /lib/SubjectsPlus/Control/Pluslet/Chat.php</p>";
} else { ?>

    <div jid="<?php echo $this->getJid(); ?>" class="libraryh3lp" >
        <iframe
            src="<?php echo $this->getSrc(); ?>"
            frameborder="0"
            style="width: <?php echo $this->getWidth(); ?>;
                min-height: <?php echo $this->getHeight(); ?>;">
        </iframe>
    </div>

<?php } ?>


