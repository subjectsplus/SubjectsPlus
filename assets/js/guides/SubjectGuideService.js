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
                                var listCount = $("dd[data-guide-id='"+obj.id+"']").length;

                                if (listCount == 0) {
                                    var addBtn = "<a class='add-guide-btn' title='Add Guide to Subject Code'><i class='fa fa-plus-circle'></i> </a>";
                                    result += '<li data-guide-id="' + obj.id + '">' + addBtn + obj.label + '</li>';
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
                    }
                });
            } else {
                mySubjectGuide.clearSearchResults();
                mySubjectGuide.hideSearchResultsContainer();
                mySubjectGuide.cleanSelectedForEditing("");
            }
        },

        addGuideToSubject: function () {
            $('body').on('click', '.add-guide-btn', function () {
                var clickedRow = $(this).closest('li');
                var subject_id = clickedRow.attr('data-guide-id');
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
                        '<dd data-guide-id="'+subject_id+'" data-subject-code="'+subject_code_id+'">'+label + mySubjectGuide.strings.removeDatabaseBtn +'</dd>');
                    clickedRow.remove();
                    var editing = $('#code-editing-list');
                    editing.show();
                    var this_element = $("#current-selection-editing last-child");

                    mySubjectGuide.saveInsertAssociation(this_element, subject_id, subject_code_id);
                }
            });
        },

        saveInsertAssociation: function (element, data_guide_id, subject_code_id) {
            var payload = {
                'action': 'saveChanges',
                'guide_id': data_guide_id,
                'subject_code_id': subject_code_id
            };
            $.ajax({
                url: mySubjectGuide.settings.guideActionUrl,
                type: "POST",
                dataType: "json",
                data: payload,
                success: function (data) {
                    if (!data) {
                        alert("A connection error has occurred");
                    }
                }
            });
        },

        deleteGuideFromSubjectCode: function () {
            $('body').on('click', '.remove-guide-btn', function () {
                var listItem = $(this).closest('dd');
                var subject_code = listItem.attr('data-subject-code');
                if (typeof listItem.attr('data-guide-id') !== typeof undefined && listItem.attr('data-guide-id') !== false) {
                    var payload = {
                        'action': 'saveChanges',
                        'guide_id': listItem.attr('data-guide-id')
                    };

                    $.ajax({
                        url: mySubjectGuide.settings.guideActionUrl,
                        type: "POST",
                        dataType: "json",
                        data: payload
                    });
                }

                $("dd[data-guide-id='"+listItem.attr('data-guide-id')+"']").remove();
                if ($("dd[data-subject-code='"+subject_code+"']").length == 0){
                    $("dt[data-subject-code='"+subject_code+"']").remove();
                    $("#code-editing-list").hide();
                }
            });
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

        clearGuidesList: function () {
            $('#guide-list').empty();
        }

    };

    return mySubjectGuide;
}