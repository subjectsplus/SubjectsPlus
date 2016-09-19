<?php
/**
 * @file ArticlesPlus.php
 * @brief
 * @author little9 (Jamie Little)
 * @date Auguest 2015
 */
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_ArticlesPlus extends Pluslet
{

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
    private $_search_scope;

    private $_facet;
    private $_facet_rtype;
    private $_articles;
    private $_show_only;


    public function __construct($pluslet_id, $flag = "", $subject_id, $isclone = 0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "ArticlesPlus";
        $this->_pluslet_bonus_classes = "type-articleplus";
    }

    protected function onEditOutput() {

        $this->_body = "<p class=\"faq-alert\">" . _("Click 'Save' to view your uSearch Articles search box.") . "</p>";

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
            $this->_body = $this->loadHtml(__DIR__ . "/views/ArticlesPlus.php");
        }

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
        $this->setSearchScope();
        $this->setFacet();
        $this->setFacetRtype();
        $this->setArticles();
        $this->setShowOnly();

    }


    static function getMenuName() {
        global $articlesplus_primo_flyout_icon_name;
        if( isset($articlesplus_primo_flyout_icon_name) && (!empty($articlesplus_primo_flyout_icon_name)) ) {
            return _("$articlesplus_primo_flyout_icon_name");
        } else {
            return _('uSearch Articles');
        }
    }

    static function getMenuIcon() {
        $icon = "<i class=\"fa fa-file-text-o\" title=\"" . self::getMenuName() . "\" ></i><span class=\"icon-text articlesplus-text\">" . self::getMenuName() . "</span>";
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
            global $articlesplus_primo_action;
            $this->_action = $articlesplus_primo_action;
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
            global $articlesplus_primo_insitution_code;
            $this->_institution_code = $articlesplus_primo_insitution_code;
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
            global $articlesplus_primo_vid;
            $this->_vid = $articlesplus_primo_vid;
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
            global $articlesplus_primo_tab;
            $this->_tab = $articlesplus_primo_tab;
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
            global $articlesplus_primo_mode;
            $this->_mode = $articlesplus_primo_mode;
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
            global $articlesplus_primo_legend;
            $this->_form_legend = _($articlesplus_primo_legend);
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
            global $articlesplus_primo_displayMode;
            $this->_displayMode = $articlesplus_primo_displayMode;
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
        if( $search_scope != null ) {
            $this->_search_scope = $search_scope;
        } else {
            global $articlesplus_primo_search_scope;
            $this->_search_scope = $articlesplus_primo_search_scope;
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
            global $articlesplus_primo_bulkSize ;
            $this->_bulkSize = $articlesplus_primo_bulkSize;
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
            global $articlesplus_primo_highlight;
            $this->_highlight = $articlesplus_primo_highlight;
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
            global $articlesplus_primo_dum;
            $this->_dum = $articlesplus_primo_dum;
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
            global $articlesplus_primo_displayField;
            $this->_displayField = $articlesplus_primo_displayField;
        }
    }

    /**
     * @return mixed
     */
    public function getFacet() {
        return $this->_facet;
    }

    /**
     * @param mixed $facet
     */
    public function setFacet($facet = null) {
        if( $facet != null ) {
            $this->_facet = $facet;
        } else {
            global $articlesplus_primo_ct;
            $this->_facet = $articlesplus_primo_ct;
        }
    }

    /**
     * @return mixed
     */
    public function getFacetRtype() {
        return $this->_facet_rtype;
    }

    /**
     * @param mixed $facet_rtype
     */
    public function setFacetRtype($facet_rtype = null) {
        if( $facet_rtype != null ) {
            $this->_facet_rtype = $facet_rtype;
        } else {
            global $articlesplus_primo_fctN;
            $this->_facet_rtype = $articlesplus_primo_fctN;
        }
    }

    /**
     * @return mixed
     */
    public function getArticles() {
        return $this->_articles;
    }

    /**
     * @param mixed $articles
     */
    public function setArticles($articles = null) {
        if( $articles != null ) {
            $this->_articles = $articles;
        } else {
            global $articlesplus_primo_fctV;
            $this->_articles = $articlesplus_primo_fctV;
        }
    }

    /**
     * @return mixed
     */
    public function getShowOnly() {
        return $this->_show_only;
    }

    /**
     * @param mixed $show_only
     */
    public function setShowOnly($show_only = null) {
        if( $show_only != null ) {
            $this->_show_only = $show_only;
        } else {
            global $articlesplus_primo_rfnGrp;
            $this->_show_only = $articlesplus_primo_rfnGrp;
        }
    }





}