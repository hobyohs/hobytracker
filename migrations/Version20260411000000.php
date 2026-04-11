<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260411000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add active flag to comings_and_goings for soft-delete support';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comings_and_goings ADD active TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comings_and_goings DROP COLUMN active');
    }
}
