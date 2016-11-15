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

        .adv_search_btn {
            float:right;
            font-size:x-small;
            text-decoration: underline;
            cursor: pointer;
            margin-right: 8px;
        }
    </style>


<?php

//set the form vars
$action             = $this->getAction();
$institutionCode    = $this->getInstitutionCode();
$mode               = $this->getMode();
$sort               = $this->getSrt();
$vid                = $this->getVid();
$select_tabs        = $this->getSelectTabs();

//set hidden field vars
$displayMode        = $this->getDisplayMode();
$bulkSize           = $this->getBulkSize();
$highlight          = $this->getHighlight();
$dum                = $this->getDum();
$displayField       = $this->getDisplayField();
$pcAvailabilityMode = $this->getPcAvailabilityMode();
$primo_default_search_scope = $this->getPrimoDefaultSearchScope();


if( (!empty($this->flashMessage)) ) {
    //something went wrong
    echo $this->flashMessage;
} else { ?>

    <form class="pure-form" name="primo-search-form" method="get" target="_blank" action="<?php echo $action; ?>" enctype="application/x-www-form-urlencoded; charset=utf-8">
        <div class="formEntryArea">

            <input type="text" class="form-control" name="primo-search-box-query-temp" value="" size="35">

            <br><br>
            <!-- Customizable Parameters -->
            <input type="hidden" name="institution" value="<?php echo $institutionCode; ?>">
            <input type="hidden" name="vid" value="<?php echo $vid; ?>">
            
            
            <?php if(!empty($sort)) {
                echo "<input type=\"hidden\" id=\"srt\" name=\"srt\" value=".$sort." />";
            } ?>
            

            <input type="hidden" name="mode" value="<?php echo $mode; ?>">


            <!-- Fixed parameters -->
            <input type="hidden" name="displayMode" value="<?php echo $displayMode; ?>">
            <input type="hidden" name="bulkSize" value="<?php echo $bulkSize; ?>">
            <input type="hidden" name="highlight" value="<?php echo $highlight; ?>">
            <input type="hidden" name="dum" value="<?php echo $dum; ?>">
            <input type="hidden" name="displayField" value="<?php echo $displayField; ?>">

            <!-- Enable this if "Expand My Results" is enabled by default in Views Wizard -->
            <input type="hidden" name="pcAvailabiltyMode" value="<?php echo $pcAvailabilityMode; ?>">



            <select class="form-control" id="tab" name="tab">
                <?php foreach($select_tabs as $select): ?>
                    <option data-scope="<?php echo $select['search_scope']; ?>" value="<?php echo $select['value']; ?>" id="<?php echo $select['id']; ?>" name="<?php echo $select['name']; ?>"><?php echo $select['label']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>


            <input type="hidden" class="search_scope" name="search_scope" value="<?php echo $primo_default_search_scope; ?>">




            <span class="adv_search_btn">
                 <a name="adv_search_btn">More Options</a>
            </span>

            <br><br>

            <div name="advanced_search_container">
                <select class="form-control" name="precisionOperator">

                    <option value="contains" class="EXLSelectedOption">
                        that contain my query words
                    </option>
                    <option value="exact" class="EXLSelectOption">
                        with my exact phrase
                    </option>

                </select>
                <br><br>

                <select class="form-control" name="rtype">
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

            </div>

            <input type="hidden" name="query">

            <!-- Search Button -->
            <input title="Search" type="button" value="Search" alt="Search" class="button pure-button pure-button-primary search-primo-btn">
        </div>
    </form>

    <script>
        if (jQuery.isFunction(primoSearchBox)) {
            var primoSearchBox = primoSearchBox();
            primoSearchBox.init();
        }else if (primoSearchBox){
            primoSearchBox.init();
        }
    </script>

<?php } ?>