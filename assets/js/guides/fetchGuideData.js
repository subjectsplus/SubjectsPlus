/*jslint browser: true*/
/*global $, jQuery, alert*/
function guideData() {
    "use strict";

    var myGuideData = {

        settings: {
            fetchGuideData: "helpers/fetch_guide_data.php?",
            fetchTabIdsUrl: "helpers/fetch_tab_ids.php?",
            fetchSectionIdsUrl: "helpers/fetch_section_ids_by_subject_id.php?"
        },
        strings: {},

        init: function() {
          console.log('guideData init');
        },

        fetchGuideData: function() {

            var g = guide();
            var subjectId = g.getSubjectId();
            var payload = {
                'subject_id': subjectId,
            };

            return $.ajax({
                url: myGuideData.settings.fetchGuideData,
                type: "GET",
                data: payload,
                dataType: "json",
                success: function (data) {
                    return data;
                    console.log(JSON.stringify(data));
                }
            });

        },

        handleGuideData: function(data) {

            var tabData = data.responseJSON.tabs;
            $.each(tabData, function (index, value) {
                var tabIndex =  this.tab_index ;
                var tabId = this.tab_id ;

                if( tabIndex == value.tab_index) {
                    var tabItem = $("a[href^='#tabs-" + value.tab_index + "']");
                    $(tabItem).parent('li').attr('id', tabId);
                }
            });
        },


        getTabData: function() {
            var g = guide();
            var subjectId = g.getSubjectId();
            console.log(subjectId);
            var payload = {
                'subject_id': subjectId,
            };

            return $.ajax({
                url: myGuideData.settings.fetchTabIdsUrl,
                type: "GET",
                data: payload,
                dataType: "json",
                success: function (data) {
                    console.log('return tab ids' + JSON.stringify(data));
                    return data;
                }
            }).then(function (data) {
                return data;
            });
        },


        updateTabIds: function (data) {
            var tabData = data.responseJSON.tabs;
            $.each(tabData, function (index, value) {
                var tabIndex =  this.tab_index ;
                var tabId = this.tab_id ;

                if( tabIndex == value.tab_index) {
                    var tabItem = $("a[href^='#tabs-" + value.tab_index + "']");
                    $(tabItem).parent('li').attr('id', tabId);
                }
            });
        },


        getSectionData: function() {
            var g = guide();
            var subjectId = g.getSubjectId();
            var payload = {
                'subject_id': subjectId,
            };

            return $.ajax({
                url: myGuideData.settings.fetchSectionIdsUrl,
                type: "GET",
                data: payload,
                dataType: "json",
                success: function (data) {

                    // var items = $('.sp_section');
                    // $.each(items, function (index, obj) {
                    //     //console.log('index: ' + index + ' obj: ' + $(obj).attr('id'));
                    //     var newId = "section_" + $(newIds).get(index);
                    //     //console.log( $(obj).attr('id', newId) );
                    // });
                    return data;
                }
            }).then(function (data) {
                return data;
            });

        },



        updateSectionIds: function(data) {
            var newIds = [];
            $.each(data.section_ids, function (index, value) {
                newIds.push(value.section_id);
            });

            $(newIds).map(function (index, value) {});

        },

    };

    return myGuideData;
}

/*
.then(function (data) {
                var g = guide();
                favoriteBox().getUserFavoriteBoxes(g.getStaffId());
                return data;
            }).then(function (data) {
                favoriteBox().markAsFavorite();
                //console.log('get favorites');
                return data;
            }).then(function (data) {
                copyClone().markAsLinked();
                //console.log('mark clones as linked');
                return data;
            }).then(function (data) {

                var guideTabData = myGuideData.getTabData();
                guideTabData.then(function (data) {
                    myGuideData.updateTabIds(data);
                });

                console.log('update tab ids');
                return data;
            }).then(function (data) {
                var sectionData = myGuideData.getSectionData();
                sectionData.then(function(data) {
                    myGuideData.updateSectionIds(data);
                    console.log('updateSectionIds');
                });
                return data;
            }).then(function (data) {
               // saveSetup.refreshFeeds();
                //console.log('refreshFeeds');
                return data;
            }).then(function (data) {

                //tabs.fetchTabsFlyout();
                //console.log('fetchTabsFlyout');
                return data;
            }).done(function (data) {
                $( "#autosave-spinner" ).hide();
                //console.log(JSON.stringify(data));
                return data;
            })
 */