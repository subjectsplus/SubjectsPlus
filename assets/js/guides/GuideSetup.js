/**
* 
* This initializes the guide interface by calling the init method on the modules in the setupFunctions list
* 
* @constructor  GuideSetup
* @author little9 (Jamie Little)
* 
*/
/*jslint browser: true*/
/*global $, jQuery, alert*/
function GuideSetup() {
	"use strict";

	var myGuideSetup = {
			settings : {
				
			},
			setupFunctions : [FindBoxSearch, Tabs, Style, ResourceList, 
			                  Flyout, Pluslet,Section, Layout, Drag, Help, 
			                  SaveSetup, CopyClone, ColorBox, FavoriteBox, GuideSearch],
			init : function() {
				
				for (var func in myGuideSetup.setupFunctions) {
					
					var setupFunc = myGuideSetup.setupFunctions[func]();
					setupFunc.init();
				}
								


			}	
	}
	return myGuideSetup;
}	


/**
 * 
 * This initializes the guide interface by calling the init method on the modules in the setupFunctions list
 * 
 * @constructor  GuideSetup
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function GuideSetup() {
    "use strict";

    var myGuideSetup = {
        settings: {

        },
        setupFunctions: [FindBoxSearch, Tabs, Style, ResourceList,
            Flyout, Pluslet, Section, Layout, Drag, Help,
            SaveSetup, CopyClone, ColorBox, FavoriteBox, GuideSearch
        ],
        init: function() {

            for (var func in myGuideSetup.setupFunctions) {

                var setupFunc = myGuideSetup.setupFunctions[func]();
                setupFunc.init();
            }



        }
    }
    return myGuideSetup;
}/**
 * 
 * This initializes the guide interface by calling the init method on the modules in the setupFunctions list
 * 
 * @constructor  GuideSetup
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function GuideSetup() {
    "use strict";

    var myGuideSetup = {
        settings: {

        },
        setupFunctions: [FindBoxSearch, Tabs, Style, ResourceList,
            Flyout, Pluslet, Section, Layout, Drag, Help,
            SaveSetup, CopyClone, ColorBox, FavoriteBox, GuideSearch
        ],
        init: function() {

            for (var func in myGuideSetup.setupFunctions) {

                var setupFunc = myGuideSetup.setupFunctions[func]();
                setupFunc.init();
            }



        }
    }
    return myGuideSetup;
}


/**
 * 
 * This initializes the guide interface by calling the init method on the modules in the setupFunctions list
 * 
 * @constructor  GuideSetup
 * @author little9 (Jamie Little)
 * 
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
function GuideSetup() {
    "use strict";

    var myGuideSetup = {
        settings: {

        },
        setupFunctions: [FindBoxSearch, Tabs, Style, ResourceList,
            Flyout, Pluslet, Section, Layout, Drag, Help,
            SaveSetup, CopyClone, ColorBox, FavoriteBox, GuideSearch
        ],
        init: function() {

            for (var func in myGuideSetup.setupFunctions) {

                var setupFunc = myGuideSetup.setupFunctions[func]();
                setupFunc.init();
            }



        }
    }
    return myGuideSetup;
}1.5.10
