<?php

/**
 *   @file Catalog.php
 *   @brief
 *   @author little9 (Jamie Little)
 *   @date June 2015
 */

namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_Catalog extends Pluslet {

    private $_action;
    private $_institution_code;
    private $_vid;
    private $_tab;
    private $_mode;
    private $_form_legend;

    //primo hidden fields
    private $_displayMode;
    private $_bulkSize;
    private $_highlight;
    private $_dum;
    private $_displayField;

    private $_target_fields;
    private $_search_scope;
    private $_advanced_search_link;

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

    $this->_type = "Catalog";
    $this->_pluslet_bonus_classes = "type-catalog";
    }

    protected function onEditOutput() {
        $this->onViewOutput();
    }

    protected function onViewOutput() {
        //set the pluslet vars so that view can use $this->get funcs
        $this->setPlusletVars();

        $action = $this->getAction();
        $institution_code = $this->getInstitutionCode();

        if( empty($action) || empty($institution_code) ) {
            $this->_body = $this->getFlashMessage();
        } else {
            //set the view
            $this->_body = $this->loadHtml(__DIR__ . "/views/CatalogView.php");
        }
    }

    private function getPlusletVars() {
        return get_object_vars($this);
    }

    protected function setPlusletVars() {
        $this->setAction();
        $this->setInstitutionCode();
        $this->setVid();
        $this->setMode();
        $this->setFormLegend();
        $this->setTab();
        $this->setDisplayMode();
        $this->setBulkSize();
        $this->setHighlight();
        $this->setDum();
        $this->setDisplayField();
        $this->setAdvancedSearchLink();
        $this->setTargetFields();
        $this->setSearchScope();
    }

    static function getMenuName() {
        global $catalog_primo_flyout_icon_name;
        if( isset($catalog_primo_flyout_icon_name) && (!empty($catalog_primo_flyout_icon_name)) ) {
            return _("$catalog_primo_flyout_icon_name");
        } else {
            return _('Catalog');
        }
    }

    static function getMenuIcon() {
        $icon="<i class=\"fa fa-book\" title=\"" . self::getMenuName() . "\" ></i><span class=\"icon-text\">" . self::getMenuName() . "</span>";
        return $icon;
    }

    public function getFlashMessage() {
        $flash_message = "";
        $flash_message .= "<p>". _("Please have your SubjectsPlus Admin add the settings for your institution's Primo account.") . "</p>";
        $flash_message .= "<p>". _("The file is located at /control/includes/config.php") . "</p>";
        return $flash_message;
    }

    /**
     * @return mixed
     */
    public function getAction() {
        return $this->_action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action = null) {
        if ( $action != null ) {
            $this->_action = $action;
        } else {
            global $catalog_primo_action;
            $this->_action = $catalog_primo_action;
        }
    }

    /**
     * @return mixed
     */
    public function getInstitutionCode() {
        return $this->_institution_code;
    }

    /**
     * @param mixed $institution_code
     */
    public function setInstitutionCode($institution_code = null) {
        if( $institution_code != null ) {
            $this->_institution_code = $institution_code;
        } else {
            global $catalog_primo_insitution_code;
            $this->_institution_code = $catalog_primo_insitution_code;
        }
    }

    /**
     * @return mixed
     */
    public function getVid() {
        return $this->_vid;
    }

    /**
     * @param mixed $vid
     */
    public function setVid($vid = null) {
        if ( $vid != null ) {
            $this->_vid = $vid;
        } else {
            global $catalog_primo_vid;
            $this->_vid = $catalog_primo_vid;
        }
    }

    /**
     * @return mixed
     */
    public function getTab() {
        return $this->_tab;
    }

    /**
     * @param mixed $tab
     */
    public function setTab($tab = null) {
        if( $tab != null ) {
            $this->_tab = $tab;
        } else {
            global $catalog_primo_tab;
            $this->_tab = $catalog_primo_tab;
        }
    }

    /**
     * @return mixed
     */
    public function getMode() {
        return $this->_mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode = null) {
        if( $mode != null ) {
            $this->_mode = $mode;
        } else {
            global $catalog_primo_mode;
            $this->_mode = $catalog_primo_mode;
        }
    }

    /**
     * @return mixed
     */
    public function getDisplayMode() {
        return $this->_displayMode;
    }

    /**
     * @param mixed $displayMode
     */
    public function setDisplayMode($displayMode = null) {
        if( $displayMode != null) {
            $this->_displayMode = $displayMode;
        } else {
            global $catalog_primo_displayMode;
            $this->_displayMode = $catalog_primo_displayMode;
        }
    }

    /**
     * @return mixed
     */
    public function getBulkSize() {
        return $this->_bulkSize;
    }

    /**
     * @param mixed $bulkSize
     */
    public function setBulkSize($bulkSize = null) {
        if( $bulkSize != null ) {
            $this->_bulkSize = $bulkSize;
        } else {
            global $catalog_primo_bulkSize ;
            $this->_bulkSize = $catalog_primo_bulkSize;
        }
    }

    /**
     * @return mixed
     */
    public function getHighlight() {
        return $this->_highlight;
    }

    /**
     * @param mixed $highlight
     */
    public function setHighlight($highlight = null) {
        if( $highlight != null ) {
            $this->_highlight = $highlight;
        } else {
            global $catalog_primo_highlight;
            $this->_highlight = $catalog_primo_highlight;
        }
    }

    /**
     * @return mixed
     */
    public function getDum() {
        return $this->_dum;
    }

    /**
     * @param mixed $dum
     */
    public function setDum($dum = null) {
        if( $dum != null ) {
            $this->_dum = $dum;
        } else {
            global $catalog_primo_dum;
            $this->_dum = $catalog_primo_dum;
        }
    }

    /**
     * @return mixed
     */
    public function getDisplayField() {
        return $this->_displayField;
    }

    /**
     * @param mixed $displayField
     */
    public function setDisplayField($displayField = null) {
        if( $displayField != null ) {
            $this->_displayField = $displayField;
        } else {
            global $catalog_primo_displayField;
            $this->_displayField = $catalog_primo_displayField;
        }
    }

    /**
     * @return mixed
     */
    public function getTargetFields() {
        return $this->_target_fields;
    }

    /**
     * @param mixed $target_fields
     */
    public function setTargetFields($target_fields = null) {
        if( $target_fields != null ) {
            $this->_target_fields = $target_fields;
        } else {
            global $catalog_primo_target_fields;
            $this->_target_fields = $catalog_primo_target_fields;
        }
    }

    /**
     * @return mixed
     */
    public function getSearchScope() {
        return $this->_search_scope;
    }

    /**
     * @param mixed $search_scope
     */
    public function setSearchScope($search_scope = null) {
        if( $search_scope != null) {
            $this->_search_scope = $search_scope;
        } else {
            global $catalog_primo_search_scope;
            $this->_search_scope = $catalog_primo_search_scope;
        }
    }

    /**
     * @return mixed
     */
    public function getAdvancedSearchLink() {
        return $this->_advanced_search_link;
    }

    /**
     * @param mixed $advanced_search_link
     */
    public function setAdvancedSearchLink($advanced_search_link = null) {
        if( $advanced_search_link != null ) {
            $this->_advanced_search_link = $advanced_search_link;
        } else {
            global $catalog_primo_advanced_search_link;
            $this->_advanced_search_link = $catalog_primo_advanced_search_link;
        }
    }

    /**
     * @return mixed
     */
    public function getFormLegend() {
        return $this->_form_legend;
    }

    /**
     * @param mixed $form_legend
     */
    public function setFormLegend($form_legend = null) {
        if( $form_legend != null ) {
            $this->_form_legend = _($form_legend);
        } else {
            global $catalog_primo_legend;
            $this->_form_legend = _($catalog_primo_legend);
        }
    }



}