<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use App\Service\Migration\SubjectSpecialistMigrationService;


final class Version20220713194005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate Subject Specialist pluslets to new format.';
    }

    public function up(Schema $schema): void
    {
        $pluslets = $this->connection->fetchAllAssociative('SELECT pluslet_id, extra FROM pluslet WHERE type = "SubjectSpecialist"');
        
        foreach ($pluslets as $pluslet) {
            $plusletId = $pluslet['pluslet_id'];
            $extra = $pluslet['extra'];

            if ($extra) {
                $newExtra = SubjectSpecialistMigrationService::getUpdatedExtraField($extra);
                
                if ($newExtra) {
                    $this->addSql('UPDATE pluslet SET extra = :extra WHERE pluslet_id = :pluslet_id', [
                        ':extra' => $newExtra,
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
