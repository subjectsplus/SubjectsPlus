<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Masterminds\HTML5;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621134441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate LinkList pluslet.';
    }

    private function isLinkListDisplay(string $html): bool {
        return str_contains($html, '<ul class="link-list-display">') ||
            str_contains($html, "<ul class='link-list-display'>");
    }

    private function cleanString(string $str): string {
        return htmlspecialchars(trim(strip_tags($str)));
    }

    private function getRecord(string $recordId) {
        return $this->connection->fetchAssociative(
            'SELECT title.title_id AS title_id, title, description, location FROM title, location
            WHERE title.title_id = :title_id AND location.title_id = title.title_id AND format = 1', 
            [
                ':title_id' => $recordId
            ]
        );
    }

    private function getUpdatedLinkListDisplay(string $html) {
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
            echo 'tokenConfig: ' . implode(',', $tokenConfig) . "\n";
            $recordId = isset($tokenConfig[1]) ? $tokenConfig[1] : null;
            
            if ($recordId) {
                echo 'recordId: ' . $recordId . "\n";
                $extraConfig = str_split($tokenConfig[3]);
                echo 'extraConfig: ' . implode($extraConfig) . "\n";
                $showIcon = isset($extraConfig[0]) ? $extraConfig[0] : false;
                $showDescription = isset($extraConfig[1]) ? $extraConfig[1] : false;
                $showNotes = isset($extraConfig[2]) ? $extraConfig[2] : false;

                // Get most up to date record data
                $recordData = $this->getRecord($recordId);

                if ($recordData) {
                    echo 'recordData: ' . implode(',', $recordData) . "\n";
                    // Create the token span element
                    $tokenElement = $doc->createElement('span');
                    $tokenElement->setAttribute('class', 'record-token');
                    $tokenElement->setAttribute('data-record-id', $recordId);
                    $tokenElement->setAttribute('data-description-type', 'none');

                    if ($showIcon) $tokenElement->setAttribute('data-show-icon', 'true');
                    if ($showNotes) $tokenElement->setAttribute('data-show-notes', 'true');

                    // Create and append the token link element
                    $recordLink = $doc->createElement('a');
                    $recordLink->nodeValue = $this->cleanString($recordData['title']);
                    $recordLink->setAttribute('class', 'record-link');
                    $recordLink->setAttribute('href', $recordData['location']);
                    $tokenElement->appendChild($recordLink);
                    
                    // Create and append the token description element if applicable
                    if ($showDescription) {
                        $tokenElement->setAttribute('data-description-type', 'block');
                        
                        $descriptionElement = $doc->createElement('span');
                        $descriptionElement->nodeValue = $this->cleanString($recordData['description']);
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

    public function up(Schema $schema): void
    {
        $linkLists = $this->connection->fetchAllAssociative('SELECT pluslet_id, body FROM pluslet WHERE type = "LinkList"');

        foreach ($linkLists as $linkList) {
            $plusletId = $linkList['pluslet_id'];
            $body = $linkList['body'];

            if ($body) {
                if ($this->isLinkListDisplay($body)) {
                    echo "-------------------------------\n";
                    echo 'Pluslet ' . $plusletId . "\n";
                    echo "-------------------------------\n";
                    $newBody = $this->getUpdatedLinkListDisplay($body);
                    if ($newBody) {
                        $this->addSql('UPDATE pluslet SET body = :body, type = "Basic" WHERE pluslet_id = :pluslet_id', [
                            ':body' => $newBody,
                            ':pluslet_id' => $plusletId
                        ]);
                    }
                }
            }
        }
    }

    public function down(Schema $schema): void
    {
    }
}
