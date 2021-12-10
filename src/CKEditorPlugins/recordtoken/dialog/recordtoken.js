CKEDITOR.dialog.add( 'recordtokenDialog', function( editor ) {
    return {
        title: 'Edit Record Token',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'tab-main',
                label: 'Record Token Settings',
                elements: [
                    {
                        type: 'text',
                        id: 'record-title',
                        label: 'Title',
                        validate: CKEDITOR.dialog.validate.notEmpty('Title field cannot be empty.'),

                        setup: function(widget) {
                            console.log(widget);
                            var record = widget.data.record;
                            if (record) {
                                this.setValue(record.title);
                            }
                        }
                    },
                    
                    {
                        type: 'text',
                        id: 'record-description',
                        label: 'Description',
                        validate: CKEDITOR.dialog.validate.notEmpty('Description field cannot be empty.'),
                        setup: function(widget) {
                            console.log(widget);
                            var record = widget.data.record;
                            if (record) {
                                this.setValue(record.description);
                            }
                        }
                    },
                    {
                        type: 'checkbox',
                        id: 'record-show-description',
                        label: 'Show Description',
                        'default': false
                    }
                ]
            }
        ]
    };
});