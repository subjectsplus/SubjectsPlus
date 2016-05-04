<?php
namespace RichterLibrary\Helpers;

class CatalogMigrator
{
    public function removeProxy($url) {
        $noProxyUrl = str_replace("https://iiiprxy.library.miami.edu/login?url=", "",$url);
        return $noProxyUrl;
    }

    public function removeLegacyCatalog($url,$title) {
        $url_params = explode("record=", $url);
        if (isset($url_params[1])) {
            $new_url = "http://search.library.miami.edu/primo_library/libweb/action/dlSearch.do?&institution=01UOML&vid=uxtest2&query=any,contains,{$url_params[1]}";
        } else {
            $new_url = "http://search.library.miami.edu/primo_library/libweb/action/dlSearch.do?&institution=01UOML&vid=uxtest2&query=any,contains,{$title}";

        }
        return $new_url;
    }

    public function isCatalogLink($href) {
        $isCatalogLink = false;

        if (strpos($href,'http://ibisweb.miami.edu') !== false) {
            $isCatalogLink = true;
        }

        if (strpos($href,'http://catalog.library.miami.edu') !== false) {
            $isCatalogLink = true;
        }

        return $isCatalogLink;

    }
}