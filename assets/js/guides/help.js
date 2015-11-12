/**
 * Activates the help buttons on pluslets
 *  
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function help() {
	"use strict";

 	var myHelp = {
 	
 	settings : {
 		popUrl : 'helpers/popup_help.php?type='
 	},
 	strings : {
 	},
 	bindUiActions : function() {
 	},
 	init : function() {
 		myHelp.makeHelpable('img[class*=help-]');
 	},


 	makeHelpable : function (lstrSelector) {
         ////////////////
 		 // Help Buttons
 		 // unbind click events from class and redeclare click event
 		 ////////////////
 	$(lstrSelector).unbind('click');
 	$(lstrSelector).on('click', function() {
 		var help_type = $(this).attr('class').split('-');
 		myHelp.settings.popUrl = myHelp.settings.popUrl + help_type[1];

 		$(this).colorbox({
 			href : myHelp.settings.popUrl,
 			iframe : true,
 			innerWidth : '600px',
 			innerHeight : '60%',
 			maxWidth : '1100px',
 			maxHeight : '800px'
 		});
 	});
 }

 };
 
 	return myHelp;
 }