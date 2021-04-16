<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 4/20/16
 * Time: 1:20 PM
 */

namespace SubjectsPlus\Control\Pluslet;
require_once("Pluslet.php");

class PrimoSearch extends \SubjectsPlus\Control\Pluslet
{

    private $_action;
    private $_institution_code;
    private $_mode;
    private $_srt;
    private $_vid;
    private $_select_tabs;
    //primo hidden fields
    private $_displayMode;
    private $_bulkSize;
    private $_highlight;
    private $_dum;
    private $_displayField;
    private $_pcAvailabilityMode;
    private $_primo_flyout_icon_name;
    private $_primo_default_search_scope;


    public function __construct($pluslet_id, $flag = "", $subject_id, $isclone = 0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "PrimoSearch";
        $this->_pluslet_bonus_classes = "type-primosearch";
    }

    protected function onEditOutput() {
        //$this->_body = "<p class=\"faq-alert\">" . _("Click 'Save Changes' to view your Primo Search box.") . "</p>";
        $this->onViewOutput();
    }

    protected function onViewOutput() {

        $this->setPlusletVars();

        $action = $this->getAction();
        $institution_code = $this->getInstitutionCode();

        if( empty($action) || empty($institution_code) ) {
            $this->_body = $this->getFlashMessage();
        } else {
            //set the view
            $this->_body = $this->loadHtml(__DIR__ . "/views/PrimoSearchView.php");
        }

    }


    private function getPlusletVars() {

        return get_object_vars($this);
    }

    protected function setPlusletVars() {

        //set your local options
        $this->setAction();
        $this->setInstitutionCode();
        $this->setMode();
        $this->setSrt();
        $this->setVid();
        $this->setSelectTabs();

        //set hidden fields
        $this->setDisplayMode();
        $this->setBulkSize();
        $this->setHighlight();
        $this->setDum();
        $this->setDisplayField();
        $this->setPcAvailabilityMode();
        $this->setPrimoDefaultSearchScope();

    }

    public function getFlashMessage() {
        $flash_message = "";
        $flash_message .= "<p>" . _("Please have your SubjectsPlus Admin add the settings for your institution's Primo account.") . "</p>";
        $flash_message .= "<p>" . _("The file is located at /control/includes/config.php") . "</p>";
        return $flash_message;
    }

    static function getMenuName() {
        global $primo_flyout_icon_name;

        if (!empty($primo_flyout_icon_name)) {
            return _($primo_flyout_icon_name);
        } else {
            return _('Primo Search');
        }
    }

    static function getMenuIcon() {
        global $primo_flyout_icon_name;

        if (!empty($primo_flyout_icon_name)) {
            $menu_icon = $primo_flyout_icon_name;
        } else {
            $menu_icon = "Primo Search";
        }

        $icon = "<i class=\"fa fa-search\" title=\"" . _("$menu_icon") . "\" ></i><span class=\"icon-text chat-text\">" . _("$menu_icon") . "</span>";
        return $icon;
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
        if( $action != null ) {
            $this->_action = $action;
        } else {
            global $primo_action;
            $this->_action = $primo_action;
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
            global $primo_institution_code;
            $this->_institution_code = $primo_institution_code;
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
            global $primo_mode;
            $this->_mode = $primo_mode;
        }
    }

    /**
     * @return mixed
     */
    public function getSrt() {
        return $this->_srt;
    }

    /**
     * @param mixed $srt
     */
    public function setSrt($srt = null) {
        if( $srt != null ) {
            $this->_srt = $srt;
        } else {
            global $primo_srt;
            $this->_srt = $primo_srt;
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
        if( $vid != null ) {
            $this->_vid = $vid;
        } else {
            global $primo_vid;
            $this->_vid = $primo_vid;
        }
    }

    /**
     * @return mixed
     */
    public function getPrimoFlyoutIconName() {
        return $this->_primo_flyout_icon_name;
    }

    /**
     * @param mixed $primo_flyout_icon_name
     */
    public function setPrimoFlyoutIconName($primo_flyout_icon_name = null) {
        if($primo_flyout_icon_name != null ) {
            $this->_primo_flyout_icon_name = $primo_flyout_icon_name;
        } else {
            global $primo_flyout_icon_name;
            $this->_primo_flyout_icon_name = $primo_flyout_icon_name;
        }
    }

    /**
     * @return mixed
     */
    public function getSelectTabs() {
        return $this->_select_tabs;
    }

    /**
     * @param mixed $select_tabs
     */
    public function setSelectTabs($select_tabs = null) {
        if( $select_tabs != null ) {
            $this->_select_tabs = $select_tabs;
        } else {
            global $primo_select_tabs;
            $this->_select_tabs = $primo_select_tabs;
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
        if( $displayMode != null ) {
            $this->_displayMode = $displayMode;
        } else {
            global $primo_displayMode;
            $this->_displayMode = $primo_displayMode;
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
            global $primo_bulkSize;
            $this->_bulkSize = $primo_bulkSize;
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
            global $primo_highlight;
            $this->_highlight = $primo_highlight;
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
            global $primo_dum;
            $this->_dum = $primo_dum;
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
            global $primo_displayField;
            $this->_displayField = $primo_displayField;
        }
    }

    /**
     * @return mixed
     */
    public function getPcAvailabilityMode() {
        return $this->_pcAvailabilityMode;
    }

    /**
     * @param mixed $pcAvailabilityMode
     */
    public function setPcAvailabilityMode($pcAvailabilityMode = null) {
        if( $pcAvailabilityMode != null ) {
            $this->_pcAvailabilityMode = $pcAvailabilityMode;
        } else {
            global $primo_pcAvailabilityMode;
            $this->_pcAvailabilityMode = $primo_pcAvailabilityMode;
        }
    }

    /**
     * @return mixed
     */
    public function getPrimoDefaultSearchScope() {
        return $this->_primo_default_search_scope;
    }

    /**
     * @param mixed $primo_default_search_scope
     */
    public function setPrimoDefaultSearchScope($primo_default_search_scope = null) {

        if($primo_default_search_scope != null) {
            $this->_primo_default_search_scope = $primo_default_search_scope;
        } else {
            global $primo_default_search_scope;
            $this->_primo_default_search_scope = $primo_default_search_scope;
        }

    }




}