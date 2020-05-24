<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_LibChat
 *   @brief 
 *
 *   @author sandbergja
 *   @date May 2020
 *   @todo 
 */
class Pluslet_LibChat extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "LibChat";
        $this->_pluslet_bonus_classes = "type-chat";

        $this->_title = _("Chat with a librarian");
        $this->_hide_titlebar = false;
    }
  static function getMenuName()
  {
    return _('LibChat');
  }

    public static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-commenting-o\" title=\"" . _("LibChat") . "\" ></i><span class=\"icon-text\">" . _("LibChat") . "</span>";
        return $icon;
    }
    protected function onViewOutput()
    {
        global $libchat_hash;
        if( empty($libchat_hash)) {
                if( strpos($_SERVER['REQUEST_URI'], 'control')) {
                    $flashMessage = "<p>" . _("Please have your SubjectsPlus Admin add the settings for your institution's LibChat hash.") . "</p>";
                    $flashMessage .= "<p>" . _("The file is located at /control/includes/config.php") . "</p>";
                } else {
                    $flashMessage = "<p>" . _("The chat feature is not currently available.") . "</p>";
                    $flashMessage .= "<p>" . _("Please contact your library directly.") . "</p>";
                }
		$this->_body = $flashMessage;
        } else {
                $this->_libchat_widget_hash = $libchat_hash;
                $this->_body = $this->loadHtml(__DIR__ . '/views/LibChatViewOutput.php');
        }
    }
    protected function onEditOutput()
    {
        if($this->_extra == "") {
            $this->_extra = array();
        } else {
            $this->_extra = json_decode( $this->_extra, true );
        }

        $this->_body = "<p class=\"faq-alert\">" . _("Click 'Save' to view your search box.") . "</p>";
    }
}

?>
