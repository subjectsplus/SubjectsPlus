/*jslint browser: true*/
/*global $, jQuery, alert*/
function guideData() {
    "use strict";

    var myGuideData = {

        settings: {
            fetchGuideData: "helpers/fetch_guide_data.php?",
            fetchTabDataBySubjectId: "helpers/fetch_tab_data_by_subject_id.php?",
            fetchSectionDataBySubjectId: "helpers/fetch_section_data_by_subject_id.php?"
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
                    //console.log(JSON.stringify(data));
                }
            });
        },


        getTabData: function() {
            var g = guide();
            var subjectId = g.getSubjectId();
            var payload = {
                'subject_id': subjectId,
            };
            return $.ajax({
                url: myGuideData.settings.fetchTabDataBySubjectId,
                type: "GET",
                data: payload,
                dataType: "json",
                success: function (data) {
                    return data;
                }
            });
        },


        updateTabIds: function (tabData) {
            $.each(tabData.tab_data, function (index, value) {
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
                url: myGuideData.settings.fetchSectionDataBySubjectId,
                type: "GET",
                data: payload,
                dataType: "json",
                success: function (data) {
                    return data;
                }
            });
        },


        updateSectionIds: function(sectionData) {
            sectionData.then(function (data) {
                var newIds = [];
                $.each(data.sections_by_subject, function (index, value) {
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

        bindNewIds: function() {
            var tabData = myGuideData.getTabData();
            tabData.then(function (data) {
                myGuideData.updateTabIds(data);
            }).then(function () {
                var sectionData = myGuideData.getSectionData();
                myGuideData.updateSectionIds(sectionData);
            }).always(function () {
                $("#autosave-spinner").hide();
            });
        }

    };

    return myGuideData;
}
