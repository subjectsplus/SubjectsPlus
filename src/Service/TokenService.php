<?php

namespace App\Service;

use Masterminds\HTML5;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Title;

class TokenService {
    private const DESCRIPTION_BLOCK_CLASS_NAME = 'record-description';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function updateTokens(string $html) {
        // Load the html for parsing
        $html5 = new HTML5([
            'disable_html_ns' => true,
        ]);

        $doc = $html5->loadHTML($html);
        $xpath = new \DOMXPath($doc);

        // Query for all span tags with data-record-id attribute
        $titleIds = $xpath->query('//span/@data-record-id');
        
        if ($titleIds && $titleIds->count() > 0) {
            /** @var \App\Repository\TitleRepository $titleRepo */
            $titleRepo = $this->entityManager->getRepository(Title::class);

            foreach ($titleIds as $titleId) {
                // span element for record token
                $recordElement = $titleId->parentNode;

                // get up to date record token info
                /** @var Title $recordInfo */
                $recordInfo = $titleRepo->findOneBy(['titleId' => $titleId->value]);

                foreach ($recordElement->childNodes as $childNode) {
                    // TODO: caching and updating on the database
                    
                    // check for link tags
                    if (strtolower($childNode->nodeName) === 'a') {
                        // update the link to the database value's location field
                        $childNode->setAttribute('href', $recordInfo->getLocation()[0]->getLocation());

                        // update title to database value if no override is present
                        if (!$recordElement->hasAttribute('data-record-title-override')) {
                            $childNode->nodeValue = UtilityService::cleanString($recordInfo->getTitle());
                        }
                    }

                    // check for description blocks
                    if (strtolower($childNode->nodeName) === 'span' && $childNode->getAttribute('class') === self::DESCRIPTION_BLOCK_CLASS_NAME) {
                        $childNode->nodeValue = UtilityService::cleanString($recordInfo->getDescription());
                    }
                }
            }

            return $doc->saveHTML();
        }

        return $html;
    }
}