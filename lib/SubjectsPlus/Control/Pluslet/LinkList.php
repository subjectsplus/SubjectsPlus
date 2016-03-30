<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 3/18/16
 * Time: 3:04 PM
 */

namespace SubjectsPlus\Control;
require_once 'Pluslet.php';

class Pluslet_LinkList extends Pluslet
{


    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "LinkList";
        $this->_pluslet_id = $pluslet_id;
        $this->_subject_id = $subject_id;
        $this->_isclone = $isclone;




    }


    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-list-alt\" title=\"" . _("Link List") . "\" ></i><span class=\"icon-text\">" . _("Link List") . "</span>";
        return $icon;
    }

    static function getMenuName()
    {
        return _('Link List');
    }



    protected function onViewOutput() {

        $xmlstring = $this->_body;
        $array = $this->convertXmlToViewableList($xmlstring);
        //var_dump($array);

        $this->_linkList = $array;

        $this->_body = $this->loadHtml(__DIR__ . '/views/LinkListView.php');
    }


    protected function onEditOutput() {


        //print_r($this->_body);
        if( ($this->_body != null) && ($this->_pluslet_id != null) ) {
            $xmlstring = $this->_body;
            $array = $this->convertXmlToViewableList($xmlstring);
            //var_dump($array);

            $this->_linkList = $array;
            $this->_body = $this->loadHtml(__DIR__ . '/views/LinkListEdit.php');

        } else {

            $this->_body = $this->loadHtml(__DIR__ . '/views/LinkListNew.php');
        }


    }

    protected function getData() {
        $data = "<linkList>
                    <topContent>top content</topContent>
                         <record>
                            <title>Biological Sciences (ProQuest)</title>
                            <recordId>98</recordId>
                            <displayOptions>
                                <showIcons>1</showIcons>
                                <showDesc>0</showDesc>
                                <showNote>0</showNote>
                            </displayOptions>			
                        </record>
                        <record>
                            <title> Sciences</title>
                            <recordId>198</recordId>
                            <displayOptions>
                                <showIcons>0</showIcons>
                                <showDesc>1</showDesc>
                                <showNote>0</showNote>
                            </displayOptions>			
                        </record>
                        <record>
                            <title>ProQuest</title>
                            <recordId>83</recordId>
                            <displayOptions>
                                <showIcons>1</showIcons>
                                <showDesc>1</showDesc>
                                <showNote>1</showNote>
                            </displayOptions>			
                        </record>
                    <bottomContent>bottom content</bottomContent>
                </linkList>";

        return $data;
    }

    protected function convertXmlToViewableList($xmlstring = null) {
        $xml = simplexml_load_string($xmlstring);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        return $array;
    }



}