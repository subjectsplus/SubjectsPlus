/**
* Asset manager flyout functionality
* 
* 
* assetManager
* 
*  
**/
/*jslint browser: true*/
/*global $*/


 
 function assetManager() {
 
 	var myAssetManager = {
 	
 	settings : {
 	},
 	strings : {
 	},
 	bindUiActions : function() {
 	},
 	init : function() {
 		myAssetManager.activateDropZone();
 	},
 	activateDropZone : function() {
 		/*
 		Dropzone.options.imagezone = {
 				  init: function() {
 				    this.on("success", function(file) { $('.dz-success-mark').show(); });
 				    this.on("error", function(file) { $('.dz-error-mark').show(); });
 				    
 				  }
 				};
 				*/
 	}
 };
 
 	return myAssetManager;
 }


