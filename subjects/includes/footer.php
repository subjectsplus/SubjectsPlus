
</div>
</div>
</div>
</div>

<div id="footer">

<p class="close" align="center">

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
<br /><br />
</p>
<!-- end footer div -->
</div>

</body>
<script src="<?php print $AssetPath; ?>jquery/bootstrap.js"></script>
</html>


