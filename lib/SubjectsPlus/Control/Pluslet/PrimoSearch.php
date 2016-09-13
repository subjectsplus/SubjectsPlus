<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 4/20/16
 * Time: 1:20 PM
 */

namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_PrimoSearch extends Pluslet
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


    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "PrimoSearch";
        $this->_pluslet_bonus_classes = "type-primosearch";

    }

    protected function onViewOutput()
    {

        //must be set these in config.php
        global $primo_action;
        global $primo_institution_code;


        if( empty($primo_action) || empty($primo_institution_code) ) {

            $flashMessge =  "<p>Please have your SubjectsPlus Admin add the settings for your institution's Primo account.</p>";
            $flashMessge .= "<p>The file is located at /control/includes/config.php</p>";

            $this->_body = $flashMessge;

        } else {

            //primo localization
            global $primo_mode;
            global $primo_srt;
            global $primo_vid;
            global $primo_select_tabs;

            //primo hidden fields
            global $primo_displayMode;
            global $primo_bulkSize;
            global $primo_highlight;
            global $primo_dum;
            global $primo_displayField;
            global $primo_pcAvailabilityMode;


            //set your local options
            $this->setAction($primo_action);
            $this->setInstitutionCode($primo_institution_code);
            $this->setMode($primo_mode);
            $this->setSrt($primo_srt);
            $this->setVid($primo_vid);
            $this->setSelectTabs($primo_select_tabs);

            //set hidden fields
            $this->setDisplayMode($primo_displayMode);
            $this->setBulkSize($primo_bulkSize);
            $this->setHighlight($primo_highlight);
            $this->setDum($primo_dum);
            $this->setDisplayField($primo_displayField);
            $this->setPcAvailabilityMode($primo_pcAvailabilityMode);

            //pass this to the view
            $this->_pluslet_view = $this->getPlusletVars();

            //set the view
            $this->_body = $this->loadHtml(__DIR__ . '/views/PrimoSearchView.php');
        }

    }


    private function getPlusletVars() {

        return get_object_vars($this);
    }

    protected function onEditOutput()
    {
        //$this->_body = "<p class=\"faq-alert\">" . _("Click 'Save Changes' to view your Primo Search box.") . "</p>";
        $this->onViewOutput();
    }

    static function getMenuName()
    {
        global $primo_flyout_icon_name;

        if(!empty($primo_flyout_icon_name)) {
            return _($primo_flyout_icon_name);
        } else {
            return _('Primo Search');
        }
    }

    static function getMenuIcon()
    {
        global $primo_flyout_icon_name;

        if(!empty($primo_flyout_icon_name)) {
            $menu_icon = $primo_flyout_icon_name;
        } else {
            $menu_icon = "Primo Search";
        }

        $icon="<i class=\"fa fa-search\" title=\"" . _("$menu_icon") . "\" ></i><span class=\"icon-text chat-text\">" . _("$menu_icon") . "</span>";
        return $icon;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->_action = $action;
    }

    /**
     * @return mixed
     */
    public function getInstitutionCode()
    {
        return $this->_institution_code;
    }

    /**
     * @param mixed $institution_code
     */
    public function setInstitutionCode($institution_code)
    {
        $this->_institution_code = $institution_code;
    }

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->_mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        $this->_mode = $mode;
    }

    /**
     * @return mixed
     */
    public function getSrt()
    {
        return $this->_srt;
    }

    /**
     * @param mixed $srt
     */
    public function setSrt($srt)
    {
        $this->_srt = $srt;
    }

    /**
     * @return mixed
     */
    public function getVid()
    {
        return $this->_vid;
    }

    /**
     * @param mixed $vid
     */
    public function setVid($vid)
    {
        $this->_vid = $vid;
    }

    /**
     * @return mixed
     */
    public function getPrimoFlyoutIconName()
    {
        return $this->_primo_flyout_icon_name;
    }

    /**
     * @param mixed $primo_flyout_icon_name
     */
    public function setPrimoFlyoutIconName($primo_flyout_icon_name)
    {
        $this->_primo_flyout_icon_name = $primo_flyout_icon_name;
    }

    /**
     * @return mixed
     */
    public function getSelectTabs()
    {
        return $this->_select_tabs;
    }

    /**
     * @param mixed $select_tabs
     */
    public function setSelectTabs($select_tabs)
    {
        $this->_select_tabs = $select_tabs;
    }

    /**
     * @return mixed
     */
    public function getDisplayMode()
    {
        return $this->_displayMode;
    }

    /**
     * @param mixed $displayMode
     */
    public function setDisplayMode($displayMode)
    {
        $this->_displayMode = $displayMode;
    }

    /**
     * @return mixed
     */
    public function getBulkSize()
    {
        return $this->_bulkSize;
    }

    /**
     * @param mixed $bulkSize
     */
    public function setBulkSize($bulkSize)
    {
        $this->_bulkSize = $bulkSize;
    }

    /**
     * @return mixed
     */
    public function getHighlight()
    {
        return $this->_highlight;
    }

    /**
     * @param mixed $highlight
     */
    public function setHighlight($highlight)
    {
        $this->_highlight = $highlight;
    }

    /**
     * @return mixed
     */
    public function getDum()
    {
        return $this->_dum;
    }

    /**
     * @param mixed $dum
     */
    public function setDum($dum)
    {
        $this->_dum = $dum;
    }

    /**
     * @return mixed
     */
    public function getDisplayField()
    {
        return $this->_displayField;
    }

    /**
     * @param mixed $displayField
     */
    public function setDisplayField($displayField)
    {
        $this->_displayField = $displayField;
    }

    /**
     * @return mixed
     */
    public function getPcAvailabilityMode()
    {
        return $this->_pcAvailabilityMode;
    }

    /**
     * @param mixed $pcAvailabilityMode
     */
    public function setPcAvailabilityMode($pcAvailabilityMode)
    {
        $this->_pcAvailabilityMode = $pcAvailabilityMode;
    }



}