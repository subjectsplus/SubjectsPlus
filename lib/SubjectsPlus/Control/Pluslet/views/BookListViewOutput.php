<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:52 PM
 */?>

<div class="booklist-container">
    <input class="booklist-isbn-list-input" type="hidden" value="<?php echo $this->_extra['isbn']?>"/>
    <input class="booklist-syndetics-client-code" type="hidden" value="<?php global $syndetics_client_code; echo (isset($syndetics_client_code)) ? $syndetics_client_code : '';?>"/>
    <input class="$google-books-api-key" type="hidden" value="<?php global $google_books_api_key; echo (isset($google_books_api_key)) ? $google_books_api_key : '';?>"/>
</div>

<script>
    var b = bookList();
    b.init();
</script>