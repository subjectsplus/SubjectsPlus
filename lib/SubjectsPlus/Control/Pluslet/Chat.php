<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 4/15/16
 * Time: 11:28 AM
 */

namespace SubjectsPlus\Control;
require_once("Pluslet.php");


class Pluslet_Chat extends Pluslet
{

    private $_jid;
    private $_src;
    private $_width;
    private $_height;

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "Chat";
        $this->_pluslet_bonus_classes = "type-chat";
    }

    /*
     * Admin must set the jid and src with the LibraryH3lp chat account information from their institution
     * http://docs.libraryh3lp.com/index.html
     */
    protected function onViewOutput()
    {
        //$this->setJid();
        //$this->setSrc();
        $this->setWidth("100%");
        $this->setHeight("350px");


        $output = $this->loadHtml(__DIR__ . '/views/ChatView.php');

        $this->_body = "$output";
    }

    protected function onEditOutput()
    {
        $this->_body = "<p class=\"faq-alert\">" . _("Click 'Save Changes' to view your Chat box.") . "</p>";
    }

    static function getMenuName()
    {
        return _('Chat');
    }

    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa fa-weixin\" title=\"" . _("Chat") . "\" ></i><span class=\"icon-text chat-text\">" . _("Chat") . "</span>";
        return $icon;
    }

    /**
     * @return string
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * @param string $height
     * @return Pluslet_Chat
     */
    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    /**
     * @return string
     */
    public function getJid()
    {
        return $this->_jid;
    }

    /**
     * @param string $jid
     * @return Pluslet_Chat
     */
    public function setJid($jid)
    {
        $this->_jid = $jid;
        return $this;
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->_src;
    }

    /**
     * @param string $src
     * @return Pluslet_Chat
     */
    public function setSrc($src)
    {
        $this->_src = $src;
        return $this;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * @param string $width
     * @return Pluslet_Chat
     */
    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }



}