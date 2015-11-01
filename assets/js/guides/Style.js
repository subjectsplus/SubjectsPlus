/**
 * Style changes that are triggered with js. 
 * 
 * @constructor Style
 * 
 * 
 */

function Style() {
	"use strict";

    var myStyle = {
        settings: {
            globalHeader: $("#header, #subnavcontainer"),         
        },
        strings: {
        },
        bindUiActions: function () {
       
        },
        init: function () {
            /** Since we are in the guide creation interface we'll need to hide the bar at the top */
            myStyle.settings.globalHeader.hide();
            myStyle.fixFlashFOUC();

            myStyle.bindUiActions();
        },
        fixFlashFOUC: function () {
            $(".guidewrapper").css("display", "block");
            $("#main-options").css("display", "block");
        },
   
       
        

    };
    return myStyle;
}