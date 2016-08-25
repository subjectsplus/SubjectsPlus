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

        //must be set these in config.php
        global $chat_jid;
        global $chat_src;
        global $chat_height;
        global $chat_width;

        if( empty($chat_jid) || empty($chat_src) ) {

            $flashMessge =  "<p>Please have your SubjectsPlus Admin add the settings for your institution's Chat account.</p>";
            $flashMessge .= "<p>The file is located at /control/includes/config.php</p>";

            $this->_body = $flashMessge;

        } else {

            $this->setJid($chat_jid);
            $this->setSrc($chat_src);
            $this->setWidth($chat_width);
            $this->setHeight($chat_height);
        }

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