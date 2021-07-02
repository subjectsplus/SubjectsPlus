<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 11/18/15
 * Time: 11:12 AM
 */

namespace SubjectsPlus\Control;
require_once("Pluslet.php");


class Pluslet_Card extends Pluslet
{

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "Card";
        $this->_pluslet_bonus_classes = "type-card";

        if ($this->_extra != "") {
            $this->_extra = json_decode($this->_extra, true);
        }

        $querier = new Querier();
        if($this->_pluslet_id != '') {

            $qry = "SELECT title FROM pluslet WHERE pluslet_id = {$this->_pluslet_id}";
            $qry_result = $querier->query($qry);
            $this->_card_title = $qry_result;

        } else {

            $this->_card_title = "";
        }


    }

    protected function onEditOutput()
    {




        global $CKPath;
        global $CKBasePath;

        include ($CKPath);
        global $BaseURL;


        $oCKeditor = new CKEditor($CKBasePath);
        $oCKeditor->timestamp = time();
        //$oCKeditor->config['ToolbarStartExpanded'] = true;
        $config['toolbar'] = 'ImageOnly';
        $config['height'] = '300';
        $config['filebrowserUploadUrl'] = $BaseURL . "ckeditor3/php/uploader.php";

        $this_instance = "cardEditor";
        $this->_editor = $oCKeditor->editor($this_instance, $this->_body, $config);

        $this->_body = $this->loadHtml(__DIR__ . '/views/CardEdit.php');

    }

    protected function onViewOutput()
    {



        $output = $this->loadHtml(__DIR__ . '/views/Card.php');

        $this->_body = "$output";

    }

    static function getMenuName()
    {
        return _('Card Image');
    }

    static function getMenuIcon()
    {
        $icon="<span class=\"icon-text guidesearch-text\">" . _("Image Card") . "</span>";
        return $icon;
    }


}