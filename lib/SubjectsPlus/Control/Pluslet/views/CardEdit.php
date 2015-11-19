<form class="pure-form pure-form-stacked" id="cardEditForm">

    <textarea name="cardEditor" id="cardEditor"><?php echo $this->_body; ?></textarea>
    <label for="card-teaser">Teaser</label> <textarea id="card-teaser" name="Card-extra-teaser" style="width:95%;"><?php echo $this->_extra['teaser']; ?></textarea>
    <label for="card-linkText">Link Text</label> <input id="card-linkText" type="text" name="Card-extra-linkText" value="<?php echo $this->_extra['linkText']; ?>" style="width:95%;" />
    <label for="card-url">URL Address</label> <input id="card-url" type="url" name="Card-extra-url" value="<?php echo $this->_extra['url']; ?>" style="width:95%;" />
</form>

<script>

    $(document).ready(function() {
        $("textarea[name=cardEditor]").hide();


    });
</script>