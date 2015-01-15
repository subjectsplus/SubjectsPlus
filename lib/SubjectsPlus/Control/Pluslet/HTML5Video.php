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

     $this->_type = "HTML5Video";


  }

  protected function onEditOutput()
  {
  	// make an editable body and title type

  	if($this->_extra == "")
  	{
  		$this->_extra = array();
  		$this->_extra['youtube'] = "";
  		$this->_extra['vimeo'] = "";
  		$this->_extra['mp4'] = "";
  		$this->_extra['ogg'] = "";
  	}else
  	{
  		$this->_extra = json_decode( $this->_extra, true );
  	}

  	// Create and output object

  	ob_start();
  	include __DIR__ . '/views/HTML5Video.html';
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

        if (isset ( $lobjSplit[1])) {
  		$this->_body .= "<div class='video-container'>" . "<iframe src='//www.youtube.com/embed/" .
  			$lobjSplit[1] .  "' frameborder='0' width='560' height='315'></iframe></div>";
        } else {
            $this->_body .= "<p class='video-error'>There was a problem creating the YouTube embed. The URL should look like: http://www.youtube.com/watch?v=abc1234 </p>";

        }
  	}

    if( $this->_extra['vimeo'] != "" )
  	{
  		$lobjSplit = explode('/', $this->_extra['vimeo']);

        if (isset ( $lobjSplit[3])) {
  		$this->_body .= "<div class='video-container'>" . "<iframe src='//player.vimeo.com/video/" .
        $lobjSplit[3] .  "' frameborder='0' width='560' height='315'></iframe></div>";
        } else {
            $this->_body .= "<p class='video-error'>There was a problem creating the Vimeo embed. The URL should look like: http://vimeo.com/0137581375135 </p>";


        }
  	}

    if( $this->_extra['mp4'] != "" AND $this->_extra['ogg'] != "" ) {

        $mp4 = $this->_extra['mp4'];
        $ogg = $this->_extra['ogg'];

        $this->_body .= "<div id='video_markup'><video class='video_display' controls><source class='video_display' src='" . $mp4 . "' type='video/mp4'><source class='video_display' src='" . $ogg . "' type='video/ogg'>Sorry, your browser doesn't support embedded videos, but don't worry, you can <a href='videofile.ogg'>download it</a> and watch it with your favorite video player! </video></div>";

    }


}

  static function getMenuName()
  {
  	return _('Video');
  }


}

?>
