function getGuideBase() {
    var GuideBase = {
        settings: {
            globalHeader: $("#header, #subnavcontainer"),
            sections: $('div[id^="section_"]'),
            newBox: $('#newbox'),
            sliderOptions: $("#slider_options"),
            layoutBox: $("#layoutbox"),
            slider: jQuery("#slider"),
            extra: jQuery("#extra"),
            mainColumnWidth: jQuery("#main_col_width"),
            saveLayout: jQuery("#save_layout"),
            tabs: $('#tabs'),
            tabTitle: $("#tab_title"),
            tabContent: $("#tab_content"),
            tabExteralDataLink: $('li[data-external-link]'),
            autoCompletebox: $('.find-guide-input')

        },
        strings: {
            tabTemplate: "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a><span class='alter_tab' role='presentation'><i class=\"fa fa-cog\"></i></span></li>",

        },
        bindUiActions: function () {

        },
        init: function () {
            // Hides the global nav on load
            GuideBase.settings.globalHeader.hide();
            GuideBase.layoutSections();
            GuideBase.settings.newBox.hoverIntent(GuideBase.hoverIntentConfig);
            GuideBase.hoverIntentLayoutBox();


        },
        layoutSections: function () {
            GuideBase.settings.sections.each(function () {
                //section id
                var sec_id = $(this).attr('id').split('section_')[1];
                var lobjLayout = $('div#section_' + sec_id).attr('data-layout').split('-');

                var lw = parseInt(lobjLayout[0]) * 7;
                var mw = parseInt(lobjLayout[1]) * 7;
                var sw = parseInt(lobjLayout[2]) * 7;

                console.log(lw, mw, sw);

                try {
                    reLayout(sec_id, lw, mw, sw);
                } catch (e) {



                }


            });
        },
        hoverIntentConfig: {
            interval: 50,
            sensitivity: 4,
            timeout: 500
        },
        hoverIntentSliderConfig: {
            interval: 50,
            sensitivity: 4,
            over: this.addSlider,
            timeout: 500,
            out: this.removeSlider
        },
        hoverIntentLayoutBox: function () {
            GuideBase.settings.layoutBox.hoverIntent(this.hoverIntentSliderConfig);
        },
        addSlider: function () {
            GuideBase.settings.sliderOptions.show();
        },
        removeSlider: function () {
            GuideBase.settings.sliderOptions.hide();


        },
        moveToHash: function () {

            if (window.location.hash) {
                setTimeout(function () {
                    if (window.location.hash.split('-').length == 3) {
                        var tab_id = window.location.hash.split('-')[1];
                        var box_id = window.location.hash.split('-')[2];
                        var selected_box = ".pluslet-" + box_id;

                        $('#tabs').tabs('select', tab_id);

                        $('html, body').animate({ scrollTop: jQuery('a[name="box-' + box_id + '"]').offset().top }, 'slow');

                        $(selected_box).effect("pulsate", {
                            times: 1
                        }, 2000);
                    }
                }, 500);
            }
        },
        setupModalWindows: function () {

        },
        setupAutoCompleteBox: function () {

        }
    };

    return GuideBase;
}