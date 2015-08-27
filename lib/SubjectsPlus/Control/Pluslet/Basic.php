<?php
   namespace SubjectsPlus\Control;

   require_once("Pluslet.php");

/**
 *   @file sp_Pluslet_Basic
 *   @brief
 *
 *   @author agdarby
 *   @date Feb 2011
 *   @todo
 */




    class Pluslet_Basic extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
    }

   	static function getMenuIcon()
   	{
   		$icon="<i class=\"fa fa-pencil-square-o\" title=\"" . _("Editable Box") . "\" ></i><span class=\"icon-text\">" . _("Editable Box") . "</span>";
        return $icon;
   	}

    static function getMenuName()
    {
        return _('Editable Box');
    }

    public function output($action="", $view) {

		global $CKPath;
		global $CKBasePath;

        parent::establishView($view);

        if ($action == "edit") {

            // make an editable body and title type

            global $title_input_size; // alter size based on column
            //
            //////////////////////
            // New or Existing?
            //////////////////////

            if ($this->_pluslet_id) {
                $this->_pluslet_id_field = "pluslet-" . $this->_pluslet_id;
                $this->_pluslet_bonus_classes = "basic-pluslet";
                $this->_pluslet_name_field = "";
                $clean_title = addslashes(htmlentities($this->_title));
                $this->_title = "<input type=\"text\" class=\"edit-input\" id=\"pluslet-update-title-$this->_pluslet_id\" value=\"$clean_title\" size=\"$title_input_size\" />";
                $this_instance = "pluslet-update-body-$this->_pluslet_id";
            } else {
                $new_id = rand(10000, 100000);
                $this->_pluslet_bonus_classes = "unsortable basic-pluslet";
                $this->_pluslet_id_field = $new_id;
                $this->_pluslet_name_field = "new-pluslet-Basic";
                $this->_title = "<input type=\"text\" class=\"edit-input\" id=\"pluslet-new-title-$new_id\" name=\"new_pluslet_title\" value=\"$this->_title\" size=\"$title_input_size\" />";
                $this_instance = "pluslet-new-body-$new_id";
            }

			include ($CKPath);
			global $BaseURL;


			$oCKeditor = new CKEditor($CKBasePath);
			$oCKeditor->timestamp = time();
			//$oCKeditor->config['ToolbarStartExpanded'] = true;
			$config['toolbar'] = 'SubsPlus_Narrow';
			$config['height'] = '300';
			$config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";


			// Create and output object
			print parent::startPluslet();
			$this->_body = $oCKeditor->editor($this_instance, $this->_body, $config);
			print parent::finishPluslet();

			return;
		} else {

			// notitle hack
			if (!isset( $this->_hide_titlebar ))
			{
				if(trim($this->_title) == "notitle") { $this->_hide_titlebar = 1;} else {$this->_hide_titlebar = 0;}
			}

			// Look for tokens, tokenize
			parent::tokenizeText();

			parent::assemblePluslet($this->_hide_titlebar);

			return $this->_pluslet;
		}
	}

}

?>
