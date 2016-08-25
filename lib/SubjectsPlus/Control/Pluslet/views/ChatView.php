<?php
if( (!empty($this->flashMessage)) ) {
    //something went wrong
    echo $this->flashMessage;
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


