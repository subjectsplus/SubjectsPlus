<?php
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

/**
 * @file HTML5Video
 * @brief A pluslet to display various video formats
 *
 * @author jlittle
 * @date Dec 2013
 * @todo
 */
class Pluslet_HTML5Video extends Pluslet
{

    public function __construct($pluslet_id, $flag = "", $subject_id, $isclone = 0)
    {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "HTML5Video";
        $this->_pluslet_bonus_classes = "type-video";


    }

    static function getMenuIcon()
    {
        $icon = "<i class=\"fa fa-file-video-o\" title=\"" . _("Video") . "\" ></i><span class=\"icon-text\">" . _("Video") . "</span>";
        return $icon;
    }

    protected function onEditOutput()
    {
        // make an editable body and title type


        if ($this->_extra == "") {
            $this->_extra = array();
            $this->_extra['youtube'] = "";
            $this->_extra['vimeo'] = "";
            $this->_extra['mp4'] = "";
            $this->_extra['ogg'] = "";
            $this->_extra['kaltura'] = "";
            $this->_extra['kalturaexternalpartnerid'] = "";
            $this->_extra['kalturaexternaluiconfid'] = "";
            $this->_extra['kalturaexternalentryid'] = "";

        } else {
            $this->_extra = json_decode($this->_extra, true);
            if (is_array($this->_extra) && !key_exists('kalturaexternalpartnerid', $this->_extra)){
                array_push($this->_extra, 'kalturaexternalpartnerid');
                array_push($this->_extra, 'kalturaexternaluiconfid');
                array_push($this->_extra, 'kalturaexternalentryid');
            }

        }
        // Create and output object

        $view = $this->loadHtml(__DIR__ . '/views/HTML5Video.html');

        $this->_body = $view;
    }

    protected function onViewOutput()
    {
        if ($this->_extra != "") {
            $this->_extra = json_decode($this->_extra, true);
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

        if ($this->_extra['youtube'] != "") {
            $lobjSplit = explode('watch?v=', $this->_extra['youtube']);
            $shortFormSplit = explode('youtu.be/', $this->_extra['youtube']);

            if (isset ($lobjSplit[1])) {
                $this->_body .= "<div class='video-container'>" . "<iframe src='//www.youtube.com/embed/" .
                    $lobjSplit[1] . "' frameborder='0' width='560' height='315' webkitallowfullscreen mozallowfullscreen allowfullscreen>></iframe></div>";

            } elseif (isset($shortFormSplit[0])) {

                $this->_body .= "<div class='video-container'>" . "<iframe src='//www.youtube.com/embed/" .
                    $shortFormSplit[1] . "' frameborder='0' width='560' height='315'></iframe></div>";

            } else {

                $this->_body .= "<p class='video-error'>" . _("There was a problem creating the YouTube embed. The URL should look like: http://www.youtube.com/watch?v=abc1234") . "</p>";

            }


        }

        if ($this->_extra['vimeo'] != "") {
            $lobjSplit = explode('/', $this->_extra['vimeo']);

            if (isset ($lobjSplit[3])) {
                $this->_body .= "<div class='video-container'>" . "<iframe src='//player.vimeo.com/video/" .
                    $lobjSplit[3] . "' frameborder='0' width='560' height='315' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>";
            } else {
                $this->_body .= "<p class='video-error'>" . _("There was a problem creating the Vimeo embed. The URL should look like: http://vimeo.com/0137581375135") . "</p>";


            }
        }

        if ($this->_extra['mp4'] != "" || $this->_extra['ogg'] != "") {

            $mp4 = $this->_extra['mp4'];
            $ogg = $this->_extra['ogg'];

            $this->_body .= "<div id='video_markup'><video class='video_display' controls><source class='video_display' src='" . $mp4 . "' type='video/mp4'><source class='video_display' src='" . $ogg . "' type='video/ogg'>" . _("Sorry, your browser doesn't support embedded videos, but don't worry, you can <a href='videofile.ogg'>download it</a> and watch it with your favorite video player!") . "</video></div>";

        }


        try {

            //Case for local SP instance of Kaltura video
            if (!empty($this->_extra['kaltura'])) {
                $kaltura_ref_id = $this->_extra['kaltura'];
                global $kaltura_video_partner_id;
                global $kaltura_video_uiconf_id;

                if (!empty($kaltura_video_partner_id) && !empty($kaltura_video_uiconf_id)) {
                    $this->_body .= "<div class='video-container'><div id='kaltura-player'>" .
                        "<iframe src=\"//cdnapisec.kaltura.com/p/$kaltura_video_partner_id/sp/{$kaltura_video_partner_id}00/embedIframeJs/uiconf_id/$kaltura_video_uiconf_id/partner_id/$kaltura_video_partner_id?iframeembed=true&playerId=kplayer&entry_id={$kaltura_ref_id}&flashvars[streamerType]=auto\""
                        . "width='560' height='315' allowfullscreen webkitallowfullscreen mozAllowFullScreen frameborder=\"0\"></iframe>"
                        . "</div></div>";
                } else {
                    $this->_body .= "<div class='video-container'>Please ask your SubjectsPlus admin to setup your institution's Kaltura Player IDs.<div id='kaltura-player'>";
                }
            }
            //Case for external instance of Kaltura video
            if (isset($this->_extra['kalturaexternalpartnerid'])) {
                if (!empty($this->_extra['kalturaexternalpartnerid']) && !empty($this->_extra['kalturaexternaluiconfid']) && !empty($this->_extra['kalturaexternalentryid'])) {
                    $kaltura_external_partner_id = $this->_extra['kalturaexternalpartnerid'];
                    $kaltura_external_uiconf_id = $this->_extra['kalturaexternaluiconfid'];
                    $kaltura_external_video_id = $this->_extra['kalturaexternalentryid'];

                    $url_string = "https://cdnapisec.kaltura.com/html5/html5lib/v2.75.3/mwEmbedFrame.php/p/$kaltura_external_partner_id/uiconf_id/$kaltura_external_uiconf_id/entry_id/$kaltura_external_video_id?wid=_$kaltura_external_partner_id&iframeembed=true&playerId=img_view_container&entry_id=$kaltura_external_video_id&flashvars[streamerType]=auto";

                    $this->_body .= "<div class='video-container'><div id='kaltura-player'>" .
                        "<iframe src=\"$url_string\""
                        . "width='560' height='315' allowfullscreen webkitallowfullscreen mozAllowFullScreen frameborder=\"0\"></iframe>"
                        . "</div></div>";

                }
            }
        } catch (Exception $e) {

        }

    }


    static function getMenuName()
    {
        return _('Video');
    }


}

?>
