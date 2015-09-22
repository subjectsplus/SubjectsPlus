<?php
   namespace SubjectsPlus\Control;
     require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_5
 *   @brief The number corresponds to the ID in the database.  Numbered pluslets are UNEDITABLE clones
 * 		this one displays the default Catalog Search Box
 *     YOU WILL NEED TO LOCALIZE THIS!
 *
 *   @author agdarby
 *   @date Feb 2011
 *   @todo 
 */

class Pluslet_5 extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id) {

        parent::__construct($pluslet_id, $flag, $subject_id);

        $this->_editable = FALSE;
        $this->_subject_id = $subject_id;
        $this->_pluslet_id = 5;
        $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
        $this->_title = _("Book Search");
    }

    public function output($action="", $view="public") {

        // public vs. admin
        parent::establishView($view);
        // example form action:  http://icarus.ithaca.edu/cgi-bin/Pwebrecon.cgi?
        $this->_body = '
            <div class="book-search">
            <form action="" method="get" name="querybox" id="querybox" class="pure-form" target="_blank">
            <fieldset>
            <input type="hidden" value="local" name="DB" />
            <select name="Search_Code" class="search_smaller">
            <option value="CMD*">Keyword (use and/or)</option>
            <option value="FT*">Keyword Anywhere</option>
            <option value="TALL">Title (omit initial a, an, the)</option>
            <option value="JALL">Journal Title (omit initial a, an, the)</option>
            <option value="NAME_">Author (last name, first name)</option>
            <option value="AUTH_">Author/Composer (sorted by title)</option>
            <option value="SUBJ_">Subject (person, place, thing)</option>
            <option value="CALL_">Call Number</option>
            </select>
            <br />
            <input maxlength="800" size="25" name="Search_Arg" class="search_smaller" />
            
            <input type="hidden" value="all of these" name="BOOL1" /> 
            <input type="hidden" value="1" name="HIST" />
            <input type="hidden" name="HIST" value="1" />
            
            <input name="SUBMIT" type="submit" value="Search" class="pure-button pure-button-pluslet" />
            <input type="hidden" value="25" name="CNT" />
            </fieldset>
            </form>
            </div>
            ';



        parent::assemblePluslet();

        return $this->_pluslet;
    }

    static function getMenuName()
  {
    return _('Books: Use the Library Catalog');
  }

  static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-book\" title=\"" . _(" Books: Use the Library Catalog") . "\" ></i><span class=\"icon-text\">". _(" Book Search") . "</span>";
        return $icon;
    }

}

?>