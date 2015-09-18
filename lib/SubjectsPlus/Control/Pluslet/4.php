<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_3
 *   @brief The number corresponds to the ID in the database.  Numbered pluslets are UNEDITABLE clones
 * 	this one displays FAQs for that subject
 *
 *   @author agdarby
 *   @date Mar 2011
 *   @todo
 */
class Pluslet_4 extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id) {
        parent::__construct($pluslet_id, $flag, $subject_id);

        $this->_editable = FALSE;
        $this->_subject_id = $subject_id;
        $this->_pluslet_id = 4;
        $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
        $this->_title = _("FAQs");
    }

    public function output($action="", $view="public") {
        global $PublicPath;
        global $CpanelPath;

        // public vs. admin
        parent::establishView($view);

        // Get librarians associated with this guide
        $querier = new Querier();
        $qs = "SELECT f.faq_id, question, answer from faq f, faq_subject fs WHERE f.faq_id = fs.faq_id and fs.subject_id = " . $this->_subject_id . " ORDER BY question";

        //print $qs;

        $faqArray = $querier->query($qs);


        if ($faqArray) {
            $this->_body = "<ul>";
            foreach ($faqArray as $value) {
                $short_q = Truncate($value["question"], 150, '');

                $this->_body .= "<li><a target=\"_blank\" href=\"$PublicPath" . "faq.php?faq_id=$value[0]\">$short_q</a></li>\n";
            }

            $this->_body .= "</ul>";
        } else {
            $this->_body = "<p class=\"faq-alert\">" . _("There are no FAQs linked for this guide") . "</p>";

        if ($view =="admin") {

            $this->_body = "<p class=\"faq-alert\">" . _("There are no FAQs linked for this guide") . "</p>
                            <p><i class=\"fa fa-plus-square\"></i> <a href=\"../faq/faq.php?faq_id=&amp;wintype=pop\" class=\"showmedium-reloader\">" . _("Add New FAQ") . "</a></p>";
            }
        }

        parent::assemblePluslet();

        return $this->_pluslet;
    }
    
    static function getMenuName()
  {
  	return _('FAQs');
  }

  static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-question-circle\" title=\"" . _("FAQs") . "\" ></i><span class=\"icon-text\">" . _("FAQs") . "</span>";
        return $icon;
    }


}

?>
