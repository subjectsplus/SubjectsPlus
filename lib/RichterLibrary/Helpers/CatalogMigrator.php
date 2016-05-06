<?php
namespace RichterLibrary\Helpers;

class CatalogMigrator
{

    public function getPrimoHome() {
        return "http://search.library.miami.edu";
    }

    public function getPrimoUrl($param) {
        $param = str_replace("~S11","",$param);
        $param = str_replace("~S12","",$param);
        $param = str_replace("~S13","",$param);

        return  "http://search.library.miami.edu/primo_library/libweb/action/dlSearch.do?&institution=01UOML&vid=uxtest2&query=any,contains,{$param}";
    }
    
    public function removeProxy($url) {
        $noProxyUrl = str_replace("https://iiiprxy.library.miami.edu/login?url=", "",$url);
        return $noProxyUrl;
    }

    public function removeLegacyCatalog($url) {

        $url_type = $this->whatType($url);

        if ($url_type == "search") {
            // Do nothing
        }
        if ($url_type == "record") {
            $url_params = explode("record=", $url);
            if (isset($url_params[1])) {
                $new_url = $this->getPrimoUrl($url_params[1]);
                return $new_url;
            }
        }

        if ($url_type == "default") {
            $new_url = $this->getPrimoHome();
            return $new_url;

        }

        if ($url_type == "mail") {
            // Do nothing
        }

    }



    public function whatType($href)
    {

        if (strrpos($href,'search') !== false) {
            $catalogLinkType = "search";
            return $catalogLinkType;

        }

        if (strpos($href,'record=') !== false) {
            $catalogLinkType = "record";
            return $catalogLinkType;

        }

        if ($href == 'http://ibisweb.miami.edu/search/X') {
            $catalogLinkType = "default";
            return $catalogLinkType;

        }

        if ($href == 'http://catalog.library.miami.edu/search/X') {
            $catalogLinkType = "default";
            return $catalogLinkType;

        }

        if (strpos($href,"mailto") !== false) {
            $catalogLinkType = "mail";
            return $catalogLinkType;

        }

        if (strpos($href,"http://libguides.miami.edu/") !== false) {
            $catalogLinkType = "libguides";
            return $catalogLinkType;

        }

        if (strpos($href,"content.php") !== false) {
            $catalogLinkType = "libguides";
            return $catalogLinkType;

        }

        if (strpos($href,".jpg") !== false) {
            $catalogLinkType = "image";
            return $catalogLinkType;

        }

        if (strpos($href,".gif") !== false) {
            $catalogLinkType = "image";
            return $catalogLinkType;

        }
    }
}