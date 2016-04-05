/// <reference path="scripts/typings/jquery/jquery.d.ts" />
var Record = (function () {
    function Record(tokenString, location) {
        this.location = location;
        this.tokenString = tokenString;
        var splitToken = tokenString.split("{").join('').split("}").join('').split(',');
        this.recordId = splitToken[1];
        this.title = splitToken[2];
        this.displayOptions = splitToken[3];
        var optionsArray = this.displayOptions.toString().split('').map(Number);
        this.showIcons = optionsArray[0];
        this.showNote = optionsArray[1];
        this.showDescription = optionsArray[2];
    }
    Record.prototype.getRecordToken = function () {
        var displayOptions = "" + this.showIcons + this.showNote + this.showDescription;
        return "{{dab},{" + this.recordId + "},{" + this.title + "},{" + displayOptions + "}}";
        ;
    };
    return Record;
}());
var RecordList = (function () {
    function RecordList() {
        this.recordList = [];
    }
    RecordList.prototype.addToList = function (record) {
        ;
        this.recordList.push(record);
    };
    RecordList.prototype.getList = function () {
        return this.recordList;
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
        ;
    };
    RecordListSortable.prototype.sortableToggleSpan = function (toggleClass, active, label) {
        var toggleSpanHtml;
        var checkIcon;
        active === true ? checkIcon = "<i class='fa fa-check'></i>" : checkIcon = "<i class='fa fa-minus' ></i> ";
        toggleSpanHtml = "<span class='" + toggleClass + " db-list-toggle'>" + checkIcon + " " + label + "</span>";
        console.log(toggleSpanHtml);
        return toggleSpanHtml;
    };
    RecordListSortable.prototype.liSortableRecord = function (record) {
        console.log(record);
        var label = record.title;
        var showIconToggle;
        var showDescriptionToggle;
        var showNotesToggle;
        var liRecordHtml = '';
        (record.showIcons === 1) ? showIconToggle = this.sortableToggleSpan('show-icons-toggle', true, 'Icons') : showIconToggle = this.sortableToggleSpan('show-icons-toggle', false, 'Icons');
        (record.showDescription === 1) ? showDescriptionToggle = this.sortableToggleSpan('show-description-toggle', true, 'Description') : showDescriptionToggle = this.sortableToggleSpan('show-description-toggle', false, 'Description');
        (record.showNote === 1) ? showNotesToggle = this.sortableToggleSpan('include-note-toggle', true, 'Note') : showNotesToggle = this.sortableToggleSpan('include-note-toggle', false, 'Note');
        console.log(showIconToggle);
        liRecordHtml = "<li class='db-list-item-draggable' data-token='" + record.tokenString + "'>        <span class='db-list-label'>" + label + "</span>        <div>          " + showIconToggle + showNotesToggle + " " + showDescriptionToggle + "           </div>         </span>         </li>";
        console.log(liRecordHtml);
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
        return "<li>" + token + "</li>";
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
        return "<li class=\"db-list-item database-listing\">             <div class=\"pure-g\">             <div class=\"pure-u-4-5 list-search-label\" title=\"" + record.title + "\">" + record.title + "</div>\n            <div class=\"pure-u-1-5\" style=\"text-align:right;\">             <button data-label='" + record.title + "' value='" + record.recordId + "'             class=\"add-to-list-button pure-button pure-button-secondary\">            <i class=\"fa fa-plus\"></i></button></div></div>             <div><a href='" + record.location + "' target='_blank'>" + record.location + "</a></div>             </li>";
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
        this.searchUrl = "../includes/autocomplete_data.php?collection=records";
        this.searchResults = new RecordList;
    }
    RecordSearch.prototype.search = function (searchTerm, limitAz, selectorId, callback) {
        var collection;
        limitAz === true ? collection = "azrecords" : collection = "records";
        this.searchResults = new RecordList;
        $.ajax({
            url: this.searchUrl,
            data: { term: searchTerm, collection: "records" }
        }).done(function (data) {
            callback(data);
        }).fail(function (data) {
            console.log("Unable to perform search");
        });
    };
    RecordSearch.prototype.searchResultRecord = function (searchResult) {
        var tokenString = "{{dab},{" + searchResult.id + "},{" + searchResult.label + "},{000}}}";
        return new Record(tokenString, searchResult.location_url);
    };
    return RecordSearch;
}());
