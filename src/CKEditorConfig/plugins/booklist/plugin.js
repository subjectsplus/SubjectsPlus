(function() {
    window.bookListOffcanvas = null;

    CKEDITOR.plugins.add( 'booklist', {
        icons: 'media',

        init: function( editor ) {
            const pluginDirectory = this.path;
            editor.addContentsCss( pluginDirectory + 'styles/sp-custom-cke-booklist.css' );
            editor.addCommand('toggleBookListOffCanvas', {
                'exec': function(editor) {
                    const sidebar = document.getElementById('offcanvasBookList');
                    if (sidebar) {
                        // hide other offcanvas that may exist
                        if (window.recordTokenOffcanvas) {
                            window.recordTokenOffcanvas.hide();
                        }
                        if (window.mediaTokenOffcanvas) {
                            window.mediaTokenOffcanvas.hide();
                        }

                        // toggle the off canvas
                        if (window.bookListOffcanvas) {
                            window.bookListOffcanvas.toggle();
                        } else {
                            window.bookListOffcanvas = new bootstrap.Offcanvas(sidebar);
                            window.bookListOffcanvas.toggle();
                        }
                    } else {
                        console.error('BookList offcanvas not found!');
                    }
                }
            });

            editor.ui.addButton( 'BookList', {
                label: 'Insert BookList',
                command: 'toggleBookListOffCanvas',
                toolbar: 'insert,102'
            });
        }
    });
})();
