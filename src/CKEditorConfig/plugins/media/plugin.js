CKEDITOR.plugins.add( 'media', {
    icons: 'media',
    allowedContent: 'img[src,alt,width,height],figure,figcaption,br',
    init: function( editor ) {
        const pluginDirectory = this.path;
        editor.addContentsCss( pluginDirectory + 'styles/sp-custom-cke-media.css' );
        editor.addCommand('toggleMediaSidebar', {
            'exec': function(editor) {
                var sidebar = document.getElementById('offcanvasMediaToken');
                var mediaTokenOffcanvas = null;
                if (sidebar) {
                    if (mediaTokenOffcanvas) {
                        mediaTokenOffcanvas.toggle();
                    }
                    else {
                        mediaTokenOffcanvas = new bootstrap.Offcanvas(sidebar);
                        mediaTokenOffcanvas.toggle();
                    }
                } else {
                    console.error('Media sidebar not found!');
                }
            }
        });

        editor.on('paste', function(evt) {
            var media = evt.data.dataTransfer.getData('media');
            if (!media) {
                return;
            }

            if (media.mimeType.substring(0, 5) === 'image') {
                evt.data.dataValue =
                '<br />' +
                '<figure class="image">' +
                    '<img alt="' + media.altText + '" src="' + media.link + '" />' +
                    '<figcaption>' + media.caption + '</figcaption>' +
                '</figure>' +
                '<br />';
            } else {
                evt.data.dataValue =
                '<a href="' + media.link + '">' + media.title + '</a>';
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
    // When an item in the contact list is dragged, copy its data into the drag and drop data transfer.
    // This data is later read by the editor#paste listener in the media plugin defined above.
    CKEDITOR.document.getById('media-list').on('dragstart', function(evt) {
      // The target may be some element inside the draggable div (e.g. the image), so get the div.media-card.
      var target = evt.data.getTarget().getAscendant('div', true);

      // Initialization of the CKEditor 4 data transfer facade is a necessary step to extend and unify native
      // browser capabilities. For instance, Internet Explorer does not support any other data type than 'text' and 'URL'.
      // Note: evt is an instance of CKEDITOR.dom.event, not a native event.
      CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);

      var dataTransfer = evt.data.dataTransfer;

      var media = {
          'mediaId': target.data('media-id'),
          'mimeType': target.data('mime-type'),
          'title': target.data('title'),
          'altText': target.data('alt-text'),
          'caption': target.data('caption'),
          'link': target.data('link')
      };

      dataTransfer.setData('media', media);

      // You need to set some normal data types to backup values for two reasons:
      // * In some browsers this is necessary to enable drag and drop into text in the editor.
      // * The content may be dropped in another place than the editor.
      dataTransfer.setData('text/html', target.getText());

      // You can still access and use the native dataTransfer - e.g. to set the drag image.
      // Note: IEs do not support this method... :(.
      if (dataTransfer.$.setDragImage) {
        dataTransfer.$.setDragImage(target.findOne('img').$, 0, 0);
      }
    });
  });
