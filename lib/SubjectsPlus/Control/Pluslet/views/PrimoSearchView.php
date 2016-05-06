<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 4/20/16
 * Time: 1:23 PM
 */

?>
<style>
    .portal-column .pure-form input, .portal-column .pure-form select, .portal-column .pure-form textarea {
        width: 98%;
        margin-left: auto;
        margin-right: auto;
    }
</style>
<script type="text/javascript">
    function searchPrimo() {
        document.getElementById("primoQuery").value = document.getElementById("rtype").value + "," + document.getElementById("precisionOperator").value + "," + document.getElementById("primoQueryTemp").value.replace(/[,]/g, " ");
        document.forms["searchForm"].submit();
    }
</script>

<?php

//get the form vars
$action          = $this->getAction();
$institutionCode = $this->getInstitutionCode();
$mode            = $this->getMode();
$sort            = $this->getSrt();
$vid             = $this->getVid();
$scope           = $this->getScope();


if( (empty($action)) || (empty($institutionCode)) || (empty($mode)) || (empty($sort)) || (empty($vid)) || (empty($scope)) ) {

    echo "<p>Please have your SubjectsPlus Admin add the settings for your institution's Primo account.</p>";
    echo "<p>The file is located at /lib/SubjectsPlus/Control/Pluslet/PrimoSearch.php</p>";

} else { ?>

<form class="pure-form" id="simple" name="searchForm" method="get" target="_blank" action="<?php echo $action; ?>" enctype="application/x-www-form-urlencoded; charset=utf-8" onsubmit="searchPrimo()">
    <div class="formEntryArea">

        <input type="text" class="form-control" id="primoQueryTemp" value="" size="35">
        <br><br>

        <!-- Customizable Parameters -->
        <input type="hidden" name="institution" value="<?php echo $institutionCode; ?>">
        <input type="hidden" name="vid" value="<?php echo $vid; ?>">
        <input type="hidden" id="srt" name="srt" value="<?php echo $this->getSrt(); ?>" />

        <select class="form-control" id="tab" name="tab">
            <option value="everything" id="everything_tab" name="everything_tab">Everything</option>
            <option value="default_tab" id="default_tab" name="default_tab">Library Catalog</option>
        </select>
            <br><br>
        <input type="hidden" name="mode" value="Basic">
            <select class="form-control" id="precisionOperator"
                    name="precisionOperator">

                <option value="contains" class="EXLSelectedOption">
                    that contain my query words
                </option>
                <option value="exact" class="EXLSelectOption">
                    with my exact phrase
                </option>

            </select>
            <br><br>

        <select class="form-control" id="rtype" name="rtype">
            <option  value="any" id="scope_anyall1" selected="selected" class="EXLSelectedOption">
                anywhere in the record
            </option>
            <option  value="title" id="scope_titleall1" class="EXLSelectOption">
                Title
            </option>
            <option  value="creator" id="scope_creatorall1" class="EXLSelectOption">
                Author/Creator
            </option>
            <option  value="sub" id="scope_suball1" class="EXLSelectOption">
                Subject
            </option>
            <option  value="desc" id="scope_lsr06all1" class="EXLSelectOption">
                Description
            </option>
            <option  value="isbn" id="scope_isbnall1" class="EXLSelectOption">
                ISBN
            </option>
        </select>
        <br><br>

        <!-- Fixed parameters -->
        <input type="hidden" name="displayMode" value="full">
        <input type="hidden" name="bulkSize" value="10">
        <input type="hidden" name="highlight" value="true">
        <input type="hidden" name="dum" value="true">
        <input type="hidden" name="query" id="primoQuery">
        <input type="hidden" name="displayField" value="all">

        <!-- Enable this if "Expand My Results" is enabled by default in Views Wizard -->
        <input type="hidden" name="pcAvailabiltyMode" value="true">

        <!-- Search Button -->
        <input id="go" title="Search" onclick="searchPrimo()" type="button" value="Search" alt="Search" style="height: 22px; font-size: 12px; font-weight: bold; background: #DE6E17; color: #ffffff; border: 1px solid;">
    </div>
</form>



<?php } ?>