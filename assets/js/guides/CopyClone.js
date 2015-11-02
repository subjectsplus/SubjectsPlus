/**
 * Sets up the clone and copy buttons used in the flyout.
 *  
 * @constructor Tabs
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function CopyClone() {
	"use strict";

	var myCopyClone = {

		settings : {
			cloneButton : '.clone-button',
			copyButton : '.copy-button',
			cloneFavoriteButton : '.clone-favorite',
			plusletIdSelector : 'data-pluslet-id'
		},
		strings : {},
		bindUiActions : function() {

			var ps = Pluslet();

			$('body').on(
					'click',
					myCopyClone.settings.cloneButton,
					function() {

						var origin_id = $(this).parent().parent().parent()
								.attr('data-pluslet-id');
						var origin_title = $(this).parent().parent().find(
								'.box-search-label').text();

						ps.dropPluslet('', 'Clone', origin_id, origin_title);

					});

			$('body').on(
					'click',
					myCopyClone.settings.copyButton,
					function() {

						var origin_id = $(this).parent().parent().parent()
								.attr(myCopyClone.settings.plusletIdSelector);
						var origin_title = $(this).parent().parent().parent()
								.text().replace(" /Clone Copy/g", "");

						// Get the type and pass it to the dropPluset function
						
						var type = $("#pluslet-" + origin_id).attr('name');

						ps.dropPluslet(origin_id, type, origin_title);

					});

			$('body').on(
					'dblclick',
					myCopyClone.settings.cloneFavoriteButton,
					function() {

						var origin_id = $(this).attr(
								myCopyClone.settings.plusletIdSelector);
						var origin_title = $(this).html();

						ps.dropPluslet('', 'Clone', origin_id, origin_title);

					});

		},
		init : function() {
		    myCopyClone.bindUiActions();
		    myCopyClone.markAsLinked();
		},
		markAsLinked: function () {
		    /**
             * Created by cbrownroberts on 8/28/15.
             */
		    //identify pluslets marked as linked aka cloned and addClass linked_pluslet
		    var linkedBoxes = $('div.pluslet[name=\'Clone\']');
		    linkedBoxes.each(function () {
		        $(this).children('.titlebar').children('.titlebar_text').addClass('linked_pluslet');
		    });

		}
	};

	return myCopyClone;
}
/**
 * Sets up the clone and copy buttons used in the flyout.
 *  
 * @constructor Tabs
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function CopyClone() {
    "use strict";

    var myCopyClone = {

        settings: {
            cloneButton: '.clone-button',
            copyButton: '.copy-button',
            cloneFavoriteButton: '.clone-favorite',
            plusletIdSelector: 'data-pluslet-id'
        },
        strings: {},
        bindUiActions: function() {

            var ps = Pluslet();

            $('body').on(
                'click',
                myCopyClone.settings.cloneButton,
                function() {

                    var origin_id = $(this).parent().parent().parent()
                        .attr('data-pluslet-id');
                    var origin_title = $(this).parent().parent().find(
                        '.box-search-label').text();

                    ps.dropPluslet('', 'Clone', origin_id, origin_title);

                });

            $('body').on(
                'click',
                myCopyClone.settings.copyButton,
                function() {

                    var origin_id = $(this).parent().parent().parent()
                        .attr(myCopyClone.settings.plusletIdSelector);
                    var origin_title = $(this).parent().parent().parent()
                        .text().replace(" /Clone Copy/g", "");

                    // Get the type and pass it to the dropPluset function

                    var type = $("#pluslet-" + origin_id).attr('name');

                    ps.dropPluslet(origin_id, type, origin_title);

                });

            $('body').on(
                'dblclick',
                myCopyClone.settings.cloneFavoriteButton,
                function() {

                    var origin_id = $(this).attr(
                        myCopyClone.settings.plusletIdSelector);
                    var origin_title = $(this).html();

                    ps.dropPluslet('', 'Clone', origin_id, origin_title);

                });

        },
        init: function() {
            myCopyClone.bindUiActions();
            myCopyClone.markAsLinked();
        },
        markAsLinked: function() {
            /**
             * Created by cbrownroberts on 8/28/15.
             */
            //identify pluslets marked as linked aka cloned and addClass linked_pluslet
            var linkedBoxes = $('div.pluslet[name=\'Clone\']');
            linkedBoxes.each(function() {
                $(this).children('.titlebar').children('.titlebar_text').addClass('linked_pluslet');
            });

        }
    };

    return myCopyClone;
}/**
 * Sets up the clone and copy buttons used in the flyout.
 *  
 * @constructor Tabs
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function CopyClone() {
    "use strict";

    var myCopyClone = {

        settings: {
            cloneButton: '.clone-button',
            copyButton: '.copy-button',
            cloneFavoriteButton: '.clone-favorite',
            plusletIdSelector: 'data-pluslet-id'
        },
        strings: {},
        bindUiActions: function() {

            var ps = Pluslet();

            $('body').on(
                'click',
                myCopyClone.settings.cloneButton,
                function() {

                    var origin_id = $(this).parent().parent().parent()
                        .attr('data-pluslet-id');
                    var origin_title = $(this).parent().parent().find(
                        '.box-search-label').text();

                    ps.dropPluslet('', 'Clone', origin_id, origin_title);

                });

            $('body').on(
                'click',
                myCopyClone.settings.copyButton,
                function() {

                    var origin_id = $(this).parent().parent().parent()
                        .attr(myCopyClone.settings.plusletIdSelector);
                    var origin_title = $(this).parent().parent().parent()
                        .text().replace(" /Clone Copy/g", "");

                    // Get the type and pass it to the dropPluset function

                    var type = $("#pluslet-" + origin_id).attr('name');

                    ps.dropPluslet(origin_id, type, origin_title);

                });

            $('body').on(
                'dblclick',
                myCopyClone.settings.cloneFavoriteButton,
                function() {

                    var origin_id = $(this).attr(
                        myCopyClone.settings.plusletIdSelector);
                    var origin_title = $(this).html();

                    ps.dropPluslet('', 'Clone', origin_id, origin_title);

                });

        },
        init: function() {
            myCopyClone.bindUiActions();
            myCopyClone.markAsLinked();
        },
        markAsLinked: function() {
            /**
             * Created by cbrownroberts on 8/28/15.
             */
            //identify pluslets marked as linked aka cloned and addClass linked_pluslet
            var linkedBoxes = $('div.pluslet[name=\'Clone\']');
            linkedBoxes.each(function() {
                $(this).children('.titlebar').children('.titlebar_text').addClass('linked_pluslet');
            });

        }
    };

    return myCopyClone;
}
/**
 * Sets up the clone and copy buttons used in the flyout.
 *  
 * @constructor Tabs
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function CopyClone() {
    "use strict";

    var myCopyClone = {

        settings: {
            cloneButton: '.clone-button',
            copyButton: '.copy-button',
            cloneFavoriteButton: '.clone-favorite',
            plusletIdSelector: 'data-pluslet-id'
        },
        strings: {},
        bindUiActions: function() {

            var ps = Pluslet();

            $('body').on(
                'click',
                myCopyClone.settings.cloneButton,
                function() {

                    var origin_id = $(this).parent().parent().parent()
                        .attr('data-pluslet-id');
                    var origin_title = $(this).parent().parent().find(
                        '.box-search-label').text();

                    ps.dropPluslet('', 'Clone', origin_id, origin_title);

                });

            $('body').on(
                'click',
                myCopyClone.settings.copyButton,
                function() {

                    var origin_id = $(this).parent().parent().parent()
                        .attr(myCopyClone.settings.plusletIdSelector);
                    var origin_title = $(this).parent().parent().parent()
                        .text().replace(" /Clone Copy/g", "");

                    // Get the type and pass it to the dropPluset function

                    var type = $("#pluslet-" + origin_id).attr('name');

                    ps.dropPluslet(origin_id, type, origin_title);

                });

            $('body').on(
                'dblclick',
                myCopyClone.settings.cloneFavoriteButton,
                function() {

                    var origin_id = $(this).attr(
                        myCopyClone.settings.plusletIdSelector);
                    var origin_title = $(this).html();

                    ps.dropPluslet('', 'Clone', origin_id, origin_title);

                });

        },
        init: function() {
            myCopyClone.bindUiActions();
            myCopyClone.markAsLinked();
        },
        markAsLinked: function() {
            /**
             * Created by cbrownroberts on 8/28/15.
             */
            //identify pluslets marked as linked aka cloned and addClass linked_pluslet
            var linkedBoxes = $('div.pluslet[name=\'Clone\']');
            linkedBoxes.each(function() {
                $(this).children('.titlebar').children('.titlebar_text').addClass('linked_pluslet');
            });

        }
    };

    return myCopyClone;
}1.5.10
