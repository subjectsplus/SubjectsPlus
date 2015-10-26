$(document).ready(function () {
    /*
     * This assumes you have some markup that looks like this:
     * 
     * <ul class="db-list-results ui-sortable">     
     <li class="db-list-item-draggable" value="253">Record Title
     <div><span class="show-description-toggle"><i class="fa fa-check" style="display: none;"></i>
      Show Description  </span><span class="show-icons-toggle"> <i class="fa fa-check" style="display: none;">
      </i>Show Icons </span><span class="include-note-toggle"><i class="fa fa-check" style="display: none;">
      </i> Include Note </span></div></li></ul>
     */

    var DatabaseToken = {

        "label": "",
        "record_id": "",
        "token_string": ""


    };

    var s, ResourceList = {
        settings: {

            autoCompleteUrl: "../includes/autocomplete_data.php?collection=records&term=",
            autoCompleteUrlAzList: "../includes/autocomplete_data.php?collection=azrecords&term=",
            dbListButton: $(".dblist-button"),
            dbListButtons: $(".db-list-buttons"),
            dbListContent: $('.db-list-content'),
            dbListResults: $('.db-list-results'),
            dbListResetButton: $('.dblist-reset-button'),
            dbSearchResults: $('.databases-searchresults'),
            dbSearchBox: $('.databases-search'),
            listItem: $(".db-list-item-draggable"),
            resetButton: $(".dblist-reset-button"),
            listLabel: $(".db-list-label"),
            listResults: $(".db-list-results"),
            limitAz: $('#limit-az'),
            showDescriptionToggle: $(".show-description-toggle"),
            showIconsToggle: $(".show-icons-toggle"),
            showNoteToggle: $(".include-note-toggle"),
            click_count: 0,
            searchTermMinimumLength: 3

        },

        strings: {
            noResults: "<li><span class=\"no-box-results\">No Results</span></li>",
            displayToggles: "<div><span class='show-icons-toggle db-list-toggle'><i class='fa fa-minus'></i><i class='fa fa-check'></i>" +
                             " Icons  </span><span class='show-description-toggle db-list-toggle'><i class='fa fa-minus'></i> <i class='fa fa-check'></i>" +
                             " Description </span><span class='include-note-toggle db-list-toggle'><i class='fa fa-minus'></i><i class='fa fa-check'></i>" +
                             " Note </span></div>"
        },

        init: function () {
            s = this.settings;
            strings = this.strings;
            this.bindUiActions();


        },


        bindUiActions: function () {
            this.addToList();
            this.resetList();
            this.toggleIcons();

            this.databaseSearch();
            this.addListToPage();




        },

        addToList: function () {
            $('body').on("click", '.add-to-list-button', function () {

                s.dbListButtons.show();
                s.dbListContent.show();

                var databaseToken = Object.create(DatabaseToken);
                databaseToken.label = $(this).attr('data-label').trim();
                databaseToken.record_id = $(this).val();



                s.dbListResults.append("<li class='db-list-item-draggable' value='" + databaseToken.record_id + "'><span class='db-list-label'>" + databaseToken.label +
                        "</span>" + strings.displayToggles);
                s.dbListResults.sortable();
                s.dbListResults.disableSelection();

                $('.fa-check').hide();

            });
        },

        resetList: function () {
            s.dbListResetButton.on("click", function () {
                s.dbListResults.empty();
                s.dbSearchBox.val("");
            });
        },

        toggleOptions: function (toggleElement) {
            {
                toggleElement.find('.fa-minus').toggle();
                toggleElement.find('.fa-check').toggle();

                toggleElement.toggleClass("active");

                toggleElement.children().find('.fa-minus').toggle();


                include_icons = toggleElement.parent().find('.show-icons-toggle').hasClass('active') | 0;
                include_description = toggleElement.parent().find('.show-description-toggle').hasClass('active') | 0;
                display_note = toggleElement.parent().find('.include-note-toggle').hasClass('active') | 0;

                var display_options = '' + include_icons + '' + include_description + '' + display_note + "";
                toggleElement.parent().parent().data({ 'display_options': display_options });

            }
        },

        toggleIcons: function () {

            $('body').on("click", ".show-description-toggle", function (data) {
                ResourceList.toggleOptions($(this));
            });
            $('body').on("click", ".show-icons-toggle", function (data) {

                ResourceList.toggleOptions($(this));
            });
            $('body').on("click", ".include-note-toggle", function (data) {
                ResourceList.toggleOptions($(this));
            });
        },



        databaseSearch: function () {
            s.dbSearchBox.keyup(function (data) {

                s.dbSearchResults.empty();
                var search_url;
                var search_term = s.dbSearchBox.val();
                var limit_az = s.limitAz.prop("checked");

                if (limit_az) {
                    search_url = s.autoCompleteUrl;
                } else {
                    search_url = s.autoCompleteUrlAzList;
                }


                if ($(this).val() === "") {
                    s.dbSearchResults.html(strings.noResults);

                }


                if (search_term.length > s.searchTermMinimumLength) {

                    $.get(search_url + search_term, function (data) {

                        if (data.length !== 0) {
                            for (var i = 0; i < 10; i++) {
                                try {
                                    if (data[i]['content_type'] == "Record") {

                                        s.dbSearchResults.append("<li data-pluslet-id='" + data[i].id + "' class=\"db-list-item database-listing\">" +
                                                "<div class=\"pure-g\"><div class=\"pure-u-4-5 list-search-label\" title=\"" + data[i].label + "\">" + data[i].label + "</div>" +
                                                        "<div class=\"pure-u-1-5\" style=\"text-align:right;\">" +
                                                        "<button data-label='" + data[i].label + "' value='" + data[i].id + "' class=\"add-to-list-button pure-button pure-button-secondary\"><i class=\"fa fa-plus\"></i></button></div></div></li>");
                                    }

                                } catch (e) {

                                }
                            }
                        } else {
                            s.dbSearchResults.html(strings.noResults);
                        }
                    });

                } else {
                    s.dbSearchResults.html(strings.noResults);

                }

            });
        },

        addListToPage: function () {
            s.dbListButton.on("click", function () {
                dropPluslet('', 'Basic', '');
                var waitCKEDITOR = setInterval(function () {
                    if (window.CKEDITOR) {
                        clearInterval(waitCKEDITOR);

                        var token_string = "<ul class='token-list'>";

                        $(".db-list-item-draggable").each(function (data) {

                            var title = $(this).find('.db-list-label').text();
                            var record_id = $(this).val();

                            // Grab the options
                            var display_options = $(this).data().display_options;


                            // If these are undefined, make them 0
                            display_options = (typeof display_options === 'undefined') ? "000" : display_options;


                            if ($(this).text()) {
                                token_string += "<li class='token-list-item'>{{dab},{" + record_id + "},{" + title + "}" + ",{" + display_options + "}}</li>";
                            }
                        });

                        token_string += "</ul>";



                        var ck_index = Object.keys(CKEDITOR.instances).length - 1;
                        CKEDITOR.instances[Object.keys(CKEDITOR.instances)[ck_index]].setData(token_string.trim());

                        s.click_count++;
                        s.dbListResults.empty();
                    }
                }, 100);
            });
        }
    };

    ResourceList.init();
});




