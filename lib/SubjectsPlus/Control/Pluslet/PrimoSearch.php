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
    private $_scope;

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "PrimoSearch";
        $this->_pluslet_bonus_classes = "type-primosearch";
    }

    protected function onViewOutput()
    {
        /*
        $this->setAction("http://miami-primosb.hosted.exlibrisgroup.com/primo_library/libweb/action/search.do");
        $this->setInstitutionCode("01UOML");
        $this->setMode("Basic");
        $this->setSrt("rank");
        $this->setVid("uxtest2");
        $this->setScope("scope:(01UOML),scope:(01UOML_ALMA)");
        */
        
        //set your local options
        $this->setAction("");
        $this->setInstitutionCode("");
        $this->setMode("");
        $this->setSrt("");
        $this->setVid("");
        $this->setScope("");

        $output = $this->loadHtml(__DIR__ . '/views/PrimoSearchView.php');

        $this->_body = "$output";
    }

    protected function onEditOutput()
    {
        $this->_body = "<p class=\"faq-alert\">" . _("Click 'Save Changes' to view your Primo Search box.") . "</p>";
    }

    static function getMenuName()
    {
        return _('Primo Search');
    }

    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-search\" title=\"" . _("Primo Search") . "\" ></i><span class=\"icon-text chat-text\">" . _("Primo Search") . "</span>";
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
    public function getScope()
    {
        return $this->_scope;
    }

    /**
     * @param mixed $scope
     */
    public function setScope($scope)
    {
        $this->_scope = $scope;
    }


}