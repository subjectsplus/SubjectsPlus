/// <reference path="scripts/typings/jquery/jquery.d.ts" />
var Record = (function () {
    function Record(settings) {
        this.location = settings.location;
        if (settings.tokenString) {

            // console.warn('settings.tokenString is TRUE');

            this.tokenString = settings.tokenString;
            //console.log('tokenString: ' + this.tokenString);
            //console.log('prefix: ' + this.prefix);
            var splitToken = settings.tokenString.split("{").join('').split("}").join('').split(',');
            this.recordId = splitToken[1];
            this.prefix = "";
            this.title = splitToken[2];
            this.displayOptions = splitToken[3];
            var optionsArray = this.displayOptions.toString().split('').map(Number);
            this.showIcons = optionsArray[0];
            this.showNote = optionsArray[1];
            this.showDescription = optionsArray[2];
        }
        else {

            // console.warn('settings.tokenString is false');

            this.recordId = settings.recordId;
            this.title = settings.title;
            this.prefix = settings.prefix;
            //console.log('prefix: ' + this.prefix);
            this.displayOptions = settings.displayOptions;
            settings.showIcons === undefined ? this.showIcons = 0 : this.showIcons = settings.showIcons;
            settings.showNote === undefined ? this.showNote = 0 : this.showNote = settings.showNote;
            settings.showDescription === undefined ? this.showDescription = 0 : this.showDescription = settings.showDescription;
        }
    }
    Record.prototype.getRecordToken = function () {
        var displayOptions = "" + this.showIcons + this.showDescription + this.showNote;
        return "{{dab},{" + this.recordId + "},{" + this.title + "},{" + displayOptions + "}}";
    };
    return Record;
}());
var RecordList = (function () {
    function RecordList() {
        this.recordList = [];
    }
    RecordList.prototype.addToList = function (record) {
        this.recordList.push(record);
    };
    RecordList.prototype.getList = function () {
        return this.recordList;
    };
    RecordList.prototype.removeFromList = function (index) {
        this.recordList.splice(index, 1);
    };
    return RecordList;
}());
var RecordListSortable = (function () {
    function RecordListSortable(recordList) {
        this.recordList = recordList;
    }
    RecordListSortable.prototype.getList = function () {
        var recordListHtml = this.liSortableRecordList();
        return "<ul class=\"db-list-results ui-sortable\" id=\"db-list-results\">" + recordListHtml + "</ul>";
    };
    RecordListSortable.prototype.sortableToggleSpan = function (toggleClass, active, label) {
        var toggleSpanHtml;
        var checkIcon = ( active ? 'check' : 'minus' );
        const checkHtml = `
            <i class='fa fa-${checkIcon}'></i>
        `;
        
        toggleSpanHtml = `
            <span class='${toggleClass} db-list-toggle'>
                ${checkHtml} ${label}
            </span>
        `;

        return toggleSpanHtml;
    };


    /**
     * @description Fetches Records from DB.
     * @param {RecordList} recordsArray Takes an array of Record objects.
     */
    RecordListSortable.prototype.liSortableRecord = function (recordsArray) {

        // console.table('recordsArray coming into RecordList.js > liSortableRecord(): ', recordsArray);

        // var showIconToggle;
        // var showDescriptionToggle;
        // var showNotesToggle;
        var subject_id = $('#guide-parent-wrap').attr("data-subject-id");
        var description_override = '';

        // Updated subject_databases_helper action will accept array of record IDs as just ints
        const onlyIds = recordsArray.map((record) => record.recordId);

        // Local RecordList + var to track # of elements, in case they're different from DB
        // (ie. user has added a new item to the RecordList that hasn't yet been saved)
        const existingRecordList = this.recordList.recordList;
        const existingRecordListLength = existingRecordList.length;

        let html = '';
        const that = this;

        $.ajax({
            url: '../records/helpers/subject_databases_helper.php',
            type: "GET",
            dataType: "json",
            data: {
                'action': 'getDescriptionOverrides',
                'subject_id': subject_id,
                'record_ids': onlyIds
            },
            async: false,
            success: (data) => {
                var databases = data.databases;
                const arrayLengthMismatch = (databases.length !== existingRecordListLength);

                if (arrayLengthMismatch) {
                    for (const item of existingRecordList) {
                        const itemInBoth = databases.find((dbItem) => {
                            return Number(dbItem.title_id) === Number(item.recordId);
                        });

                        if (!itemInBoth) {
                            databases.push(item);
                        };
                    }
                };

                console.log({ databases, existingRecordList, arrayLengthMismatch }, this );

                $.each(databases, (index, obj) => {
                    html += that.buildSortableRecordItem(obj, existingRecordList);
                });
            }
        });

        return html;
    };

    RecordListSortable.prototype.buildSortableRecordItem = function( record, existingRecordList ) {        
        const title_id = Number(record.title_id) | record.recordId;
        const existingRecord = existingRecordList.find((record) => record.recordId === title_id);
        const mergedRecord = {...existingRecord, ...record};

        // console.log({mergedRecord});

        if (mergedRecord.description_override) {
            description_override = mergedRecord.description_override.trim();
        };

        // I don't know what this </span> tag below is closing, but leaving it in. ¯\_(ツ)_/¯ -Ali
        let textArea = `
            <textarea
                class='link-list-description-override-textarea'
                style='clear: both; display: none'
                rows='4'
                cols='35'>
            </textarea>
            </span>
        `;
        
        if (mergedRecord.rank_id) {
            textArea = `
                <textarea
                    id='description-override-textarea${mergedRecord.rank_id}'
                    title_id='${mergedRecord.title_id}'
                    subject_id='${mergedRecord.subject_id}'
                    class='link-list-description-override-textarea'
                    style='clear: both; display: none'
                    rows='4'
                    cols='35'>
                        ${mergedRecord.description_override}
                </textarea>
            `;
        };

        let descriptionOverrideButton = `
            <button
                class='db-list-item-description-override pure-button pure-button-secondary'
                title='Edit description'>
                    <i class='fa fa-pencil'></i>
            </button>
        `;

        if (mergedRecord.description_override){
            descriptionOverrideButton = `
                <button
                    class='db-list-item-description-override pure-button pure-button-secondary active'
                    title='Edit description'>
                        <i class='fa fa-pencil'></i>
                </button>
            `;
        };

        const iconToggleBoolean = (mergedRecord.showIcons === 1);
        const showIconToggle = this.sortableToggleSpan( 'show-icons-toggle', iconToggleBoolean, 'Icons');

        const showDescriptionBoolean = (mergedRecord.showDescription === 1);
        const showDescriptionToggle = this.sortableToggleSpan('show-description-toggle', showDescriptionBoolean, 'Description');

        const showNotesBoolean = (mergedRecord.showNote === 1);
        const showNotesToggle = this.sortableToggleSpan('include-note-toggle', showNotesBoolean, 'Note');

        console.log({ showIconToggle, showDescriptionToggle, showNotesToggle });
        
        const liRecordHtml = `
            <li
                class='db-list-item-draggable'
                data-location='${                   mergedRecord.location}'
                data-record-id='${                  mergedRecord.recordId}'
                data-title='${                      mergedRecord.title}'
                data-show-icons='${                 mergedRecord.showIcons}'
                data-show-note='${                  mergedRecord.showNote}'
                data-show-description='${           mergedRecord.showDescription}'>
                    <span class='db-list-label'>${  mergedRecord.title}</span>
                    ${descriptionOverrideButton}
                    <button
                        class='db-list-remove-item pure-button pure-button-secondary'
                        title='Remove from list'>
                            <i class='fa fa-remove'></i>
                    </button>
                    <div>
                        ${showIconToggle} ${showNotesToggle} ${showDescriptionToggle}
                    </div>
                    ${textArea}
                    </span>
            </li>
        `;

        return liRecordHtml;
    };

    RecordListSortable.prototype.liSortableRecordList = function () {
        return this.liSortableRecord(this.recordList.recordList);
    };
    return RecordListSortable;
}());
var RecordListDisplay = (function () {
    function RecordListDisplay(recordList) {

        console.table(recordList);

        this.recordList = recordList;
    }
    RecordListDisplay.prototype.getList = function () {
        var recordListHtml = this.liDisplayRecordList();
        return "<ul class='link-list-display'>" + recordListHtml + "</ul>";
    };
    RecordListDisplay.prototype.liDisplayRecord = function (record) {
        var token = record.getRecordToken();
        console.log('token: ' + token);
        return "<li data-location='" + record.location + "' data-record-id='" + record.recordId + "' data-title='" + record.title + "' data-show-icons='" + record.showIcons + "'              data-show-description='" + record.showDescription + "' data-show-note='" + record.showNote + "' data-prefix='" + record.prefix + "'>" + token + "</li>";
    };
    RecordListDisplay.prototype.liDisplayRecordList = function () {
        var liRecordListHtml = '';
        for (var i = 0; i < this.recordList.recordList.length; i++) {
            if (this.recordList.recordList[i] !== undefined) {
                liRecordListHtml += this.liDisplayRecord(this.recordList.recordList[i]);
            }
        }
        return liRecordListHtml;
    };
    return RecordListDisplay;
}());
var RecordListSearch = (function () {
    function RecordListSearch(recordList) {
        this.recordList = recordList;
    }
    RecordListSearch.prototype.liRecordList = function (record) {
        return "<li class=\"db-list-item database-listing\" data-location='" + record.location + "' data-record-id='" + record.recordId + "' data-title=\"" + record.title  + "\"   data-show-icons='" + record.showIcons + "'              data-show-note='" + record.showNote + "' data-show-description='" + record.showDescription + "'  data-prefix='" + record.prefix + "'>             <span class=\"list-search-label\" title=\"" + record.title + "\">" + record.title + "</span>\n <button class=\"add-to-list-button pure-button pure-button-secondary\" title=\"Add to list\"> <i class=\"fa fa-plus\"></i></button>                          <div class=\"db-list-item-link\"><a href='" + record.location + "' target='_blank'>" + record.location + "</a></div>             </li>";
    };
    RecordListSearch.prototype.liDisplayRecordList = function () {
        var liRecordListHtml = '';
        for (var i = 0; i < this.recordList.recordList.length; i++) {
            if (this.recordList.recordList[i] !== undefined) {
                liRecordListHtml += this.liRecordList(this.recordList.recordList[i]);
            }
        }
        return liRecordListHtml;
    };
    RecordListSearch.prototype.getList = function () {
        return this.liDisplayRecordList();
    };
    ;
    return RecordListSearch;
}());
var RecordSearch = (function () {
    function RecordSearch() {
        // This takes a search string and then returns a RecordList based on the search results
        this.searchUrl = "../includes/autocomplete_data.php?collection=records";
        this.searchResults = new RecordList;
    }
    RecordSearch.prototype.search = function (searchTerm, limitAz, selectorId, callback) {
        var collection;
        limitAz === true ? collection = "azrecords" : collection = "records";
        this.searchResults = new RecordList;
        $.ajax({
            "url": this.searchUrl,
            "data": { term: searchTerm, collection: collection, limit_results_number: 1 }
        }).done(function (data) {
            callback(data);
            // console.log(data);
        }).fail(function (data) {
            console.log("Unable to perform search");
        });
    };
    RecordSearch.prototype.searchResultRecord = function (searchResult) {
        // console.log('searchresult: ' + searchResult.prefix);
        return new Record({ recordId: searchResult.id, title: searchResult.label, prefix: searchResult.prefix, location: searchResult['location_url'] });
    };
    return RecordSearch;
}());