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


<fieldset>
    <legend>Get book's cover from</legend>
    <input type="checkbox" name="BookList-extra-openLibraryCover" class="book-list-pluslet-checkbox" <?php echo $this->_bookListSettings['openLibraryCover']; ?>/>
    <label>Open Library</label>
    <input type="checkbox" name="BookList-extra-syndeticsCover" class="book-list-pluslet-checkbox" <?php echo $this->_bookListSettings['syndeticsCover']; ?>/>
    <label>Syndetics</label>
    <input type="checkbox" name="BookList-extra-googleBooksCover" class="book-list-pluslet-checkbox" <?php echo $this->_bookListSettings['googleBooksCover']; ?>/>
    <label>Google Books</label>
</fieldset>
<fieldset>
    <legend>Get book's metadata from</legend>
    <input type="checkbox" name="BookList-extra-openLibraryMetadata" class="book-list-pluslet-checkbox" <?php echo $this->_bookListSettings['openLibraryMetadata']; ?>/>
    <label>Open Library</label>
    <input type="checkbox" name="BookList-extra-googleBooksMetadata" class="book-list-pluslet-checkbox" <?php echo $this->_bookListSettings['googleBooksMetadata']; ?>/>
    <label>Google Books</label>
</fieldset>

<script>
    var b = bookList();
    b.initEditView();
</script>