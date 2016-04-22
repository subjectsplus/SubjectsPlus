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


        document.forms["searchForm"].submit();
    }
    function searchKeyPress(e) {
        if (typeof e == 'undefined' && window.event) {
            e = window.event;
        }
        if (e.keyCode == 13) {
            document.getElementById('go').click();
        }
    }
</script>

<?php
if( (empty($this->getAction())) || (empty($this->getInstitutionCode())) ||
    (empty($this->getMode())) || (empty($this->getSrt())) ||
    (empty($this->getVid())) || (empty($this->getScope())) ) {

    echo "<p>Please have your SubjectsPlus Admin add the settings for your institution's Primo account.</p>";
    echo "<p>The file is located at /lib/SubjectsPlus/Control/Pluslet/PrimoSearch.php</p>";
} else { ?>


<form name="searchForm" role="search" method="get" action="<?php echo $this->getAction(); ?>" class="pure-form" enctype="application/x-www-form-urlencoded; charset=utf-8" id="simple" target="_blank" onsubmit="searchPrimo()">
    <div class="formEntryArea">
    <input type="hidden" name="institution" value="<?php echo $this->getInstitutionCode(); ?>">
    <input type="hidden" id="fn" name="fn" value="search" />
    <input type="hidden" id="ct" name="ct" value="search" />
    <input type="hidden" id="initialSearch" name="initialSearch" value="true" />
    <input type="hidden" id="mode" name="mode" value="<?php echo $this->getMode(); ?>" />


    <input type="hidden" id="indx" name="indx" value="1" />
    <input type="hidden" id="dum" name="dum" value="true" />
    <input type="hidden" id="srt" name="srt" value="<?php echo $this->getSrt(); ?>" />


    <input id="vid" name="vid" value="<?php echo $this->getVid(); ?>" type="hidden">

    <input type="hidden" id="frbg" name="frbg" value="" />
    <input type="hidden" id="tb" name="tb" value="t" />


    <input type="text" size="30" id="vl(freeText0)"  name="vl(freeText0)"  />

    <br><br>
    <select class="form-control" id="tab" name="tab">
        <option value="everything" id="everything_tab" name="everything_tab">Everything</option>
        <option value="default_tab" id="default_tab" name="default_tab">Library Catalog</option>
    </select>

    <br><br>
    <input type="hidden" id="scp.scps" name="scp.scps" value="<?php echo $this->getScope(); ?>" />

    <select class="form-control" id="vl(650122160UI1)" name="vl(650122160UI1)">
        <option value="all_items" id="all_items">All items</option>
        <option value="books" id="books">Books</option>
        <option value="articles" id="articles">Articles</option>
        <option value="journals" id="journals">Journals</option>
        <option value="government_documents" id="government_documents">Government Documents</option>
        <option value="video" id="video">Video</option>
        <option value="audio" id="audio">Audio</option>
    </select>

    <br><br>

    <select class="EXLSelectTag blue EXLSimpleSearchSelect" id="exlidInput_precisionOperator_1"
            name="vl(1UIStartWith0)">

        <option value="contains" class="EXLSelectedOption">
            that contain my query words
        </option>
        <option value="exact" class="EXLSelectOption">
            with my exact phrase
        </option>
        <option value="begins_with" class="EXLSelectOption">
            starts with
        </option>
    </select>

    <br><br>


    <select class="EXLSelectTag blue EXLSimpleSearchSelect" id="exlidInput_scope_all1" name="vl(618226781UI0)">
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
        <option  value="lsr06" id="scope_lsr06all1" class="EXLSelectOption">
            Call Number
        </option>
        <option  value="isbn" id="scope_isbnall1" class="EXLSelectOption">
            ISBN
        </option>
        <option  value="lsr01" id="scope_lsr01all1" class="EXLSelectOption">
            Course Reserves
        </option>
    </select>

    <br><br>


    <input id="go" class="pure-button pure-button-pluslet" type="button" value="Search" title="Search" onclick="searchPrimo();" alt="Search">

    </div>
</form>


<?php } ?>