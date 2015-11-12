/**
 * colorbox
 * This sets up a list of elements that will be viewed with a colorbox.
 * 
 */ 
/*jslint browser: true*/
/*global $, jQuery, alert*/
 function colorBox() {
	"use strict";

 	var myColorBox = {
 	
 	settings : {
 		colorBoxes : ['.showmeta','.showrecord','.arrange_records']
 	},
 	strings : {
 	},
 	bindUiActions : function() {
 	},
 	init : function() {
 	myColorBox.setupAllColorboxes();
 	}, 
 	setupAllColorboxes : function()
 	{
 		for (var key in myColorBox.settings.colorBoxes) {
 		    var colorBox = myColorBox.settings.colorBoxes[key];
 		        $(colorBox).colorbox({
 		            iframe: true,
 		            innerWidth: 960,
 		            innerHeight: 600,
 		            fastIframe: true,
 		        });
 		
 		} 
 	}
 };
 
 	return myColorBox;
 }
