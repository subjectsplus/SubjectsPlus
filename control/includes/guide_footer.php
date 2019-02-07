<!--end maincontent div -->
</div>
<br />
<!--end wrap div -->
</div>


</body>
<script src="<?php echo getControlURL(); ?>includes/js.php" type="text/javascript"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Initialize the guide interface
        var myGuideSetup = guideSetup();
        myGuideSetup.init();

        var ss = subjectSpecialist();
        ss.init();

    });


</script>
</html>
