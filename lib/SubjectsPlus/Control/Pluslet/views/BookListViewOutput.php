<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:52 PM
 */?>

<div id="booklist-container" class="booklist-container">
        <input type="hidden" id="isbnList" value="<?php
        if ($this->_extra != null)
        {
            //$a = $this->_extra['isbn'];
            echo $this->_extra['isbn'];
        }?>" />
</div>

