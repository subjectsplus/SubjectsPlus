<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:52 PM
 */?>

<div id="container" class=\"container\">
        <input type="hidden" id="isbnList" value="<?php
        if ($this->_extra != null)
        {
            $a = $this->_extra['isbn'];
            echo $this->_extra['isbn'];
        }?>" />
</div>

<script>
    var bookList = bookList();
    bookList.init();
</script>