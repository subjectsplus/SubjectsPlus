$(document).ready(function () { 
//  This script enables links on the guide TOC plustlet 
//  to switch tabs when they are clicked 
    
    $(".table-of-contents").on("click", function() {
        
        var pluslet_id = $(this).attr('id').split('boxid-')[1];
        var tab_id = $('#pluslet-' + pluslet_id ).parent().parent().parent().attr('id').split('tabs-')[1];
        
        console.log(tab_id);
        $('#tabs').tabs('select', tab_id);
        
        
    });
    
});
