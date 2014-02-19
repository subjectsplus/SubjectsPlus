<?php
namespace SubjectsPlus\Control;
require_once("Pluslet.php");
/**
 *   @file sp_Pluslet_HTML5Video
 *   @brief
 *
 *   @author agdarby, jlittle
 *   @date Dec 2013
 *   @todo
 */

class Pluslet_HTML5Video extends Pluslet {

  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  }

  protected function onEditOutput()
  {
  	// make an editable body and title type

  	if($this->_extra == "")
  	{
  		$this->_extra = [];
  		$this->_extra['youtube'] = "";
  		$this->_extra['vimeo'] = "";
  		$this->_extra['mp4'] = "";
  		$this->_extra['ogg'] = "";
  	}

  	// Create and output object

  	ob_start();
  	include __DIR__ . '/views/test.html';
  	$view = ob_get_clean();

  	$this->_body = $view;
  }

  protected function onViewOutput()
  {
	if ($this->_extra != "")
	{
		$this->_extra = json_decode( $this->_extra, true );
	}

  	$this->_body = " <style>
        .html5_video input {

            display:  block;
        }
    #gen_video_tag {
        margin-top:5px;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        padding-top: 30px; height: 0; overflow: hidden;
    }

    .video-container iframe,
    .video-container object,
    .video-container embed {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }


        </style>";

  	if( $this->_extra['youtube'] != "" )
  	{
  		$lobjSplit = explode('watch?v=', $this->_extra['youtube']);

  		$this->_body .= "<div class='video-container'>" . "<iframe src='http://www.youtube.com/embed/" .
  			$lobjSplit[1] .  "' frameborder='0' width='560' height='315'></iframe></div>";
  	}
  }

  static function getMenuName()
  {
  	return _('Video');
  }
}

?>
