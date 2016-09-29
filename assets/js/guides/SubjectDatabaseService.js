/**
 * Created by cbrownroberts on 6/29/16.
 */

function subjectDatabaseService() {

    "use strict";

    var mySubjectDatabase = {

        settings : {
            databaseActionUrl : "helpers/subject_databases_helper.php?",
            sortableDatabaseList : $('ul#database-list')
        },
        strings : {
            removeDatabaseBtn: "<a class='remove-database-btn' title='Remove Database from Subject'><i class='fa fa-trash fa-lg'></i> </a>"
        },
        bindUiActions : function() {

            mySubjectDatabase.addCollection();
            mySubjectDatabase.deleteCollection();
            mySubjectDatabase.databaseSearch();
            mySubjectDatabase.addDatabaseToSubject();
            mySubjectDatabase.displaySubjectDatabases();
            mySubjectDatabase.deleteDatabaseFromSubject();
        },
        init : function() {
            mySubjectDatabase.bindUiActions();
            mySubjectDatabase.hideSearchResultsContainer();
            mySubjectDatabase.hideGuideCollectionViewportContainer();
            mySubjectDatabase.hideGuideListContainer();
        },

        fetchCollectionRequest: function (id) {

            var payload = {
                'action': 'fetchone',
                'collection_id' : id
            };


            $.ajax({
                url: mySubjectDatabase.settings.databaseActionUrl,
                type: "GET",
                dataType: "json",
                data: payload,
                success: function(data) {

                    var obj = data.collection;

                    $("#guide-collection-list").prepend( "<li " +
                        "data-collection_id='" + obj.collection_id + "'" +
                        "data-label='" + obj.title + "'" +
                        "data-description='" + obj.description + "'" +
                        " id='item_"+ obj.collection_id +"' class='' title='" + obj.title + "'> " +
                        "<a id='display-guides-btn'><i class='fa fa-pencil fa-lg'></i></a> " +obj.title +
                        "<a id='delete-collection-btn'> <i class='fa fa-trash'></i></a></li>");

                    $('#item_' + obj.collection_id).effect( "highlight" );

                    mySubjectDatabase.clearCollectionMetadata();
                    mySubjectDatabase.clearAddFormValues();
                    mySubjectDatabase.renderFlashMsg(obj.title + ' Collection Created');
                    mySubjectDatabase.renderCollectionMetadata(obj.title, obj.description, obj.shortform, obj.collection_id);
                    mySubjectDatabase.showGuideCollectionViewportContainer();
                    mySubjectDatabase.showSearchResultsContainer();
                    mySubjectDatabase.showDatabaseListContainer();
                }
            });

        },

        addCollection: function () {
            $('#add_collection').on('click', function(event) {

                event.preventDefault();

                var isValid = mySubjectDatabase.validateForm();

                //clear guide list
                mySubjectDatabase.clearDatabasesList();

                if( $('input[id="shortform"]').val() ) {
                    var shortform = $('input[id="shortform"]').val();
                } else if ( $('input[id="collection-shortform-input"]').val() ) {
                    var shortform = $('input[id="collection-shortform-input"]').val();
                }

                var collection = new GuideCollection({
                    title: $('#title').val(),
                    description: $('#description').val(),
                    shortform: shortform
                });

                var isDup = mySubjectDatabase.shortformDupeCheck(collection.shortform);

                if(isValid == true && isDup == false) {
                    //add collection to db and display collection metadata
                    mySubjectDatabase.addCollectionRequest(collection);

                }

            });
        },

        addCollectionRequest: function (collection) {
            var payload = {
                'action'      : 'create',
                'title'       : collection.title,
                'description' : collection.description,
                'shortform'   : collection.shortform
            };

            $.ajax({
                url: mySubjectDatabase.settings.databaseActionUrl,
                type: "POST",
                dataType: "json",
                data: payload,
                success: function(data) {
                    mySubjectDatabase.fetchCollectionRequest(data.lastInsertId);
                }
            });
        },


        deleteCollection: function() {

            $('body').on('click', '#delete-collection-btn', function () {

                alert('Are you sure?');

                var elementDestoyed = $(this).parent('li');
                var payload = {
                    'action' : 'delete',
                    'collection_id' : $(this).parent('li').attr('data-collection_id'),
                };

                $.ajax({
                    url: mySubjectDatabase.settings.databaseActionUrl,
                    type: "POST",
                    dataType: "json",
                    data: payload,
                    success: function(data) {
                        //render flash msg
                        mySubjectDatabase.renderFlashMsg('Collection Deleted');

                        //remove element from collection list
                        $(elementDestoyed).remove();

                        //clear collection metadata
                        mySubjectDatabase.clearCollectionMetadata();

                        //clear form value
                        mySubjectDatabase.clearAddFormValues();

                        //clear search results
                        mySubjectDatabase.clearSearchResults();

                        //clear guide list
                        mySubjectDatabase.clearDatabasesList();

                        //hide the guide container viewport container
                        mySubjectDatabase.hideGuideCollectionViewportContainer();
                    }
                });
            });
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
                                var listCount = $('#database-list').find("li[data-subject_id='"+obj.id+"']").length;

                                if (listCount == 0) {
                                    var addBtn = "<a class='add-database-btn' title='Add Database to Subject'><i class='fa fa-plus-circle'></i> </a>";
                                    result += '<li data-subject_id="' + obj.id + '">' + addBtn + obj.label + '</li>';
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

                var selected_item = $(this).find(":selected");
                var subject_id = selected_item.attr('subject-id');
                var name = selected_item.text();

                if (subject_id)
                {
                    //clear databases list
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

                            //clear search results
                            mySubjectDatabase.clearSearchResults();

                            //render search results
                            mySubjectDatabase.showSearchResultsContainer();

                            //render database list container
                            mySubjectDatabase.showDatabaseListContainer();

                            //render databases
                            var databases = data.databases;
                            $.each(databases, function (index, obj) {
                                var label = obj.title;
                                var item = obj.rank_id;
                                var subject_id = obj.subject_id;
                                var active = obj.eres_display;
                                $('#database-list').prepend('<li id="item_' + item + '"active_record="' + active + '" ' +
                                    'data-guide_id="' + subject_id + '">' +
                                    '<i class="fa fa-bars" aria-hidden="true"></i> ' +
                                    label + mySubjectDatabase.strings.removeDatabaseBtn + '</li>');

                            });


                        }
                    });

                    mySubjectDatabase.makeDatabasesSortable();
                }else{
                    alert("Choose subject selected");
                }
            });

        },

        makeDatabasesSortable : function () {

            $('#database-list').sortable({
                axis: 'y',
                update: function (event, ui) {
                    var listItems = $("#database-list li");
                    listItems.each(function(idx, li) {
                        var item = $(li);
                        item.attr( 'item_rank',item.index());
                    });
                }
            });
        },


        addDatabaseToSubject: function () {

            $('body').on('click', '.add-database-btn', function () {
                var clickedRow = $(this).closest('li');
                var clickedRowId = clickedRow.attr('data-subject_id');
                var listCount = $('#database-list').find("li[data-subject_id='"+clickedRowId+"']").length;
                var listItemsCount = $('#database-list').find("li").length;

                debugger;
                if (listCount == 0) {
                    var label = clickedRow.text();

                    $('#database-list').append('<li item_rank="' + listItemsCount +'" data-subject_id="' + clickedRowId + '">' +
                        '<i class="fa fa-bars" aria-hidden="true"></i> ' + label
                        + mySubjectDatabase.strings.removeDatabaseBtn + '</li>');

                    clickedRow.remove();


                    if (!$(mySubjectDatabase.settings.sortableDatabaseList).hasClass('.ui-sortable')) {
                        mySubjectDatabase.makeDatabasesSortable();
                    }
                }else{
                    clickedRow.remove();
                }

            });
        },

        deleteDatabaseFromSubject: function () {

            $('body').on('click', '.remove-database-btn', function () {
                var listItem = $(this).closest('li');
                $(listItem).remove();
            });
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