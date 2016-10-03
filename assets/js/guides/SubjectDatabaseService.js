/**
 * Created by cbrownroberts on 6/29/16.
 */

function subjectDatabaseService() {

    "use strict";

    var mySubjectDatabase = {

        databasesIdToDelete : new Array(),

        settings : {
            databaseActionUrl : "helpers/subject_databases_helper.php?",
            sortableDatabaseList : $('ul#database-list')
        },
        strings : {
            removeDatabaseBtn: "<a class='remove-database-btn' title='Remove Database from Subject'><i class='fa fa-trash fa-lg'></i> </a>"
        },
        bindUiActions : function() {
            mySubjectDatabase.databaseSearch();
            mySubjectDatabase.addDatabaseToSubject();
            mySubjectDatabase.displaySubjectDatabases();
            mySubjectDatabase.deleteDatabaseFromSubject();
            mySubjectDatabase.saveChanges();
        },
        init : function() {
            mySubjectDatabase.bindUiActions();
            mySubjectDatabase.hideSearchResultsContainer();
            mySubjectDatabase.hideGuideCollectionViewportContainer();
            mySubjectDatabase.hideGuideListContainer();
        },

        databaseSearch: function () {
            // Autocomplete search
            $(' #add-database-input').keyup(function (data) {
                if ($('#add-database-input').val().length > 2) {

                    var searchTerm = $('#add-database-input').val();
                    var url = '../includes/autocomplete_data.php?';
                    var collection = 'azrecords';
                    var payload = {
                        'term': searchTerm,
                        'collection': collection
                    };

                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        data: payload,
                        success: function(data) {
                            var result = '';
                            $.each(data, function (index, obj) {
                                var listCount = $('#database-list').find("li[title_id='"+obj.id+"']").length;

                                if (listCount == 0) {
                                    var addBtn = "<a class='add-database-btn' title='Add Database to Subject'><i class='fa fa-plus-circle'></i> </a>";
                                    result += '<li title_id="' + obj.id + '">' + addBtn + obj.label + '</li>';
                                }
                            });

                            $("#database-search-results").replaceWith('<ul id="database-search-results">' + result + '</ul>');
                        }
                    });
                }
            });
        },

        displaySubjectDatabases: function () {

            $('body').on('change', '#subjects', function () {
                        mySubjectDatabase.refreshSubjectDatabases();
            });

        },

        refreshSubjectDatabases: function(){
            var selected_item = $('#subjects').find(":selected");
            var subject_id = selected_item.attr('subject-id');
            mySubjectDatabase.databasesIdToDelete = new Array();

            if (subject_id)
            {
                mySubjectDatabase.clearDatabasesList();
                var payload = {
                    'action': 'fetchdatabases',
                    'subject_id': subject_id
                };

                $.ajax({
                    url: mySubjectDatabase.settings.databaseActionUrl,
                    type: "GET",
                    dataType: "json",
                    data: payload,
                    success: function (data) {

                        mySubjectDatabase.clearSearchResults();
                        mySubjectDatabase.showSearchResultsContainer();
                        mySubjectDatabase.showDatabaseListContainer();

                        var databases = data.databases;
                        $.each(databases, function (index, obj) {
                            var label = obj.title;
                            var subject_database_id = obj.subject_database_id;
                            var title_id = obj.title_id;
                            var record_status = obj.record_status;
                            var sort = obj.sort;
                            var rank_id = obj.rank_id;
                            var description_override = obj.description_override;
                            var source_id = obj.source_id;
                            var active_description_override = 'fa-inactive';

                            if (description_override) {
                                if (description_override.trim()) {
                                    active_description_override = '';
                                }
                            }

                            $('#database-list').prepend('<li subject_database_id="' + subject_database_id + '" title_id="' + title_id +
                                '"sort="' + sort + '"record_status="' + record_status + '" rank_id="'+ rank_id + '" source_id="' + source_id + '">' +
                                '<i class="fa fa-bars" aria-hidden="true"></i> ' +
                                label + mySubjectDatabase.strings.removeDatabaseBtn +
                                '<i class="fa fa-lg fa-file-text-o ' + active_description_override + ' note_override clickable" id="note_override-' + subject_database_id +
                                '" alt="Add Description Override" title="Add Description Override" border="0"></i><br>' +
                                    '<textarea id="description-override-textarea' + subject_database_id + '" class="description-override-text-area" style="clear: both; display: block" rows="4" cols="35"></textarea>' +
                                '</li>');

                            if (description_override) {
                                $('#description-override-textarea' + subject_database_id).val(description_override);
                            }
                        });

                        mySubjectDatabase.descriptionOverrideButtonBehavior();
                        mySubjectDatabase.descriptionOverrideTextAreaBehavior();
                        mySubjectDatabase.hideAllDescriptionOverrideTextAreas();
                        mySubjectDatabase.makeDatabasesSortable();
                        mySubjectDatabase.hideSaveChangesButtons();
                        mySubjectDatabase.showNoDataBasesMessage();
                    }
                });
            }else{
                mySubjectDatabase.clearSearchResults();
                mySubjectDatabase.clearDatabasesList();
            }
        },

        makeDatabasesSortable : function () {

            $('#database-list').sortable({
                axis: 'y',
                update: function (event, ui) {
                    mySubjectDatabase.orderItems();
                    mySubjectDatabase.showSaveChangesButtons();
                }
            });
        },

        orderItems : function (){
            var listItems = $("#database-list li");
            listItems.each(function(idx, li) {
                var item = $(li);
                item.attr( 'sort',item.index());
            });
        },

        descriptionOverrideButtonBehavior: function () {

            $( ".note_override").click(function(event) {
                debugger;
                $(this).parent().find('textarea').toggle();
                event.preventDefault();
                event.stopPropagation();
            });
        },

        descriptionOverrideTextAreaBehavior: function () {
            $('.description-override-text-area').bind('input propertychange', function() {
                mySubjectDatabase.showSaveChangesButtons();
            });
        },

        addDatabaseToSubject: function () {

            $('body').on('click', '.add-database-btn', function () {
                var clickedRow = $(this).closest('li');
                var clickedRowId = clickedRow.attr('title_id');
                var listCount = $('#database-list').find("li[subject_id='"+clickedRowId+"']").length;
                var listItemsCount = $('#database-list').find("li").length;

                if (listCount == 0) {
                    var label = clickedRow.text();

                    $('#database-list').append('<li sort="' + listItemsCount +'" title_id="' + clickedRowId + '">' +
                        '<i class="fa fa-bars" aria-hidden="true"></i> ' + label
                        + mySubjectDatabase.strings.removeDatabaseBtn +
                        '<i class="fa fa-lg fa-file-text-o fa-inactive note_override clickable" id="not-saved-override-button' + listItemsCount + '" alt="Add Description Override" title="Add Description Override" border="0"></i><br>' +
                        '<textarea id="description-override-textarea" class="description-override-text-area" style="clear: both; display: block" rows="4" cols="35"></textarea>' +
                        '</li>');


                    $('#database-list').find('#not-saved-override-button'+listItemsCount).click(function(event) {
                        debugger;
                        $(this).parent().find('textarea').toggle();
                        event.preventDefault();
                        event.stopPropagation();
                    });


                    clickedRow.remove();
                    if (!$(mySubjectDatabase.settings.sortableDatabaseList).hasClass('.ui-sortable')) {
                        mySubjectDatabase.makeDatabasesSortable();
                    }

                    mySubjectDatabase.orderItems();
                    mySubjectDatabase.showSaveChangesButtons();
                    mySubjectDatabase.descriptionOverrideTextAreaBehavior();
                    mySubjectDatabase.hideAllDescriptionOverrideTextAreas();

                    if ($('#database-list-no-items').is(':visible')){
                        $('#database-list-no-items').hide();
                    }

                }else{
                    clickedRow.remove();
                }
            });
        },

        deleteDatabaseFromSubject: function () {

            $('body').on('click', '.remove-database-btn', function () {

                var listItem = $(this).closest('li');
                if (typeof listItem.attr('subject_database_id') !== typeof undefined && listItem.attr('subject_database_id') !== false) {
                    mySubjectDatabase.databasesIdToDelete.push(listItem.attr('subject_database_id'));
                }
                $(listItem).remove();
                var databaseInput = $('#add-database-input');
                if (listItem.text().toLowerCase().indexOf(databaseInput.val().toLowerCase()) !== -1){
                    var addBtn = "<a class='add-database-btn' title='Add Database to Subject'><i class='fa fa-plus-circle'></i> </a>";
                    var itemToSearchResults = '<li sort="' + listItem.attr('sort')+'" title_id="' + listItem.attr('title_id') + '">' + addBtn + listItem.text() + '</li>';

                    $('#database-search-results').prepend(itemToSearchResults);
                }

                mySubjectDatabase.showSaveChangesButtons();
                mySubjectDatabase.showNoDataBasesMessage();

            });
        },

        saveChanges: function () {

            $('body').on('click', '#update-databases-btn', function () {

                var selected_item = $('#subjects').find(":selected");
                var subject_id = selected_item.attr('subject-id');

                var deleteListCount = mySubjectDatabase.databasesIdToDelete.length;
                for (var i = 0; i < deleteListCount; i++) {
                    var payload = {
                        'action': 'delete',
                        'subject_database_id': mySubjectDatabase.databasesIdToDelete[i]
                    };
                    $.ajax({
                        url: mySubjectDatabase.settings.databaseActionUrl,
                        type: "POST",
                        dataType: "json",
                        data: payload
                    });
                }

                var total = $('#database-list li').length;
                $('#database-list li').each(function(index) {
                    var title_id =  $(this).attr('title_id');
                    var sort =  $(this).attr('sort');
                    var subject_database_id =  $(this).attr('subject_database_id');
                    var description_override = $(this).find('textarea').val();

                    var payload = {
                        'action': 'update',
                        'subject_database_id': subject_database_id,
                        'subject_id' : subject_id,
                        'title_id' : title_id,
                        'sort' : sort,
                        'description_override' : description_override
                    };

                    $.ajax({
                        url: mySubjectDatabase.settings.databaseActionUrl,
                        type: "POST",
                        dataType: "json",
                        data: payload,
                        success: function() {
                            if (index === total - 1){
                                mySubjectDatabase.refreshSubjectDatabases();
                            }
                        }
                    });
                });

                $('#update-databases-btn').hide();

            });
        },

        showNoDataBasesMessage: function () {
            if($('#database-list li').length == 0) {
                if($('#database-list-no-items').length == 0) {
                    $('#database-list').prepend("<h4 id='database-list-no-items'>There are not databases assigned to this subject.</h4>");
                }else{
                    $('#database-list-no-items').show();
                }
            }
        },

        errorDialog: function (selector) {
            $( selector ).dialog({
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        },


        clearFlashMsg: function () {
            $('#flash-msg').html(' ');
        },
        renderFlashMsg: function (msg) {
            mySubjectDatabase.clearFlashMsg();
            $('#flash-msg').append(msg).addClass( 'success-msg' );
        },

        showCollectionMetadataContainer: function () {
            $('#collection-metadata').show();
        },

        hideCollectionMetadataContainer: function () {
            $('#collection-metadata').hide();
        },

        clearCollectionMetadata: function () {
            $('#collection-title').html(' ');
            $('#collection-description').html('');
            $('#collection-shortform').html('');
        },

        renderCollectionMetadata: function (title, description, shortform, collection_id) {
            $('#collection-metadata').attr('data-collection_id', collection_id);
            $('#collection-title').attr('data-collection_id', collection_id);
            $('#collection-title').append(title);
            $('#collection-description').append(description);
            $('#collection-shortform').append(shortform);
        },

        clearEditFormValues: function () {
            $('#collection-title-input').val('');
            $('#collection-description-input').val('');
            $('#collection-shortform-input').val('');
        },

        showGuideCollectionViewportContainer: function () {
            $('#guide-collection-viewport-container').show();
        },

        showSaveChangesButtons: function () {
            $('#update-databases-btn').show();
        },

        hideSaveChangesButtons: function () {
            $('#update-databases-btn').hide();
        },

        hideAllDescriptionOverrideTextAreas: function () {
            $('.description-override-text-area').hide();
        },

        hideGuideCollectionViewportContainer: function () {
            $('#guide-collection-viewport-container').hide();
        },

        showSearchResultsContainer: function () {
            $('#search-results-container').show()
        },

        hideSearchResultsContainer: function () {
            $('#search-results-container').hide();
        },

        clearSearchResults: function () {
            $('#add-database-input').val('');
            $('#database-search-results').empty();
        },

        showDatabaseListContainer: function () {
            $('#database-list-container').show();
        },

        hideGuideListContainer: function () {
            $('#guide-list-container').hide();
        },

        clearDatabasesList: function () {
            $('#database-list').empty();
        }

    };

    return mySubjectDatabase;
}