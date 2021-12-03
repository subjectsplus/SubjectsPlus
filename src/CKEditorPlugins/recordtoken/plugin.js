(function() {

    var linkTemplate = '<a class="record-link" href="{recordLink}">{recordTitle}</a>',
        descriptionTemplate = '<span class="record-description">{recordDescription}</span>',
        template = '<span class="record-token" data-record-id="{recordId}">' +
                    linkTemplate +
                    '</span>';

    CKEDITOR.plugins.add('recordtoken', {
        icons: 'record',
        requires: 'widget',
        
        onLoad: function() {
            CKEDITOR.addCss(
                '.record-token {' +
                'background: #FFFDE3;' +
                'padding: 3px 6px;' +
                'border-bottom: 1px dashed #ccc;' +
                '}' 
            );
        },

        init: function( editor ) {
            editor.widgets.add('recordtoken', {
                allowedContent: getWidgetAllowedContent(),
                pathName: 'recordtoken',

                upcast: function(el) {
                    return el.name == 'span' && el.hasClass('record-token');
                }
            });

            editor.addFeature(editor.widgets.registered.recordtoken);

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

                // replace placeholders in template with record details
                var dataValue = template.replace('{recordId}', record.recordId);
                dataValue = dataValue.replace('{recordLink}', record.location);
                dataValue = dataValue.replace('{recordTitle}', record.title);

                evt.data.dataValue = dataValue;

                console.log(evt.data.dataValue);
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

    function getWidgetAllowedContent() {
        var rules = {
            span: {
                classes: 'record-token',
                attributes: 'data-*'
            },

            a: {
                classes: 'record-link',
                attributes: 'href'
            }
        }

        return rules;
    }
})();

