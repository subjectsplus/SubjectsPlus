/**
 * 
 */
function getFlyout() {

	var Flyout = {
		settings : {
			mainOptions : $('#main-options'),
			triggerMainOptions : $(".trigger-main-options"),
			triggerPointer : $("#trigger-pointer"),
			mainOptionsClose : $("#main-options-close")
		},
		strings : {

		},

		activateFlyoutButton : function(FlyoutButton, FlyoutPanel) {
			$(FlyoutButton).click(function() {
				Flyout.selectedPanelDisplay();
				$(FlyoutPanel).show();
				$(FlyoutButton).addClass("active-item");
			});
		},
		flyOutPanels : {
			// The key in this object is the selector for the button on the flyout
		    // the value is the actual flyout content. 
			// If you add another button and flyout content to the page, add them to 
			// this object to activate them 
			
			"#show_box_options" : "#box_options_content",
			"#show_findbox_options" : "#findbox_options_content",
			"#show_layout_options" : "#layout_options_content",
			"#show_dblist_options" : "#dblist_options_content",
			"#show_analytics_options" : "#analytics_options_content",
			"#show_my_guides" : "#my_guides_content",
			"#show_image_gallery" : "#image_gallery"
		},
		bindUiActions : function() {
			// Show/Hide "Find in Guide" form

			for (flyoutButton in Flyout.flyOutPanels) {
				{
					Flyout.activateFlyoutButton(flyoutButton,
							Flyout.flyOutPanels[flyoutButton]);
				}

			}
		},
		init : function() {

			//Top level Panel Open by default

			this.mainSlider();
			this.bindUiActions();
		},
		mainSlider : function() {
			var s = Flyout.settings;

			var mainslider = s.mainOptions.slideReveal({
				trigger : s.triggerMainOptions,
				push : false,
				width : 440,
				shown : function(slider, trigger) {
					s.triggerPointer.addClass("fa-chevron-left");
					s.mainOptionsClose.removeClass("fa-chevron-right");
				},
				hidden : function(slider, trigger) {
					s.triggerPointer.addClass("fa-chevron-right");
					s.triggerPointer.removeClass("fa-chevron-left");
				}
			});

			s.mainOptionsClose.click(function() {
				mainslider.slideReveal("hide");
			});

			//Top Level Panel Flyout 
			window.onload = function() {
				mainslider.slideReveal("show");
			};

		},
		selectedPanelDisplay : function() {
			// Select ONLY Active Panel for coresponding Top Level Item

			$('.second-level-content').not(this).each(function() {
				$(this).hide();
			});
			$('.top-panel-option-item').not(this).each(function() {
				$(this).removeClass("active-item");
			});

		}

	};

	return Flyout;
};