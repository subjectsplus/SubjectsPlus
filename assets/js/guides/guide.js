$(document).ready(function () {
	
	var fdbx = FindBoxSearch();
    fdbx.init();
    var tabs = Tabs();
    tabs.init();
    var guide = GuideBase();
    guide.init();
    var rs = ResourceList();
    rs.init();
    var fly = Flyout();
    fly.init();
    var pluslet = Pluslet();
    pluslet.init();
    var layout = Layout();
    layout.init();
    var drag = Drag();
    drag.init();
    
    var clone_pluslet_id;
    var subjectId = $("#guide-parent-wrap").data().subjectId;
  
    setupSaveButton('#save_guide');
    makeEditable('a[id*=edit]', subjectId);
    makeDeleteable('a[id*=delete]');
    makeDeleteable('.section_remove', 'sections');
    setupAllColorboxes();
    setupMiscEvents();
    setupMiscClickEvents();
    makeHelpable("img[class*=help-]");
    makeAddSection('a[id="add_section"]');

   

});