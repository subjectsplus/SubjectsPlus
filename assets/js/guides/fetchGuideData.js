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
                    return data;
                }
            });

        },


        updateSectionIds: function() {
            var sectionData = myGuideData.getSectionData();

            sectionData.then(function (data) {
                var newIds = [];
                $.each(data.sections, function (index, value) {
                    //console.log(value.section_id);
                    newIds.push(value.section_id);
                });
                return newIds;
            }).then(function(newIds) {
                var sectionHtml = $('#tabs').find('.sp_section');
                $.each(sectionHtml, function(index, value) {
                    var newId = newIds[index];
                    $(this).attr('id', 'section_' + newId);
                });
            });

        },

    };

    return myGuideData;
}
