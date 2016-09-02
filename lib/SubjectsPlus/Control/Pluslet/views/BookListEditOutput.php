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

    function bookList() {

        "use strict";

        var myBookList = {

            settings: {},
            strings: {},
            bindUiActions: function () {
                myBookList.validCharacters();
                myBookList.isNumberKey();
            },
            init: function () {
                myBookList.bindUiActions();
            },
            validCharacters: function () {
                $('textarea[name=BookList-extra-isbn]').on('paste', function() {
                    var $el = $(this);
                    setTimeout(function() {
                        $el.val(function(i, val) {
                            return val.replace(/[^0-9X,]/g, '')
                        })
                    })
                });
            },
            isNumberKey: function ()
            {
                $('textarea[name=BookList-extra-isbn]').keypress(function(evt) {
                    var result = true;
                    var charCode = (evt.which) ? evt.which : event.keyCode;
                    if (charCode < 48 || charCode > 57)
                        result = false;

                    if (charCode == 88 || charCode == 44)
                        result = true;

                    return result;
                });
            }
        };

        return myBookList;
    }

    var bookList = bookList();
    bookList.init();
</script>