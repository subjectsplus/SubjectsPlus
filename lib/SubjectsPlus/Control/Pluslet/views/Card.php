<div class="card">
    <div class="card-image">
        <?php echo $this->_body; ?>
        <div class="card-title-wrap"> <span class="card-title"><?php echo $this->_card_title[0]['title']; ?></span></div>
    </div>
    <div class="card-content">
        <?php echo $this->_extra['teaser']; ?>
    </div>
    <div class="card-action">
        <a href="<?php echo $this->_extra['url']; ?>"><?php echo $this->_extra['linkText']; ?></a>
    </div>
</div>