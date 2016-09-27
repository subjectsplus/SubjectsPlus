<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:52 PM
 */?>


    <div class="booklist-content">
        <input class="booklist-isbn-list-input" type="hidden" value="<?php echo $this->_extra['isbn']?>"/>
        <input class="booklist-syndetics-client-code" type="hidden" value="<?php global $syndetics_client_code; echo (isset($syndetics_client_code)) ? $syndetics_client_code : '';?>"/>
        <input class="google-books-api-key" type="hidden" value="<?php global $google_books_api_key; echo (isset($google_books_api_key)) ? $google_books_api_key : '';?>"/>
    </div>


<?php

    $url = $_SERVER['REQUEST_URI'];
    $script = "<script src=\"../assets/js/guides/bookList.js\" type=\"text/javascript\"></script>";

    if (!strpos($url, 'control')){
        echo $script;
    }

?>

<script>

    var containerSelector = "<?php
        if (!empty($this->_clone_id)){
            echo "pluslet-" . $this->_clone_id;
        }else{
            echo $this->_pluslet_id_field;
        }
    ?>";

    var container = $("#"+containerSelector).find('.booklist-content');
    $.each(container, function() {
        var b = bookList();
        b.init(this);
        var a = 'a';
    });


</script>