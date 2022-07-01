<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use App\Service\Migration\LinkListMigrationService;

final class Version20220621134441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate LinkList pluslet of type "LinkList".';
    }

    public function getRecord(string $recordId)
    {
        return $this->connection->fetchAssociative(
            'SELECT title.title_id AS title_id, title, description, location FROM title, location
            WHERE title.title_id = :title_id AND location.title_id = title.title_id AND format = 1', 
            [
                ':title_id' => $recordId
            ]
        );
    }

    public function up(Schema $schema): void
    {
        // Convert LinkList Pluslet type
        $linkLists = $this->connection->fetchAllAssociative('SELECT pluslet_id, body FROM pluslet WHERE type = "LinkList" AND 
        (body LIKE :condition1 OR body LIKE :condition2 OR body LIKE :condition3)', [
            ':condition1' => '%<ul class="link-list-display">%',
            ':condition2' => "%<ul class='link-list-display'>%",
            ':condition3' => '%<li>{{dab}%'
        ]);

        foreach ($linkLists as $linkList) {
            $plusletId = $linkList['pluslet_id'];
            $body = $linkList['body'];

            if ($body) {
                $newBody = LinkListMigrationService::getUpdatedLinkListDisplay($body, [$this, 'getRecord']);

                if ($newBody) {
                    $this->addSql('UPDATE pluslet SET body = :body, type = "Basic" WHERE pluslet_id = :pluslet_id', [
                        ':body' => $newBody,
                        ':pluslet_id' => $plusletId
                    ]);
                }
            }
        }
    }

    public function down(Schema $schema): void
    {
    }
}
