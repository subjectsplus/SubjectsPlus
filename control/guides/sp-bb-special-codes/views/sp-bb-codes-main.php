<link rel="stylesheet" href="sp-bb-special-codes/css/styles.css" type="text/css" media="all">
<script src="sp-bb-special-codes/js/sp-bb-special-codes.js"></script>

<div class="pure-g">

    <div class="pure-u-1-2">
        <?php echo makePluslet(_("Add New Special Code"), $addNewSpecialCodeTemplate , "no_overflow"); ?>
        <?php echo makePluslet(_("Special Case Codes List"), $currentCodesList, "no_overflow"); ?>
    </div>

    <div class="pure-u-1-3">
        <?php echo makePluslet(_("Edit Special Code"), $editSpecialCodeTemplate , "no_overflow"); ?>
    </div>

</div>