(function() {

    var linkClass = 'record-link';
    var tokenClass = 'record-token';
    var descriptionClass = 'record-description';
    var iconClass = 'record-icon';

    var iconSource = '/build/assets/images/icons/information.png';

    var linkTemplate = '<a class="' + linkClass + '" href="{recordLink}">{recordTitle}</a>',
        descriptionTemplate = '<span class="' + descriptionClass + '">{recordDescription}</span>',
        iconTemplate = '<button class="' + iconClass + '"><img src="' + iconSource +'" /></button>',
        template = '<span class="' + tokenClass + '" data-record-id="{recordId}">' +
                    linkTemplate +
                    '</span>';
    
    var templateBlock = new CKEDITOR.template(function(data) {

        if (data.descriptionType == 'block') {
            return '<span class="' + tokenClass + '" data-record-id="{recordId}">' +
                linkTemplate + descriptionTemplate +
                    '</span>';
        } else if (data.descriptionType == 'icon') {
            return '<span class="' + tokenClass + '" data-record-id="{recordId}">' +
            linkTemplate + iconTemplate +
                '</span>';
        } else {
            return template;
        }
    });

    var titleApi = '/api/titles/{titleId}';

    CKEDITOR.plugins.add('recordtoken', {
        icons: 'record',
        requires: 'widget,dialog',
        
        onLoad: function() {
            CKEDITOR.addCss(
                '.' + tokenClass + ' {' +
                'background: #FFFDE3;' +
                'padding: 3px 6px;' +
                'border-bottom: 1px dashed #ccc;' +
                '}' +
                '.' + iconClass + ' {' +
                'background-color: transparent;' +
                'background-repeat: no-repeat;' +
                'border: none;' +
                'cursor: pointer;' +
                'overflow: hidden;' +
                'outline: none;' +
                '}' +
                '.' + iconClass + ' img {' +
                'margin: auto' +
                '}'
            );
        },

        init: function( editor ) {

            var records = {};

            // Create record token widget
            editor.widgets.add('recordtoken', {
                allowedContent: getWidgetAllowedContent(),
                pathName: 'recordtoken',
                dialog: 'recordtokenDialog',
                template: templateBlock,

                upcast: function(el) {
                    return el.name == 'span' && el.hasClass(tokenClass);
                },

                init: function() {
                    var recordId = this.element.data('record-id');

                    // Set record data, call api and index if unavailable
                    if (records[recordId]) {
                        this.setData('record', records[recordId]);
                    } else {
                        var record = getRecordFromAPI(recordId);
                        if (record) {
                            record = JSON.parse(apiResult);
                            this.setData('record', record);
                            records[recordId] = record;
                        } else {
                            this.setData('record', null);
                        }
                    }

                    // Set description type data
                    var descriptionBlock = this.element.findOne('.' + descriptionClass);
                    var descriptionIcon = this.element.findOne('.' + iconClass);

                    if (descriptionBlock) {
                        this.setData('descriptionType', 'block');
                        this.oldDescriptionType = 'block';
                    } else if (descriptionIcon) {
                        this.setData('descriptionType', 'icon');
                        this.oldDescriptionType = 'icon';
                    } else {
                        this.setData('descriptionType', 'none');
                        this.oldDescriptionType = 'none';
                    }
                },

                data: function() {
                    var newDescriptionType = this.data.descriptionType;
                    console.log("Old DescriptionType: " + this.oldDescriptionType);
                    console.log("New DescriptionType: " + newDescriptionType);

                    if (this.oldDescriptionType == newDescriptionType)
                        return;
                    
                    var description = this.data.record.description;
                    if (!description || description.trim().length == 0) {
                        // Description is null or empty
                        // Set description type to none and notify the user
                        this.setData('descriptionType', 'none');
                        var notification = new CKEDITOR.plugins.notification( editor, {
                            message: 'No description is available for this record! Description type will default to none.',
                            type: 'warning'
                        } );
                        notification.show();

                        return;
                    }

                    if (this.oldDescriptionType == 'block') {
                        // Undo the block template
                        var descriptionBlock = this.element.findOne('.' + descriptionClass);
                        if (descriptionBlock) {
                            descriptionBlock.remove();
                        }

                        var breakLine = this.element.findOne('br');
                        if (breakLine) {
                            breakLine.remove();
                        }
                    } else if (this.oldDescriptionType == 'icon') {
                        // Undo the icon template
                        var descriptionIcon = this.element.findOne('.' + iconClass);
                        if (descriptionIcon) {
                            descriptionIcon.remove();
                        }
                    }

                    if (newDescriptionType == 'block') {
                        var html = '<br />' + descriptionTemplate.replace('{recordDescription}', description);
                        this.element.appendHtml(html);
                    } else if (newDescriptionType == 'icon') {
                        this.element.appendHtml(iconTemplate);
                    }

                    this.oldDescriptionType = newDescriptionType;
                }
            });

            // Register the record token widget
            editor.addFeature(editor.widgets.registered.recordtoken);

            // Register the record token dialog window
            CKEDITOR.dialog.add('recordtokenDialog', this.path + 'dialog/recordtoken.js');

            // Define an editor command that opens our dialog window
		    editor.addCommand( 'recordtoken', new CKEDITOR.dialogCommand('recordtokenDialog'));

            // if editor has a context menu, add context entry for opening dialog window
            if (editor.contextMenu) {
                editor.addMenuGroup('recordtoken', 3);
                editor.addMenuItems({
                    recordtoken_edit: {
                        label: 'Edit Record Token',
                        icon: this.path + 'icons/record.png',
                        command: 'recordtoken',
                        group: 'recordtoken'
                    }
                });

                editor.contextMenu.addListener( function( element ) {
                    if (element.hasClass('cke_widget_wrapper_' + tokenClass)) {
                        return { recordtoken_edit: CKEDITOR.TRISTATE_OFF };
                    }
                });
            }

            editor.addCommand('toggleRecordSidebar', {
                'exec': function(editor) {
                    var sidebar = document.getElementById('records-sidebar');
                    if (sidebar) {
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
                console.log(templateBlock);
                var dataValue = template.replace('{recordId}', record.recordId);
                dataValue = dataValue.replace('{recordLink}', record.location);
                dataValue = dataValue.replace('{recordTitle}', record.title);

                evt.data.dataValue = dataValue;

                records[record.recordId.toString()] = record;
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
            'description': target.data('record-description'),
            'location': target.data('record-location')
        };

        dataTransfer.setData('record', record);

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
    });

    function getWidgetAllowedContent() {
        var rules = {
            span: {
                classes: [tokenClass, descriptionClass],
                attributes: 'data-*'
            },

            a: {
                classes: linkClass,
                attributes: 'href'
            },

            button: {
                classes: iconClass,
                attributes: 'src'
            }
        }

        return rules;
    }

    function getRecordFromAPI(recordId) {
        var apiLink = titleApi.replace('{titleId}', recordId);
        var apiResult = CKEDITOR.ajax.load(apiLink);
        
        if (apiResult) {
            var record = JSON.parse(apiResult);

            return {
                'recordId': recordId,
                'title': record.title,
                'description': record.description,
            }
        }
    }
})();

