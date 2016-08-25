<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:50 PM
 */?>


        <textarea id="target" rows="4" cols="30" name="BOOKLIST-extra-isbn"
                  placeholder="Please insert a comma-separated ISBNs list"><?php
                if ($this->_extra != null)
                    {
                        echo $this->_extra['isbn'];
                    }

            ?></textarea>

<script>
    var bookList = bookList();
    bookList.init();
</script>