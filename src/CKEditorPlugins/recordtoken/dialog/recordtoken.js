CKEDITOR.dialog.add( 'recordtoken', function( editor ) {
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
                            this.setValue(widget.data.title);
                        },

                        commit: function(widget) {
                            widget.setData('title', this.getValue());
                        }
                    },

                    {
                        type: 'textarea',
                        id: 'record-description',
                        label: 'Description',
                        setup: function(widget) {
                            var record = widget.data.record;
                            if (record) {
                                this.setValue(record.description);
                                this.disable();
                            }
                        }
                    },
                    {
                        type: 'radio',
                        id: 'record-description-type',
                        label: 'Description Type',
                        items: [
                            ['No Description', 'none'], ['Description Icon', 'icon'], ['Description Block', 'block']
                        ],
                        'default': 'none',
                        
                        setup: function(widget) {
                            this.setValue(widget.data.descriptionType);
                        },

                        commit: function(widget) {
                            widget.setData('descriptionType', this.getValue());
                        }
                    }
                ]
            }
        ]
    };
});