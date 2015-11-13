/**
 * Sets up the clone and copy buttons used in the flyout.
 *  
 * 
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function copyClone() {
	"use strict";

	var myCopyClone = {

		settings : {
			cloneButton : '.clone-button',
			copyButton : '.copy-button',
			cloneFavoriteButton : '.clone-favorite',
			copyFavoriteButton : '.copy-favorite',
		
		},
		strings : {
			noLinking : "I'm sorry, but you cannot link a linked box"
		},
		bindUiActions : function() {

			var ps = pluslet();

			$('body').on(
					'click',
					myCopyClone.settings.cloneButton,
					function() {

						var origin_id = $(this).parent().parent().parent()
								.attr('data-pluslet-id');
						var origin_title = $(this).parent().parent().find(
								'.box-search-label').text();
						var origin_type = $(this).parent().parent().parent().data().plusletType;

						if (origin_type === 'Clone') {
						alert(myCopyClone.strings.noLinking);	
						
						} else {
						ps.dropPluslet('', 'Clone', origin_id, origin_title);
						}
					});

			$('body').on(
					'click',
					myCopyClone.settings.copyButton,
					function() {

					    var origin_id = $(this).parent().parent().parent().data().plusletId;
						var origin_title = $(this).parent().parent().parent()
								.text().replace(" /Clone Copy/g", "");
						var origin_type = $(this).parent().parent().parent().data().plusletType;

						ps.dropPluslet(origin_id, origin_type, origin_title);

					});

			$('body').on(
					'click',
					myCopyClone.settings.cloneFavoriteButton,
					function() {

						var origin_id = $(this).parent().siblings('.fav-box-item').find('a').attr('data-pluslet-id');
						var origin_title = $(this).parent().siblings('.fav-box-item').find('a').text();

						ps.dropPluslet('', 'Clone', origin_id, origin_title);

					});

			$('body').on(
					'click',
					myCopyClone.settings.copyFavoriteButton,
					function() {

						var origin_id = $(this).parent().siblings('.fav-box-item').find('a').attr('data-pluslet-id');
						var origin_title = $(this).parent().siblings('.fav-box-item').find('a').text();
						var origin_type = $(this).parent().siblings('.fav-box-item').find('a').attr('data-pluslet-type');

						ps.dropPluslet(origin_id, origin_type, origin_title);

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