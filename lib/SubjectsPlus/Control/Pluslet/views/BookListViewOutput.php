<?php
/**
 * Created by PhpStorm.
 * User: acarrasco
 * Date: 8/23/2016
 * Time: 2:52 PM
 */?>

<div class="bookListPlusletPreloader" style=" display: table; margin: auto;"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><br><span
        class="sr-only" style="font-size: 0.875em;"><?php echo _('Loading...') ?></span></div>
<div class="booklist-content" rendered="0" style="display:none;" data-booklist-id="<?php echo $this->_pluslet_id; ?>">
    <input class="booklist-isbn-list-input" type="hidden" value="<?php echo $this->_extra['isbn'] ?>"/>
    <input class="google-books-api-key" type="hidden" value="<?php global $google_books_api_key;
    echo ( isset( $google_books_api_key ) ) ? $google_books_api_key : ''; ?>"/>
    <p class="booklist-list-description"><?php echo isset( $this->_extra['listDescription'] ) ? $this->_extra['listDescription'] : ''; ?></p>
</div>

<script>
    $(document).ready(()=> {
        // Convenience tool to switch to editing view for development
        let automaticallyOpenEditView
        = true // <-- comment this out to turn off
        ;

        if (automaticallyOpenEditView) {
            setTimeout(()=> {
                const pluslet_id = Number(<?php print $this->_pluslet_id ?>);
                $(`#edit-${pluslet_id}-BookList`).click();
                console.clear();
            }, 2500);
        };
    });
</script>

