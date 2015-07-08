
function makeDraggable( lstrSelector )
{
    ////////////////////////////////
    // SET UP DRAGGABLE
    // --makes anyting with class of "draggable" draggable
    ////////////////////////////////

    var draggable_element = $(lstrSelector);

    draggable_element.draggable({
	ghosting:	true,
	opacity:	0.5,
	revert: true,
	fx: 300,
	cursor: 'pointer',
	helper: 'clone',
	zIndex: 350
    });
    
}
