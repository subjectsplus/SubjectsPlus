/**
* 
* This initializes the guide interface by calling the init method on the modules in the setupFunctions list
* 
* @constructor  GuideSetup
* @author little9 (Jamie Little)
* 
*/
function GuideSetup() {
	var myGuideSetup = {
			settings : {
				
			},
			setupFunctions : [FindBoxSearch, Tabs, GuideBase, ResourceList, 
			                  Flyout, Pluslet, Layout, Drag, Help, 
			                  SaveSetup, CopyClone,ColorBox],
			init : function() {
				
				for (func in myGuideSetup.setupFunctions) {
					
					var setupFunc = myGuideSetup.setupFunctions[func]();
					setupFunc.init();
				}
								


			}	
	}
	return myGuideSetup;
}	


