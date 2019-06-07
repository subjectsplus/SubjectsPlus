
</div> <!--end #body_inner_wrap-->
</div> <!--end pure-u-1-->
</div> <!--end pure-g-->
</div> <!-- end #wrapper-full-->

<div id="push"></div>
</div><!--end #wrap-->

<div id="footer">
    <p class="close">

    <?php 
        if (isset($last_mod) && $last_mod != "") {
            print _("Revised: ") . $last_mod;
        } else {
            print _("This page maintained by: ") . "<a href=\"mailto:$administrator_email\">
    $administrator</a>";
        }

    ?>
    <br />
    Powered by <a href="http://www.subjectsplus.com/">SubjectsPlus</a>
    </p>
</div><!-- end #footer div -->




<script>
  function printView() {
      var visible_tab;

    $('#tab-body').children().each(function () {
        if ($(this).is(":visible")) {
            visible_tab = $(this);        
        } 
        else {
            $(this).show();        
        }

        
      });
      window.print();
      
      
      $('#tab-body').children().each(function () {
    $(this).hide(); 
    
      });
      
      $(visible_tab).show();

  } //end printView()


//show print dialog
function showPrintDialog() {
  
  $(".printer_tabs").colorbox({html: "<h1>Print Selection</h1><div class=\"printDialog\"><ul><li><a onclick=\"window.print();\" class=\"pure-button pure-button-topsearch\">Print Current Tab</a></li><li><a onclick=\"printView();\" class=\"pure-button pure-button-topsearch\">Print All Tabs</a></li></ul></div>", innerWidth:640, innerHeight:480});

}


$(".print-img-tabs").click(function() {    
    showPrintDialog()
});

$('.print-img-no-tabs').click(function(){ window.print(); });

  //fix FOUC
  $('#tab-container').attr('style', 'visibility:visible;');

  //remove favorites from DOM
    $(".uml-quick-links").remove();
  
</script>


</body>
</html>
