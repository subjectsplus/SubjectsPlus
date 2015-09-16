<?php
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

/**
 * @file SocialMedia
 * @brief A pluslet to display Social Media accounts
 *
 * @author cbrownroberts
 * @date Aug 2015
 *
 */


class Pluslet_SocialMedia extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "SocialMedia";

    }

    static function getMenuIcon()
    {
        $icon="<i class=\"fa fa-share-alt-square\" title=\"" . _("Social Media") . "\" ></i><span class=\"icon-text\">" . _("Social Media") . "</span>";
        return $icon;
    }


    protected function onEditOutput()
    {
        $objSM = new SocialMedia();
        $smAccounts = $objSM->toArray();

        // make an editable body and title type
        if($this->_extra == "")
        {
            foreach($smAccounts as $account):
                $accountName = strtolower($account['name']);
                $this->_extra[$accountName] = "";
            endforeach;

        }else
        {
            $this->_extra = json_decode( $this->_extra, true );
        }

        //pass view SocialMedia() data
        $this->smAccounts = $smAccounts;

        // Create and output object
        $view = $this->loadHtml(__DIR__ . '/views/SocialMedia.php' );

        $this->_body = $view;
    }


    protected function onViewOutput()
    {
        if ($this->_extra != "") {
            $this->_extra = json_decode($this->_extra, true);
        }

        $this->_body = "";

        $this->_body .= "<div id='social_media_accounts'>";

        $this->_body .= "<ul>";

        if( $this->_extra['facebook'] != "" )
        {
            $this->_body .= '<li><a href="http://facebook.com/'.$this->_extra['facebook'].'"><i class="fa fa-facebook-square"></i></a></li>';
        }

        if( $this->_extra['twitter'] != "" )
        {
            $this->_body .= '<li><a href="http://twitter.com/'.$this->_extra['twitter'].'"><i class="fa fa-twitter-square"></i></a></li>';
        }

        if( $this->_extra['pinterest'] != "" )
        {
            $this->_body .= '<li><a href="http://pinterest.com/'.$this->_extra['pinterest'].'"><i class="fa fa-pinterest-square"></i></a></li>';
        }

        if( $this->_extra['instagram'] != "" )
        {
            $this->_body .= '<li><a href="http://instagram.com/'.$this->_extra['instagram'].'"><i class="fa fa-instagram"></i></a></li>';
        }

        $this->_body .= "</ul>";
        $this->_body .= "</div>";

    }


    static function getMenuName()
    {
        return _('SocialMedia');
    }
}