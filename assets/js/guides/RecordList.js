/// <reference path="scripts/typings/jquery/jquery.d.ts" />
var Record = (function () {
    function Record(settings) {
        this.location = settings.location;
        if (settings.tokenString) {
            this.tokenString = settings.tokenString;
            var splitToken = settings.tokenString.split("{").join('').split("}").join('').split(',');
            this.recordId = splitToken[1];
            this.title = splitToken[2];
            this.displayOptions = splitToken[3];
            var optionsArray = this.displayOptions.toString().split('').map(Number);
            this.showIcons = optionsArray[0];
            this.showNote = optionsArray[1];
            this.showDescription = optionsArray[2];
        }
        else {
            this.recordId = settings.recordId;
            this.title = settings.title;
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
        var checkIcon;
        active === true ? checkIcon = "<i class='fa fa-check'></i>" : checkIcon = "<i class='fa fa-minus' ></i> ";
        toggleSpanHtml = "<span class='" + toggleClass + " db-list-toggle'>" + checkIcon + " " + label + "</span>";
        return toggleSpanHtml;
    };
    RecordListSortable.prototype.liSortableRecord = function (record) {
        var showIconToggle;
        var showDescriptionToggle;
        var showNotesToggle;
        (record.showIcons === 1) ? showIconToggle = this.sortableToggleSpan('show-icons-toggle', true, 'Icons') : showIconToggle = this.sortableToggleSpan('show-icons-toggle', false, 'Icons');
        (record.showDescription === 1) ? showDescriptionToggle = this.sortableToggleSpan('show-description-toggle', true, 'Description') : showDescriptionToggle = this.sortableToggleSpan('show-description-toggle', false, 'Description');
        (record.showNote === 1) ? showNotesToggle = this.sortableToggleSpan('include-note-toggle', true, 'Note') : showNotesToggle = this.sortableToggleSpan('include-note-toggle', false, 'Note');
        var liRecordHtml = "<li class='db-list-item-draggable' data-location='" + record.location + "'  \n             data-record-id='" + record.recordId + "' data-title='" + record.title + "' data-show-icons='" + record.showIcons + "'              data-show-note='" + record.showNote + "' data-show-description='" + record.showDescription + "'>             <span class='db-list-label'>" + record.title + "</span>  <span class='db-list-remove-item'><button class=\"pure-button pure-button-secondary\"><i class='fa fa-remove'></i></button></span>\n <div>             " + showIconToggle + showNotesToggle + " " + showDescriptionToggle + " </div>  </span></li>";
        return liRecordHtml;
    };
    RecordListSortable.prototype.liSortableRecordList = function () {
        var liRecordListHtml = '';
        for (var i = 0; i < this.recordList.recordList.length; i++) {
            if (this.recordList.recordList[i] != undefined) {
                var sortableLi = this.recordList.recordList[i];
                liRecordListHtml += this.liSortableRecord(sortableLi);
            }
        }
        return liRecordListHtml;
    };
    return RecordListSortable;
}());
var RecordListDisplay = (function () {
    function RecordListDisplay(recordList) {
        this.recordList = recordList;
    }
    RecordListDisplay.prototype.getList = function () {
        var recordListHtml = this.liDisplayRecordList();
        return "<ul class='link-list-display'>" + recordListHtml + "</ul>";
    };
    RecordListDisplay.prototype.liDisplayRecord = function (record) {
        var token = record.getRecordToken();
        return "<li data-location='" + record.location + "' data-record-id='" + record.recordId + "' data-title='" + record.title + "' data-show-icons='" + record.showIcons + "'              data-show-description='" + record.showDescription + "' data-show-note='" + record.showNote + "' >" + token + "</li>";
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
        return "<li class=\"db-list-item database-listing\" data-location='" + record.location + "' data-record-id='" + record.recordId + "' data-title=\"" + record.title + "\" data-show-icons='" + record.showIcons + "'              data-show-note='" + record.showNote + "' data-show-description='" + record.showDescription + "'>             <span class=\"list-search-label\" title=\"" + record.title + "\">" + record.title + "</span>\n <span>             <button class=\"add-to-list-button pure-button pure-button-secondary\"> <i class=\"fa fa-plus\"></i></button></span>                          <div class=\"db-list-item-link\"><a href='" + record.location + "' target='_blank'>" + record.location + "</a></div>             </li>";
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
            "data": { term: searchTerm, collection: "records" }
        }).done(function (data) {
            callback(data);
        }).fail(function (data) {
            console.log("Unable to perform search");
        });
    };
    RecordSearch.prototype.searchResultRecord = function (searchResult) {
        return new Record({ recordId: searchResult.id, title: searchResult.label, location: searchResult['location_url'] });
    };
    return RecordSearch;
}());