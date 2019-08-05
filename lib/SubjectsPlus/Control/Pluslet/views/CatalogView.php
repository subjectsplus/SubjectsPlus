<?php

//get the form vars
$action               = $this->getAction();
$institutionCode      = $this->getInstitutionCode();
$vid                  = $this->getVid();
$tab                  = $this->getTab();
$mode                 = $this->getMode();
$form_legend          = $this->getFormLegend();
$displayMode          = $this->getDisplayMode();
$bulkSize             = $this->getBulkSize();
$highlight            = $this->getHighlight();
$dum                  = $this->getDum();
$displayField         = $this->getDisplayField();
$advanced_search_link = $this->getAdvancedSearchLink();
$target_fields        = $this->getTargetFields();
$search_scope         = $this->getSearchScope();


if( (!empty($this->flashMessage)) ) {
  //something went wrong
  echo $this->flashMessage;
} else { ?>

<style>
  .portal-column .pure-form legend {
    border-bottom: none;
    color: #000000;
    font-weight: normal;
    font-style: normal;
    width: 94%;
  }

  .portal-column .pure-form input, .portal-column .pure-form select, .portal-column .pure-form textarea {
    width: 90%;

  }

</style>
<div class="catalog-search">
  <form action="<?php echo $action; ?>" method="get" name="search-primo-catalog-form" id="search-primo-catalog-form" class="pure-form" target="_blank" enctype="application/x-www-form-urlencoded; charset=utf-8">

  <fieldset>
  <!-- Optional legend:-->
  <legend><?php echo $form_legend; ?></legend>

  <div class="formEntryArea">

    <!-- Customizable Parameters -->
    <input type="hidden" name="institution" value="<?php echo $institutionCode; ?>">
    <input type="hidden" name="vid" value="<?php echo $vid; ?>">
    <input type="hidden" name="tab" value="<?php echo $tab; ?>">
    <input type="hidden" name="mode" value="<?php echo $mode; ?>">

     <!-- Fixed parameters -->
    <input type="hidden" name="displayMode" value="<?php echo $displayMode; ?>">
    <input type="hidden" name="bulkSize" value="<?php echo $bulkSize; ?>">
    <input type="hidden" name="highlight" value="<?php echo $highlight; ?>">
    <input type="hidden" name="dum" value="<?php echo $dum; ?>">
    <input type="hidden" name="query" id="primo-catalog-query">
    <input type="hidden" name="displayField" value="<?php echo $displayField; ?>">


    <input type="text" id="primo-catalog-query-temp" value="">&nbsp;&nbsp;

    <br/>

    <!-- our target fields -->
    <select name="searchtype" id="primo-catalog-searchtype" style="vertical-align:middle;">
        <?php foreach($target_fields as $key => $value): ?>
            <option value="<?php echo $key;?>"><?php echo $value; ?></option>
        <?php endforeach; ?>
    </select>

    <br />

    <span class="wcat-label"><?php echo _("Limit search to:"); ?></span><br />

    <select name="search_scope" id="searchscope">
        <?php foreach($search_scope as $key => $value): ?>
            <option value="<?php echo $key;?>"><?php echo $value; ?></option>
        <?php endforeach; ?>
    </select>

    <br />
    <input id="search-primo-catalog" title="Search" type="submit" value="Search" alt="Search Catalog" class="pure-button pure-button-pluslet pluslet-catalog-form-button">

    <br />
    <span class="adv-prompt"><a href="<?php echo $advanced_search_link; ?>" target="_blank"><?php echo _("Advanced Search"); ?></a></span>

  </div><!-- end formEntryArea -->

  </fieldset>


  </form>
</div>


<?php } ?>