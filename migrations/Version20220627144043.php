<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use App\Service\Migration\LinkListMigrationService;

final class Version20220627144043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate Database Tokens.';
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
        // Convert Basic Database tokens
        $basicTokens = $this->connection->fetchAllAssociative('SELECT pluslet_id, body FROM pluslet WHERE type = "Basic"
        AND body LIKE :body', [
            ':body' => '%{dab%'
        ]);

        foreach ($basicTokens as $basicToken) {
            $plusletId = $basicToken['pluslet_id'];
            $body = $basicToken['body'];

            if ($body) {
                $newBody = LinkListMigrationService::getUpdatedBasicToken($body, [$this, 'getRecord']);

                if ($newBody) {
                    $this->addSql('UPDATE pluslet SET body = :body WHERE pluslet_id = :pluslet_id', [
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
