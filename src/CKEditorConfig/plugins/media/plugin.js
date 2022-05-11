(function() {
    const figureClass = 'image';
    const figureTemplate = '<figure class="' + figureClass + '">' +
            '<img alt="{altText}" src="{link}" />' +
            '<figcaption>{caption}</figcaption>' +
        '</figure><br />';

    const linkTemplate = '<a href="{link}">{title}</a>';

    let mediaTokenOffcanvas = null;

    CKEDITOR.plugins.add( 'media', {
        icons: 'media',
        allowedContent: 'img[src,alt,width,height],figure,figcaption,br',
        init: function( editor ) {
            editor.addCommand('toggleMediaSidebar', {
                'exec': function(editor) {
                    const sidebar = document.getElementById('offcanvasMediaToken');
                    if (sidebar) {
                        if (mediaTokenOffcanvas) {
                            mediaTokenOffcanvas.toggle();
                        } else {
                            mediaTokenOffcanvas = new bootstrap.Offcanvas(sidebar);
                            mediaTokenOffcanvas.toggle();
                        }
                    } else {
                        console.error('Media sidebar not found!');
                    }
                }
            });

            editor.on('paste', function(evt) {
                const tokenElement = evt.data.dataTransfer.getData('tokenElement');
                if (!tokenElement) {
                    return;
                }

                evt.data.dataValue = tokenElement;
            });

            editor.on('insertHtml', function(evt) {
                const temp = document.createElement('div');
                temp.innerHTML = evt.data.dataValue;

                const mediaElement = temp.firstChild;
                if (!mediaElement?.dataset?.mediaId) {
                    return;
                }

                const media = {
                    'mediaId': mediaElement.dataset['mediaId'],
                    'mimeType': mediaElement.dataset['mimeType'],
                    'title': mediaElement.dataset['title'],
                    'altText': mediaElement.dataset['altText'],
                    'caption': mediaElement.dataset['caption'],
                    'link': mediaElement.dataset['link']
                };

                // choose template based on mime type and replace placeholders
                if (media.mimeType.substring(0, 5) === 'image') {
                    // use figure template for images
                    evt.data.dataValue = figureTemplate.replace('{altText}', media.altText)
                    .replace('{link}', media.link)
                    .replace('{caption}', media.caption);
                } else {
                    // use link template for all non-image media
                    evt.data.dataValue = linkTemplate.replace('{link}', media.link)
                    .replace('{title}', media.title);
                }
            });

            editor.ui.addButton( 'Media', {
                label: 'Insert My Media',
                command: 'toggleMediaSidebar',
                toolbar: 'insert,100'
            });
        }
    });

    CKEDITOR.on('instanceReady', function() {
        // When an item in the media list is dragged, copy its data into the drag and drop data transfer.
        // This data is later read by the editor#paste listener in the media plugin defined above.
        if (CKEDITOR.document.getById('media-list')) {
            CKEDITOR.document.getById('media-list').on('dragstart', function(evt) {
                // The target may be some element inside the draggable div (e.g. the image), so get the div.media-token.
                const target = evt.data.getTarget().getAscendant('div', true);

                // Initialization of the CKEditor 4 data transfer facade is a necessary step to extend and unify native
                // browser capabilities. For instance, Internet Explorer does not support any other data type than 'text' and 'URL'.
                // Note: evt is an instance of CKEDITOR.dom.event, not a native event.
                CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);

                const dataTransfer = evt.data.dataTransfer;

                // Set outerHTML of token to tokenElement data key
                dataTransfer.setData('tokenElement', target.$.outerHTML);

                // You need to set some normal data types to backup values for two reasons:
                // * In some browsers this is necessary to enable drag and drop into text in the editor.
                // * The content may be dropped in another place than the editor.
                dataTransfer.setData('text/html', target.getText());

                // You can still access and use the native dataTransfer - e.g. to set the drag image.
                // Note: IEs do not support this method... :(.
                if (dataTransfer.$.setDragImage && target.findOne('img')) {
                    dataTransfer.$.setDragImage(target.findOne('img').$, 0, 0);
                }
            });
        } else {
            console.log('Media Token dragstart event failed to load! Id "media-list" is null.')
        }
    });
})();
