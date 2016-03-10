<div class="card">
    <div class="card-image">
        <?php echo $this->_body; ?>

        <?php if(!empty($this->_card_title[0]['title'])) { ?>
            <div class="card-title-wrap"> <span class="card-title"><?php echo $this->_card_title[0]['title']; ?></span></div>
        <?php } ?>
    </div>

    <?php if(!empty($this->_extra['teaser'])) { ?>
    <div class="card-content">
        <?php echo $this->_extra['teaser']; ?>
    </div>
    <?php } ?>

    <?php if(!empty($this->_extra['url'])) { ?>
    <div class="card-action">
        <a href="<?php echo $this->_extra['url']; ?>"><?php echo $this->_extra['linkText']; ?></a>
    </div>
    <?php } ?>

</div>