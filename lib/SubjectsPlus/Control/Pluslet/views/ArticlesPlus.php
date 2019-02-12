<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 9/19/16
 * Time: 3:12 PM
 */


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
$searchScope          = $this->getSearchScope();
$facet                = $this->getFacet();
$facet_rtype          = $this->getFacetRtype();
$articles             = $this->getArticles();
$show_only            = $this->getShowOnly();


if( (!empty($this->flashMessage)) ) {
    //something went wrong
    echo $this->flashMessage;
} else { ?>

<div class="articlesplus-search">
    <form action="<?php echo $action; ?>" enctype="application/x-www-form-urlencoded; charset=utf-8" method="GET" id="summon_search2" class="pure-form pluslet-articles-plus-form" target="_blank" name="articlesplusSearchForm">
        <fieldset>

            <!-- Customizable Parameters -->
            <input type="hidden" name="institution" value="<?php echo $institutionCode; ?>">
            <input type="hidden" name="vid" value="<?php echo $vid; ?>">
            <input type="hidden" name="tab" value="<?php echo $tab; ?>">
            <input type="hidden" name="mode" value="<?php echo $mode; ?>">
            <input type="hidden" name="search_scope" value="<?php echo $searchScope; ?>">

            <!-- Fixed parameters -->
            <input type="hidden" name="displayMode" value="<?php echo $displayMode; ?>">
            <input type="hidden" name="bulkSize" value="<?php echo $bulkSize; ?>">
            <input type="hidden" name="highlight" value="<?php echo $highlight; ?>">
            <input type="hidden" name="dum" value="<?php echo $dum; ?>">
            <input type="hidden" name="query" id="primoQueryArticles">
            <input type="hidden" name="displayField" value="<?php echo $displayField; ?>">

            <!-- Articles only -->
            <input type="hidden" name="ct" value="<?php echo $facet; ?>">
            <input type="hidden" name="fctN" value="<?php echo $facet_rtype; ?>">
            <input type="hidden" name="fctV" value="<?php echo $articles; ?>">
            <input type="hidden" name="rfnGrp" value="<?php echo $show_only; ?>">


            <legend><?php echo $form_legend; ?></legend>
            <input type="text" id="primoQueryTempArticles" value="" class="searchinput-1" />
            <input id="search-articles-plus" title="Search" type="submit" value="Search" alt="Search" class="pure-button pure-button-pluslet pluslet-articles-plus-form-button">

        </fieldset>
    </form>
</div>

<?php } ?>