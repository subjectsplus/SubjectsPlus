function LinkList(id,idSelector) {

    var myId = id;

    console.log(myId);

    var recordSearch = new RecordSearch;
    var myRecordList = new RecordList;

    // Autocomplete search
    $(' .databases-search').keyup(function (data) {
        if ($('.databases-search').val().length > 2) {
            databaseSearch();
        }


    });
    // Rerun the search when the user changes the limit az box
    $('#limit-az').on('change', function () {
        databaseSearch();
    });

    // Add to sortable list when user click the add button
    $(idSelector).on('click', '.add-to-list-button', function () {
        // Create a Record object for the listing you clicked on
        var myRecord = new Record('{{dab},{' + $(this).val() + '},{' + $(this).data().label + '},{000}}');
        // Add that record to the main RecordList
        myRecordList.addToList(myRecord);
        // Get a sortable list and append it to the draggable link list area
        var sortableList = new RecordListSortable(myRecordList);
        $('.link-list-draggable').html(sortableList.getList());
    });

    // Reset the the html and RecordList instance
    $(' .dblist-reset-button').on('click', function () {
        $(this).parents().find('.link-list-draggable').html('');
        myRecordList = new RecordList;
    });

    // Create List button
    $(' .dblist-button').on('click', function () {
        var list = $(this).parents().find('.link-list');
        loadSortableList();
        if (myRecordList.getList().length > 0) {
            var displayList = new RecordListDisplay(myRecordList);
            var descriptionLocation = $('input[name=LinkList-extra-radio]:checked').val();

            list.html(displayList.getList());


            var description = CKEDITOR.instances['link-list-textarea'].getData();
            console.log(descriptionLocation);
            if (descriptionLocation == "top") {
                list.prepend("<div class='link-list-text-top'>" + description + "</div>");
            } else {
                list.append("<div class='link-list-text-bottom'>" + description + "</div>");
            }


        } else {
            alert('Please add some records to your list.')
        }
    });



    function databaseSearch() {
        var limitAz;
        if ($('#limit-az').prop("checked")) {
            limitAz = true;
        } else {
            limitAz = false
        }
        recordSearch.search($('.databases-search').val(), limitAz, 'databases-searchresults', addSearchResultsToPage);
    }

    // Load existing list behaviour
    if ($('.link-list').data().linkListId) {
        loadDisplayList($('#LinkList-body').siblings().first().find('li'));
    }

    function loadDisplayList(list) {
        // This loads a display list and appends a sortable list
        var existingList = new RecordList();
        list.each(function (data) {
            console.log(data);
            var token = $(this).text();
            console.log(token);
            var exisitingRecord = new Record(token);
            existingList.addToList(exisitingRecord);
            var existingSortableList = new RecordListSortable(existingList);
            $('.link-list-draggable').html(existingSortableList.getList());
            $('.db-list-results').sortable();
        });

        myRecordList = existingList;
    }

    function loadSortableList() {
        myRecordList = new RecordList;
        $('.db-list-item-draggable').each(function (li) {
            var record = new Record($(this).data().token)
            myRecordList.addToList(record);

        });
    }

    function addSearchResultsToPage(data) {
        var searchResults = new RecordList;
        $.each(data, function (index) {
            var resultRecord = recordSearch.searchResultRecord(data[index]);
            searchResults.addToList(resultRecord);
        });

        var searchResultsDisplay = new RecordListSearch(searchResults);
        var element = document.getElementById('databases-searchresults');
        element.innerHTML = searchResultsDisplay.getList(myId);
    }


// CKEditor
    function activateCKEditors() {
        CKEDITOR.replace('description', {
            toolbar: 'TextFormat'
        });

        CKEDITOR.replace('link-list-textarea', {
            toolbar: 'TextFormat'
        });
    }


    $('document').ready(function () {
        activateCKEditors();
    })

}