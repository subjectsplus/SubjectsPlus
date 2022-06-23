<?php

namespace App\Service;

use Masterminds\HTML5;

class LazyLoadingService {

    public function addLazyAttribute(string $html) {
        // Load the html for parsing
        $html5 = new HTML5([
            'disable_html_ns' => true,
        ]);

        $doc = $html5->loadHTML($html);
        $xpath = new \DOMXPath($doc);

        // Query for all img tags
        $imgTags = $xpath->query('//img');

        foreach ($imgTags as $imgTag) {
            // add lazy loading attribute to
            $imgTag->setAttribute('loading', 'lazy');
            $imgTag->setAttribute('class', 'img-fluid');

        }

        return $doc->saveHTML();
    }
}