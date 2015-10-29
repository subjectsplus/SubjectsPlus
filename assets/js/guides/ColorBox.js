/**
 * @constructor ColorBox
 * 
 * 
 */ 
 function ColorBox() {
 
 	var myColorBox = {
 	
 	settings : {
 		colorBoxes : [".showmeta",".showrecord",".arrange_records"]
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
 	 	        innerWidth:"80%",
 	 	        innerHeight:"90%",

 	 	        onClosed:function() {
 	 	        }
 	 	    });
 		} 
 	}
 };
 
 	return myColorBox;
 }
