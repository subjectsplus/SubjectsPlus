<?php

namespace App\Service\Migration;

use Masterminds\HTML5;
use App\Service\UtilityService;

class LinkListMigrationService {
    public static function isLinkListDisplay(string $html): bool {
        return str_contains($html, '<ul class="link-list-display">') ||
            str_contains($html, "<ul class='link-list-display'>") ||
            str_contains($html, '<li>{{dab}');
    }

    public static function getUpdatedLinkListDisplay(string $html, $get_record_function) {
        // Load the html for parsing
        $html5 = new HTML5([
            'disable_html_ns' => true,
        ]);

        $doc = $html5->loadHTML($html);
        $xpath = new \DOMXPath($doc);

        // Query for all li tags
        $oldListItems = $xpath->query('//li');

        foreach ($oldListItems as $oldListItem) {
            // Token configuration with format {{dab},{title_id},{title},{extraConfig}}
            $tokenString = trim($oldListItem->nodeValue, '{}');
            $tokenConfig = preg_split('/},\s?{/', $tokenString);
            $recordId = isset($tokenConfig[1]) ? $tokenConfig[1] : null;
            
            if ($recordId) {
                $extraConfig = str_split($tokenConfig[3]);
                $showIcon = isset($extraConfig[0]) ? $extraConfig[0] : false;
                $showDescription = isset($extraConfig[1]) ? $extraConfig[1] : false;
                $showNotes = isset($extraConfig[2]) ? $extraConfig[2] : false;

                // Get most up to date record data
                $recordData = $get_record_function($recordId);

                if ($recordData) {
                    // Create the token span element
                    $tokenElement = $doc->createElement('span');
                    $tokenElement->setAttribute('class', 'record-token');
                    $tokenElement->setAttribute('data-record-id', $recordId);
                    $tokenElement->setAttribute('data-description-type', 'none');

                    if ($showIcon) $tokenElement->setAttribute('data-show-icon', 'true');
                    if ($showNotes) $tokenElement->setAttribute('data-show-notes', 'true');

                    // Create and append the token link element
                    $recordLink = $doc->createElement('a');
                    $recordLink->nodeValue = UtilityService::cleanString($recordData['title']);
                    $recordLink->setAttribute('class', 'record-link');
                    $recordLink->setAttribute('href', $recordData['location']);
                    $tokenElement->appendChild($recordLink);
                    
                    // Create and append the token description element if applicable
                    if ($showDescription) {
                        $tokenElement->setAttribute('data-description-type', 'block');
                        
                        $descriptionElement = $doc->createElement('span');
                        $descriptionElement->nodeValue = UtilityService::cleanString($recordData['description']);
                        $descriptionElement->setAttribute('class', 'record-description');
                        
                        $brElement = $doc->createElement('br');
                        
                        $tokenElement->appendChild($brElement);
                        $tokenElement->appendChild($descriptionElement);
                    }

                    // Create a list item element and append token span element
                    $newListItem = $doc->createElement('li');
                    $newListItem->appendChild($tokenElement);

                    // Replace old list item element with new list item element
                    $oldListItem->parentNode->replaceChild($newListItem, $oldListItem);
                } else {
                    // Remove the nonexistant record
                    $oldListItem->parentNode->removeChild($oldListItem);
                }
            } else {
                // Remove the nonexistant record
                $oldListItem->parentNode->removeChild($oldListItem);
            }
        }

        return $doc->saveHTML();
    }
}