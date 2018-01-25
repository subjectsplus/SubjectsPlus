<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_QP
 *   @brief 
 *
 *   @author sandbergja
 *   @date Dec 2017
 *   @todo 
 */
class Pluslet_QP extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "QP";
        $this->_pluslet_bonus_classes = "type-chat";

        $this->_title = _("Chat with a librarian");
        $this->_hide_titlebar = false;
    }
  static function getMenuName()
  {
    return _('QuestionPoint Chat');
  }

    public static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-commenting-o\" title=\"" . _("Chat") . "\" ></i><span class=\"icon-text\">" . _("Chat") . "</span>";
        return $icon;
    }
    protected function onViewOutput()
    {
        global $qp_inst_id;
        if( empty($qp_inst_id) && strpos($_SERVER['REQUEST_URI'], 'control')) {
                $flashMessage = "<p>" . _("Please have your SubjectsPlus Admin add the settings for your institution's QuestionPoint Chat account.") . "</p>";
                $flashMessage .= "<p>" . _("The file is located at /control/includes/config.php") . "</p>";
		$this->_body = $flashMessage;
        } else {
                $this->_inst_id = empty($qp_inst_id) ? '13969' : $qp_inst_id; # Fallback to Oregon's Statewide Chat service in the public view
                $this->_extra = json_decode( $this->_extra, true );
	        $this->_color = isset($this->_extra['color']) ? $this->_extra['color'] : 'green';
                $this->_body = $this->loadHtml(__DIR__ . '/views/QPChatViewOutput.php');
        }
    }
    protected function onEditOutput()
    {
        if($this->_extra == "")
        {
            $this->_extra = array();

        }else
        {
            $this->_extra = json_decode( $this->_extra, true );
        }

        $this->_body = $this->loadHtml(__DIR__ . '/views/QPChatEditOutput.php');
    }
}

?>
