<?php
/**
 *   @file WorldCat.php
 *   @brief A worldcat search box
 *   @author little9 (Jamie Little)
 *   @date June 2015
 */
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_WorldCat extends Pluslet {
  public $flashMessage;

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "WorldCat";
    $this->_pluslet_bonus_classes = "type-worldcat";

    // print_r( array(
    //   'var_dump($this)' => var_dump($this)
    // ));
    // die;
  }

  protected function onEditOutput() {
    global $worldcat_search_url;

    $this->_body = "<p class=\"faq-alert\">" . _("Click 'Save' to view your search box.") . "</p>";   
  }

  protected function onViewOutput() {
    global $worldcat_search_url;

    if ( isset($worldcat_search_url) ) {
      if ( trim($worldcat_search_url) == '' && isset($GLOBALS['canedit']) ) {
        $this->flashMessage =
          "<div
            class=\"faq-alert\"
            id=\"worldcat-notification-box\"
            style=\"padding: 3px 10px; border-radius: 2px;\">
              <p>Please have your SubjectsPlus Admin add the settings for your institution's WorldCat Search account.</p>
              <p>This setting can be found in:
                <br><strong>Admin > Config Site > Catalog > Catalog Settings</strong>.
              </p>
          </div>
          <br>";
      };
    };

    $output = $this->loadHtml(__DIR__ . '/views/WorldCat.html');
      
    $this->_body = "$output";
  }

  static function getMenuName() {
    return _('WorldCat Search');
  }

  static function getMenuIcon() {
    $icon="<span class=\"icon-text worldcat-text\">" . _("WorldCat Search") . "</span>";

    return $icon;
  }
}