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

//get the form vars
$pluslet = $this->_pluslet_view;

//set the form vars
$action             = $pluslet['_action'];
$institutionCode    = $pluslet['_institution_code'];
$mode               = $pluslet['_mode'];
$sort               = $pluslet['_srt'];
$vid                = $pluslet['_vid'];
$select_tabs        = $pluslet['_select_tabs'];

//set hidden field vars
$displayMode        = $pluslet['_displayMode'];
$bulkSize           = $pluslet['_bulkSize'];
$highlight          = $pluslet['_highlight'];
$dum                = $pluslet['_dum'];
$displayField       = $pluslet['_displayField'];
$pcAvailabilityMode = $pluslet['_pcAvailabilityMode'];


if( (!empty($this->flashMessage)) ) {
    //something went wrong
    echo $this->flashMessage;
} else { ?>

    <form class="pure-form" id="simple" name="searchForm" method="get" target="_blank" action="<?php echo $action; ?>" enctype="application/x-www-form-urlencoded; charset=utf-8">
        <div class="formEntryArea">

            <input type="text" class="form-control" id="primoQueryTemp" value="" size="35">

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

            <span class="adv_search_btn">
                <a id="adv_search_btn">More Options</a>
            </span>

            <br><br>

            <div id="advanced_search_container">
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

            </div>


            <input type="hidden" name="query" id="primoQuery">


            <!-- Search Button -->
            <input id="go" title="Search" onclick="searchPrimo()" type="button" value="Search" alt="Search" class="button pure-button pure-button-primary">
        </div>
    </form>



<?php } ?>


<script type="text/javascript">

    $('#advanced_search_container').hide();

    $('#adv_search_btn').on('click', function () {
        $('#advanced_search_container').toggle();
    })

/*
is this change func neccessary?

    $('input[name="search_scope"]').val($('select#tab').find('option:first').data('scope'));
    $('#tab').change(function () {
        $('input[name="search_scope"]').val($(this).find(':selected').data('scope'));
    })
 */
    function searchPrimo() {

        var primoQuery = $('#primoQuery').val();
        var primoQueryTemp = $('#primoQueryTemp').val().replace(/[,]/g, " ");
        var rtype = $('#rtype').val();
        var precisionOperator = $('#precisionOperator').val();

        if(rtype && precisionOperator) {

            $('#primoQuery').val(rtype + ',' + precisionOperator + ',' + primoQueryTemp)

        } else {

            $('#primoQuery').val('any,contains,' + primoQueryTemp);

        }

        //document.getElementById("primoQuery").value = document.getElementById("rtype").value + "," + document.getElementById("precisionOperator").value + "," + document.getElementById("primoQueryTemp").value.replace(/[,]/g, " ");
        document.forms["searchForm"].submit();
    }


</script>
