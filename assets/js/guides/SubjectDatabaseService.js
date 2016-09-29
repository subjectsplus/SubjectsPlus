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
            removeGuideBtn: "<a class='remove-guide-btn' title='Remove Database from Subject'><i class='fa fa-trash fa-lg'></i> </a>"
        },
        bindUiActions : function() {

            mySubjectDatabase.addCollection();
            mySubjectDatabase.deleteCollection();
            mySubjectDatabase.guideSearch();
            mySubjectDatabase.addGuideToCollection();
            mySubjectDatabase.updateCollection();
            mySubjectDatabase.displaySubjectDatabases();
            mySubjectDatabase.deleteGuideFromCollection();
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
                    mySubjectDatabase.showGuideListContainer();
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


        updateCollection: function () {

            $('body').on('click', '#edit-collection-metadata-btn', function () {

                var collection_id = $(this).parent('#collection-metadata').attr('data-collection_id');
                var title = $(this).parent('#collection-metadata').children('h3#collection-title').text();
                var shortform = $(this).parent('#collection-metadata').children('#collection-shortform').text();
                var description = $(this).parent('#collection-metadata').children('#collection-description').text();

                $('#collection-metadata-editform').show();

                $('input#collection-title-input').val(title);
                $('input#collection-shortform-input').val(shortform);
                $('input#collection-description-input').val(description);

                $('body').on('click', '#update-collection-metadata-btn', function () {


                    var title = $('input#collection-title-input').val();
                    var shortform = $('input#collection-shortform-input').val();
                    var description =  $('input#collection-description-input').val();

                    var payload = {
                        'action': 'update',
                        'collection_id' : collection_id,
                        'title' : title,
                        'shortform' : shortform,
                        'description' : description
                    };

                    $.ajax({
                        url: mySubjectDatabase.settings.databaseActionUrl,
                        type: "POST",
                        dataType: "json",
                        data: payload,
                        success: function(data) {

                            mySubjectDatabase.clearEditFormValues();
                            mySubjectDatabase.clearCollectionMetadata();

                            $( "#collection-metadata" ).fadeIn( "slow", function() {
                                mySubjectDatabase.renderCollectionMetadata(title, description,shortform, collection_id);
                            });

                        }
                    });

                    $('#collection-metadata-editform').hide();

                });

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


        guideSearch: function () {
            // Autocomplete search
            $(' #add-guide-input').keyup(function (data) {
                if ($('#add-guide-input').val().length > 2) {

                    var searchTerm = $('#add-guide-input').val();
                    var url = '../includes/autocomplete_data.php?';
                    var collection = 'all_guides';
                    //showInactive === true ? collection = "all_guides" : collection = "guides";
                    var payload = {
                        'term': searchTerm,
                        'collection': collection
                    };

                    $('#guide-search-results').empty();

                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        data: payload,
                        success: function(data) {
                            //console.log(data);

                            $.each(data, function (index, obj) {
                                var addBtn = "<a class='add-guide-btn' title='Add Guide to Collection'><i class='fa fa-plus-circle'></i> </a>";

                                $('#guide-search-results').prepend('<li data-guide_id="' + obj.id + '">' + addBtn + obj.label + '</li>');

                            })
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

                            //show guide collection viewport container
                            mySubjectDatabase.showGuideCollectionViewportContainer();

                            //clear any collection metadata
                            mySubjectDatabase.clearCollectionMetadata();

                            //render collection metadata
                            mySubjectDatabase.renderCollectionMetadata(title, description, shortform, collection_id);

                            //clear form values
                            mySubjectDatabase.clearAddFormValues();

                            //clear search results
                            mySubjectDatabase.clearSearchResults();

                            //render search results
                            mySubjectDatabase.showSearchResultsContainer();

                            //render guide list container
                            mySubjectDatabase.showGuideListContainer();

                            //render guides
                            var guides = data.guides;
                            $.each(guides, function (index, obj) {
                                var label = obj.subject;
                                var item = obj.collection_subject_id;
                                var subject_id = obj.subject_id;
                                $('#guide-list').prepend('<li id="item_' + item + '" ' +
                                    'data-guide_id="' + subject_id + '">' +
                                    '<i class="fa fa-bars" aria-hidden="true"></i> ' +
                                    label + mySubjectDatabase.strings.removeGuideBtn + '</li>');

                            });


                        }
                    });

                    mySubjectDatabase.makeGuidesSortable(collection_id);
                }else{
                    alert("Choose subject selected");
                }
            });

        },

        makeGuidesSortable : function (collection_id) {

            $('#guide-list').sortable({

                axis: 'y',
                update: function (event, ui) {
                    var data = 'action=sortguides&' + $(this).sortable('serialize');

                    $.ajax({
                        url: mySubjectDatabase.settings.databaseActionUrl,
                        type: "POST",
                        data: data,
                        success: function(data) {
                            console.log(data);
                        }
                    });

                }

            });
        },


        addGuideToCollection: function () {

            $('body').on('click', '.add-guide-btn', function () {
                var label = $(this).closest('li').text();
                var collection_id = $('#collection-title').attr('data-collection_id');
                var subject_id =  $(this).closest('li').attr('data-guide_id');
                var payload = {
                    'action' : 'addguide',
                    'collection_id' : collection_id,
                    'subject_id': subject_id
                };

                $.ajax({
                    url: mySubjectDatabase.settings.databaseActionUrl,
                    type: "POST",
                    dataType: "json",
                    data: payload,
                    success: function(data) {
                        console.log(data);
                        $('#guide-list').append( '<li id="item_' + data.lastInsertId + '" ' +
                            'data-guide_id="' + subject_id + '">' +
                            '<i class="fa fa-bars" aria-hidden="true"></i> ' +
                            label + mySubjectDatabase.strings.removeGuideBtn + '</li>' );
                    }
                });

                if( !$(mySubjectDatabase.settings.sortableDatabaseList).hasClass('.ui-sortable') ) {
                    mySubjectDatabase.makeGuidesSortable(collection_id);
                }

            });
        },

        deleteGuideFromCollection: function () {

            $('body').on('click', '.remove-guide-btn', function () {

                var listItem = $(this).closest('li');

                var payload = {
                    'action' : 'removeguide',
                    'collection_id' : $('#collection-title').attr('data-collection_id'),
                    'subject_id': $(this).closest('li').attr('data-guide_id')
                };

                console.log(payload);

                $.ajax({
                    url: mySubjectDatabase.settings.databaseActionUrl,
                    type: "POST",
                    dataType: "json",
                    data: payload,
                    success: function(data) {
                        console.log(data);
                        $(listItem).remove();

                    }
                });

            });
        },

        validateForm: function () {

            var isValid = false;

            if( !$('input[name="title"]').val() ) {
                mySubjectDatabase.errorDialog('#error-dialog-title');
            } else if ( !$('input[name="shortform"]').val() ) {
                mySubjectDatabase.errorDialog('#error-dialog-shortform');
            } else {
                isValid = true;
            }

            return isValid;
        },

        shortformDupeCheck: function (shortform) {

            var payload = {
                'action' : 'validateshortform',
                'shortform' : shortform,
            };


            var isDup = (function () {
                var isDup = true;
                $.ajax({
                    url: mySubjectDatabase.settings.databaseActionUrl,
                    type: "GET",
                    async: false,
                    data: payload,
                    'success': function (data) {
                        isDup = data.shortform.shortform;

                        console.log(isDup);

                    }
                });
                console.log(isDup);
                return isDup;
            })();


            if (isDup == undefined) {
                console.log(isDup);
                return false;

            } else {
                console.log(isDup);
                mySubjectDatabase.errorDialog('#error-dialog-shortform-dup');
                return true;
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

        clearAddFormValues: function () {
            $('#title').val('');
            $('#description').val('');
            $('#shortform').val('');
            $('#add-guide-input').val();
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
            $('#guide-search-results').empty();
        },

        showGuideListContainer: function () {
            $('#guide-list-container').show();
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