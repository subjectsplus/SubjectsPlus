<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:50 PM
 */?>

<textarea rows="4" cols="30" name="BookList-extra-isbn"
          placeholder="Please insert a comma-separated ISBNs list"><?php
    if ($this->_extra != null) {
        echo $this->_extra['isbn'];
    }
    ?></textarea>

<script>
    var b = bookList();
    b.initEditView();
</script>