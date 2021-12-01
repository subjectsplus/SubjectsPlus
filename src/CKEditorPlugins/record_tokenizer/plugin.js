CKEDITOR.plugins.add( 'record_tokenizer', {
    icons: 'records',
    init: function( editor ) {
        editor.addCommand('toggleRecordSidebar', {
            'exec': function(editor) {
                var sidebar = document.getElementById('records-sidebar');
                if (sidebar) {
                    console.log(sidebar);
                    console.log(sidebar.style.display);
                    if (sidebar.style.display === 'none' || sidebar.style.display === '') {
                        sidebar.style.display = 'block';
                    } else {
                        sidebar.style.display = 'none';
                    }
                }
            }
        });

        editor.on('paste', function(evt) {
            var record = evt.data.dataTransfer.getData('record');
            if (!record) {
                return;
            }

            evt.data.dataValue =
            '<a class="record-token" data-record-id="' + record.recordId + '" href="' + record.location + '">' + record.title + '</a>';
        });

        editor.ui.addButton( 'Record', {
            label: 'Insert Record',
            command: 'toggleRecordSidebar',
            toolbar: 'insert'
        });
    }
});

CKEDITOR.on('instanceReady', function() {
    // When an item in the contact list is dragged, copy its data into the drag and drop data transfer.
    // This data is later read by the editor#paste listener in the media plugin defined above.
    CKEDITOR.document.getById('records-list').on('dragstart', function(evt) {
      // The target may be some element inside the draggable div (e.g. the image), so get the div.media-card.
      var target = evt.data.getTarget().getAscendant('div', true);

      // Initialization of the CKEditor 4 data transfer facade is a necessary step to extend and unify native
      // browser capabilities. For instance, Internet Explorer does not support any other data type than 'text' and 'URL'.
      // Note: evt is an instance of CKEDITOR.dom.event, not a native event.
      CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);

      var dataTransfer = evt.data.dataTransfer;

      var record = {
          'recordId': target.data('record-id'),
          'title': target.data('record-title'),
          'location': target.data('record-location')
      };

      dataTransfer.setData('record', record);

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
