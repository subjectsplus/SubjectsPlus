<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ThirdPartyTags extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('google_analytics', [$this, 'googleAnalytics']),
        ];
    }

    public function googleAnalytics()
    {
        global $google_analytics_ua;
        global $google_tag_manager;
        if ((isset($google_analytics_ua)) && (( !empty($google_analytics_ua)))) {
            echo "<div id='google-analytics-ua' style='visibility: hidden;'" .
                 " data-uacode='{$google_analytics_ua}'></div>" .
                 "<div id='google_tag_manager' style='visibility: hidden;'" .
                 " data-tag-manager='{$google_tag_manager}'></div>";
    
            if (file_exists(dirname(__FILE__).'../../subjects/includes/google-analytics-tag-manager.php')) {
                include_once(dirname(__FILE__).'../../subjects/includes/google-analytics-tag-manager.php');
            }
        }
    }
}
