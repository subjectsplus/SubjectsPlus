<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:52 PM
 */?>


    <div class="booklist-content" rendered="0">
        <input class="booklist-isbn-list-input" type="hidden" value="<?php echo $this->_extra['isbn']?>"/>
        <input class="google-books-api-key" type="hidden" value="<?php global $google_books_api_key; echo (isset($google_books_api_key)) ? $google_books_api_key : '';?>"/>
        <p class="booklist-list-description"><?php echo isset( $this->_extra['listDescription'] ) ? $this->_extra['listDescription'] : ''; ?></p>
    </div>




