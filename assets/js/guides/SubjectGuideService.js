function subjectGuideService() {

    "use strict";

    var mySubjectGuide = {

        guidesIdToDelete : new Array(),

        settings : {
            guideActionUrl : "helpers/subject_code_guides_helper.php?"
        },
        strings : {
            removeDatabaseBtn: "<a class='remove-guide-btn' title='Remove Guide from Subject Code'><i class='fa fa-trash fa-lg'></i> </a>"
        },
        bindUiActions : function() {
            mySubjectGuide.guideSearch();
            mySubjectGuide.addGuideToSubject();
            mySubjectGuide.displaySubjectCodeGuides();
            mySubjectGuide.deleteGuideFromSubjectCode();
            mySubjectGuide.saveChanges();
        },
        init : function() {
            mySubjectGuide.bindUiActions();
            mySubjectGuide.hideSearchResultsContainer();
        },

        guideSearch: function () {
            // Autocomplete search
            $(' #add-guide-input').keyup(function (data) {
                if ($('#add-guide-input').val().length > 2) {
                    var searchTerm = $('#add-guide-input').val();
                    var url = '../includes/autocomplete_data.php?';
                    var payload = {
                        'term': searchTerm,
                        'collection': 'guides'
                    };
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        data: payload,
                        success: function(data) {
                            var result = '';
                            $.each(data, function (index, obj) {
                                var listCount = $('#guide-list').find("li[subject_id='"+obj.id+"']").filter(':visible').length;

                                if (listCount == 0) {
                                    var addBtn = "<a class='add-guide-btn' title='Add Guide to Subject Code'><i class='fa fa-plus-circle'></i> </a>";
                                    result += '<li guide_id="' + obj.id + '">' + addBtn + obj.label + '</li>';
                                }
                            });

                            $("#guide-search-results").replaceWith('<ul id="guide-search-results">' + result + '</ul>');
                        }
                    });
                }
            });
        },

        displaySubjectCodeGuides: function () {
            $('body').on('change', '#subject_codes', function () {
                mySubjectGuide.refreshSubjectCodeGuides();
                mySubjectGuide.addToEditing();
            });
        },

        addToEditing: function () {
            var selected_item = $('#subject_codes').find(":selected");
            var selected_subject_code_id = selected_item.attr('subject-code-id');
            var selectedCodeForEdition = $("#current-selection-editing").children().length;
            var codeInCurrentAssociations = $("#current-associations-list").find("dt[data-subject-code='" + selected_subject_code_id + "']");
            var codeGuidesList = $("#current-associations-list").find("dd[data-subject-code='" + selected_subject_code_id + "']");

            if (codeInCurrentAssociations.length == 1){
                mySubjectGuide.setCurrentCodeAsEditing(codeInCurrentAssociations, codeGuidesList);
                if (selectedCodeForEdition != 0){
                    mySubjectGuide.cleanSelectedForEditing(selected_subject_code_id);
                }
            }else{
                mySubjectGuide.cleanSelectedForEditing("");
            }
        },

        cleanSelectedForEditing: function (selected_subject_code_id) {
            var editingNow = $('#current-selection-editing');
            var currentAssociationsList = $('#current-associations-list');
            $(editingNow).children().each(function (i, element) {
                if ($(element).attr('data-subject-code') !== selected_subject_code_id){
                    var temp = $(element).detach();
                    $(currentAssociationsList).append(temp);
                }
            });

            if (!selected_subject_code_id){
                editingNow.parent().hide();
            }
        },

        setCurrentCodeAsEditing: function (codeInCurrentAssociations, codeGuidesList) {
            var editingList = $('#current-selection-editing');
            editingList.append(codeInCurrentAssociations);
            editingList.append(codeGuidesList);
            var editing = $('#code-editing-list');
            editing.show();
        },

        refreshSubjectCodeGuides: function () {
            var selected_item = $('#subject_codes').find(":selected");
            var subject_code_id = selected_item.attr('subject-code-id');
            mySubjectGuide.guidesIdToDelete = new Array();
            mySubjectGuide.hideNoGuidesMessage();

            if (subject_code_id) {
                mySubjectGuide.clearGuidesList();
                var payload = {
                    'action': 'fetch_subject_code_guides',
                    'subject_code_id': subject_code_id
                };
                $.ajax({
                    url: mySubjectGuide.settings.guideActionUrl,
                    type: "GET",
                    dataType: "json",
                    data: payload,
                    success: function (data) {

                        mySubjectGuide.clearSearchResults();
                        mySubjectGuide.showSearchResultsContainer();
                        mySubjectGuide.showDatabaseListContainer();

                        var guides = data.guides;
                        $.each(guides, function (index, obj) {
                            var subject_id = obj.subject_id;
                            var subject = obj.subject;
                            var instructor = obj.instructor === null ? '' : obj.instructor;

                            if (instructor) {
                                instructor = '<br><div data-instructor="' + instructor +'">Instructor: ' + instructor + '</div>';
                            }
                            $('#guide-list').prepend('<li subject_id="' + subject_id + '">' + subject + instructor + '<a class="remove-guide-btn"><i class="fa fa-trash fa-lg"></i></a></li>');
                        });

                        mySubjectGuide.hideSaveChangesButtons();
                        mySubjectGuide.showNoGuidesMessage();
                    }
                });
            } else {
                mySubjectGuide.clearSearchResults();
                mySubjectGuide.hideSearchResultsContainer();
                mySubjectGuide.cleanSelectedForEditing("");
                mySubjectGuide.clearDatabasesList();
            }
        },

        addGuideToSubject: function () {
            $('body').on('click', '.add-guide-btn', function () {
                var clickedRow = $(this).closest('li');
                var subject_id = clickedRow.attr('guide_id');
                var listCount = $('#guide-list').find("li[subject_id='"+subject_id+"']").length;
                var listItemsCount = $('#guide-list').find("li").length;
                var label = clickedRow.text();
                var subject_code_id = $('#subject_codes').find(":selected").attr('subject-code-id');
                var codeInCurrentAssociations = $("#current-associations-list").find("dt[data-subject-code='" + subject_code_id + "']");
                var codeGuidesList = $("#current-associations-list").find("dd[data-subject-code='" + subject_code_id + "']");
                var editingAssociationList = $("#current-selection-editing").find("dt[data-subject-code='" + subject_code_id + "']");

                if (codeInCurrentAssociations.length == 1){
                    mySubjectGuide.setCurrentCodeAsEditing(codeInCurrentAssociations, codeGuidesList);
                    if (selectedCodeForEdition != 0){
                        mySubjectGuide.cleanSelectedForEditing(label);
                    }
                }else{
                    if (editingAssociationList.length == 0){
                        $("#current-selection-editing").append('<dt data-subject-code="'+subject_code_id+'">'+subject_code_id+'</dt>');
                    }

                    $("#current-selection-editing").append('' +
                        '<dd subject_id="'+subject_id+'" data-subject-code="'+subject_code_id+'">'+label + mySubjectGuide.strings.removeDatabaseBtn +'</dd>');
                    clickedRow.remove();
                    var editing = $('#code-editing-list');
                    editing.show();
                    var this_element = $("#current-selection-editing last-child").

                    debugger;
                    mySubjectGuide.saveInsertAssociation(this_element, subject_id, subject_code_id);
                }

                // if (listCount == 0) {
                //
                //     var hidden = $('#guide-list').find("li[subject_id='"+clickedRowId+"']").length;
                //
                //     if (hidden > 0){
                //         $('#guide-list').find("li[subject_id='"+clickedRowId+"']").show();
                //         // var index = mySubjectGuide.guidesIdToDelete.indexOf(rank_id);
                //         // if(mySubjectGuide.guidesIdToDelete.indexOf(rank_id) != -1){
                //         //     var index = mySubjectGuide.guidesIdToDelete.indexOf(rank_id);
                //         //     mySubjectGuide.guidesIdToDelete.splice(index, 1);
                //         // }
                //         clickedRow.remove();
                //     }else {
                //         if(mySubjectGuide.guidesIdToDelete.indexOf(subject_id) != -1){
                //             var index = mySubjectGuide.guidesIdToDelete.indexOf(subject_id);
                //             mySubjectGuide.guidesIdToDelete.splice(index, 1);
                //         }
                //         $('#guide-list').prepend('<li subject_id="' + clickedRowId + '">' +  label + mySubjectGuide.strings.removeDatabaseBtn);
                //         clickedRow.remove();
                //         mySubjectGuide.showSaveChangesButtons();
                //     }
                // }else{
                //     $('#guide-list').prepend('<li subject_id="' + clickedRowId + '">' + label + mySubjectGuide.strings.removeDatabaseBtn);
                //     clickedRow.remove();
                // }
            });
        },

        saveInsertAssociation: function (guide_id, subject_code_id) {
            var payload = {
                'action': 'saveChanges',
                'guide_id': guide_id,
                'subject_code_id': subject_code_id
            };
            $.ajax({
                url: mySubjectGuide.settings.guideActionUrl,
                type: "POST",
                dataType: "json",
                data: payload,
                success: function (data) {
                    if (index === total - 1) {
                        mySubjectGuide.refreshSubjectCodeGuides();
                    }
                }
            });
        },

        deleteGuideFromSubjectCode: function () {
            $('body').on('click', '.remove-guide-btn', function () {
                var listItem = $(this).closest('li');
                if (typeof listItem.attr('subject_id') !== typeof undefined && listItem.attr('subject_id') !== false) {
                    mySubjectGuide.guidesIdToDelete.push(listItem.attr('subject_id'));
                }
                $(listItem).hide();
                var guideInput = $('#add-guide-input');
                var addBtn = "<a class='add-guide-btn' title='Add Guide to Subject Code'><i class='fa fa-plus-circle'></i> </a>";

                var itemToSearchResults = '<li guide_id="' + listItem.attr('subject_id') + '" >' + addBtn + listItem.text() + '</li>';

                $('#guide-search-results').prepend(itemToSearchResults);

                mySubjectGuide.showSaveChangesButtons();
                mySubjectGuide.showNoGuidesMessage();
            });
        },

        saveChanges: function () {

                var selected_item = $('#subject_codes').find(":selected");
                var subject_code_id = selected_item.attr('subject-code-id');

                var deleteListCount = mySubjectGuide.guidesIdToDelete.length;
                for (var i = 0; i < deleteListCount; i++) {
                    var payload = {
                        'action': 'saveChanges',
                        'guide_id': mySubjectGuide.guidesIdToDelete[i]
                    };
                    $('#guides-list').find("li[subject_id='"+mySubjectGuide.guidesIdToDelete[i]+"']").remove();
                    $.ajax({
                        url: mySubjectGuide.settings.guideActionUrl,
                        type: "POST",
                        dataType: "json",
                        data: payload
                    });
                }

                debugger;
                var total = $('#guide-list li').length;
                $('#guide-list li').each(function(index) {
                    if($(this).is(":visible")) {
                        var guide_id = $(this).attr('subject_id');
                        var payload = {
                            'action': 'saveChanges',
                            'guide_id': guide_id,
                            'subject_code_id': subject_code_id
                        };
                        $.ajax({
                            url: mySubjectGuide.settings.guideActionUrl,
                            type: "POST",
                            dataType: "json",
                            data: payload,
                            success: function () {
                                if (index === total - 1) {
                                    mySubjectGuide.refreshSubjectCodeGuides();
                                }
                            }
                        });
                    }
                });

                $('#update-guides-btn').hide();
                mySubjectGuide.clearSearchResults();

        },

        showNoGuidesMessage: function () {
            if($('#guide-list li').length == 0) {
                if($('#guide-list-no-items').length == 0) {
                    $('#guide-list-container').prepend("<p id='guide-list-no-items' class='db-alert'>There are no guides assigned to this subject code.</p>");
                }else{
                    $('#guide-list-no-items').show();
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
            mySubjectGuide.clearFlashMsg();
            $('#flash-msg').append(msg).addClass( 'success-msg' );
        },

        showSaveChangesButtons: function () {
            $('#update-guides-btn').show();
        },

        hideSaveChangesButtons: function () {
            $('#update-guides-btn').hide();
        },

        hideAllDescriptionOverrideTextAreas: function () {
            $('.description-override-text-area').hide();
        },

        showSearchResultsContainer: function () {
            $('#search-results-container').show()
        },

        hideSearchResultsContainer: function () {
            $('#search-results-container').hide();
        },

        clearSearchResults: function () {
            $('#add-guide-input').val('');
            $('#guide-search-results').empty();
        },

        showDatabaseListContainer: function () {
            $('#database-list-container').show();
        },

        hideGuideListContainer: function () {
            $('#guide-list-container').hide();
        },

        hideNoGuidesMessage: function () {
            $('#guide-list-no-items').hide();
        },

        clearGuidesList: function () {
            $('#guide-list').empty();
        }

    };

    return mySubjectGuide;
}