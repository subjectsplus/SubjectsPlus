///////////////
// function to correctly size layout of guide
//////////////

function reLayout( lintSectionID, lc, cc, rc )
{
    if (parseInt(lc) === 0) {
        $('div#section_' + lintSectionID + ' div#container-0').width(0);
        $('div#section_' + lintSectionID + ' div#container-0').hide();
    } else {
        $('div#section_' + lintSectionID + ' div#container-0').show();
        $('div#section_' + lintSectionID + ' div#container-0').width(lc.toString() + '%');
    }

    $('div#section_' + lintSectionID + ' div#container-1').width(cc.toString() + '%');

    if (parseInt(rc) === 0) {
        $('div#section_' + lintSectionID + ' div#container-2').width(0);
        $('div#section_' + lintSectionID + ' div#container-2').hide();
    } else {
        $('div#section_' + lintSectionID + ' div#container-2').show();
        $('div#section_' + lintSectionID + ' div#container-2').width(rc.toString() + '%');
    }

}