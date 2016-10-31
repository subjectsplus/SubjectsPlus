<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:52 PM
 */?>


    <div class="booklist-content" rendered="0">
        <input class="booklist-isbn-list-input" type="hidden" value="<?php echo $this->_extra['isbn']?>"/>
        <input class="booklist-syndetics-client-code" type="hidden" value="<?php global $syndetics_client_code; echo (isset($syndetics_client_code)) ? $syndetics_client_code : '';?>"/>
        <input class="google-books-api-key" type="hidden" value="<?php global $google_books_api_key; echo (isset($google_books_api_key)) ? $google_books_api_key : '';?>"/>
        <input class="primo-domain" type="hidden" value="<?php global $primo_domain; echo (isset($primo_domain)) ? $primo_domain : '';?>"/>
        <input class="primo-institution-code" type="hidden" value="<?php global $primo_institution_code; echo (isset($primo_institution_code)) ? $primo_institution_code : '';?>"/>
        <input class="primo-view" type="hidden" value="<?php global $primo_vid; echo (isset($primo_vid)) ? $primo_vid : '';?>"/>

        <p class="booklist-list-description"><?php  echo isset($this->_extra['listDescription']) ? $this->_extra['listDescription'] : '';?></p>
    </div>

