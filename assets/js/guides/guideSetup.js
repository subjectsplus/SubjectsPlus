/**
* 
* This initializes the guide interface by calling the init method on the modules in the setupFunctions list
*
* @author little9 (Jamie Little)
* 
*/
/*jslint browser: true*/
/*global $, jQuery, alert*/
function guideSetup() {
	"use strict";

	var myGuideSetup = {
			settings : {
				
			},
			setupFunctions : [findBoxSearch, style, resourceList, 
			                  flyout, tabs, pluslet, section, layout, drag, help, 
			                  saveSetup, copyClone, colorBox, favoriteBox, guideSearch],
			init : function() {
				
				for (var func in myGuideSetup.setupFunctions) {
					
					var setupFunc = myGuideSetup.setupFunctions[func]();
					setupFunc.init();
				}
								


			}	
	}
	return myGuideSetup;
}	


