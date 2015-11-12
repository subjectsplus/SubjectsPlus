
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
        
    } else {
        $(this).show();
        
    }


      });
      window.print();
      
      
      $('#tab-body').children().each(function () {
    $(this).hide(); 
    
      });
      
      $(visible_tab).show();    
  }
</script>

<script>
<?php include('./includes/js/hash.js'); ?>
<?php include('./includes/js/track.js'); ?>
<?php include('./includes/js/tabDropdown.js'); ?>
<?php include('./includes/js/autoComplete.js'); ?>
<?php include('./includes/js/jquery.scrollTo.js'); ?>
hash.init();
track.init();
tabDropdown.init();
autoComplete.init();
</script>
</body>
</html>
