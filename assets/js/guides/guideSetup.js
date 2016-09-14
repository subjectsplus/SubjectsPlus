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
		setupFunctions : [findBoxSearch, style,
						  flyout, tabs, pluslet, section, layout, drag, help,
						  saveSetup, copyClone, colorBox, favoriteBox, guideSearch, subjectSpecialist],
		init : function() {
			for (var func in myGuideSetup.setupFunctions) {
				var setupFunc = myGuideSetup.setupFunctions[func]();
				setupFunc.init();
			}

			myGuideSetup.getBaseUrl();
		},
		getBaseUrl: function () {
			var url = location.href;  // entire url including querystring - also: window.location.href;
			var baseURL = url.substring(0, url.indexOf('/', 14));


			if (baseURL.indexOf('http://localhost') != -1) {
				// Base Url for localhost
				var url = location.href;  // window.location.href;
				var pathname = location.pathname;  // window.location.pathname;
				var index1 = url.indexOf(pathname);
				var index2 = url.indexOf("/", index1 + 1);
				var baseLocalUrl = url.substr(0, index2);

				return baseLocalUrl + "/";
			}
			else {
				// Root Url for domain name
				return baseURL + "/";
			}
		}
	}
	return myGuideSetup;
}	


