/**
 * Object that sets up the behaviour of the flyout menu.
 * 
 * 
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function flyout() {
	"use strict";

	var myFlyout = {
		settings : {
			mainOptions : $('#main-options'),
			triggerMainOptions : $('.trigger-main-options'),
			triggerPointer : $('#trigger-pointer'),
			mainOptionsClose : $('#main-options-close')
		},
		strings : {

		},

		activateFlyoutButton : function(FlyoutButton, FlyoutPanel) {
			$(FlyoutButton).click(function() {
				myFlyout.selectedPanelDisplay();
				$(FlyoutPanel).show();
				$(FlyoutButton).addClass('active-item');
			});
		},
		flyOutPanels : {
			// The key in this object is the selector for the button on the flyout
		    // the value is the actual flyout content. 
			// If you add another button and flyout content to the page, add them to 
			// this object to activate them 
			
			'#show_box_options' : '#box_options_content',
			'#show_findbox_options' : '#findbox_options_content',
			'#show_layout_options' : '#layout_options_content',
			'#show_dblist_options' : '#dblist_options_content',
			'#show_analytics_options' : '#analytics_options_content',
			'#show_my_guides' : '#my_guides_content',
			'#show_image_gallery' : '#image_gallery',
			'#show_tabs' : '#tabs_options'
		},
		bindUiActions : function() {
			// Show/Hide 'Find in Guide' form

			for (var flyoutButton in myFlyout.flyOutPanels) {
				{
					myFlyout.activateFlyoutButton(flyoutButton,
							myFlyout.flyOutPanels[flyoutButton]);
				}

			}
		},
		init : function() {

			//Top level Panel Open by default

			myFlyout.mainSlider();
			myFlyout.bindUiActions()
			myFlyout.setScrollBar();
		},
		mainSlider : function() {
			var s = myFlyout.settings;

			var mainslider = s.mainOptions.slideReveal({
				trigger : s.triggerMainOptions,
				push : false,
				width : 440,
				shown : function(slider, trigger) {
					 $('#trigger-pointer').addClass('fa-chevron-left');
					 $('#trigger-pointer').removeClass('fa-chevron-right');
				},
				hidden : function(slider, trigger) {
					 $('#trigger-pointer').addClass('fa-chevron-right');
					 $('#trigger-pointer').removeClass('fa-chevron-left');
				}
			});

			s.mainOptionsClose.click(function() {
				mainslider.slideReveal('hide');
			});

			//Top Level Panel Flyout 
			window.onload = function() {
				mainslider.slideReveal('show');
			};

		},
		selectedPanelDisplay : function() {
			// Select ONLY Active Panel for coresponding Top Level Item

			$('.second-level-content').not(this).each(function() {
				$(this).hide();
			});
			$('.top-panel-option-item').not(this).each(function() {
				$(this).removeClass('active-item');
			});

		},
		setScrollBar : function() {

			$('.box_options_container, .fav-boxes-content, .db-list-results, .user_guides_display, .flyout-tabs').enscroll({
			    verticalTrackClass: 'track',
			    verticalHandleClass: 'handle',
			    minScrollbarLength: 28
			});


			$('.find-box-tab-list-content .pluslet-list, .find-box-tab-list-content .findbox-searchresults, .databases-searchresults').enscroll({
			    verticalTrackClass: 'track2',
			    verticalHandleClass: 'handle2',
			    minScrollbarLength: 28
			});

		}
		

	};

	return myFlyout;
};