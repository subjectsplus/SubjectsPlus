/**
* Object to get details related to the current guide.
* 
*  
**/
/*jslint browser: true*/
/*global $, jQuery, alert*/
function guide() {
	"use strict";

 	var myGuide = {
 	
 	settings : {
 		guideData : $('#guide-parent-wrap').data()
 	},
 	strings : {
 	},
 	bindUiActions : function() {
 	},
 	init : function() {
 	},
 	getSubjectId : function () {
		var subjectId = myGuide.settings.guideData.subjectId;
		return subjectId;
 	},
	getStaffId : function () {
		var staffId = myGuide.settings.guideData.staffId;
		return staffId;
 	}
 
 };
 
 	return myGuide;
 }