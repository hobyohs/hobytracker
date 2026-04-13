<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260413200000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add role_group column to staff_assignment and backfill from known position strings';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE staff_assignment ADD role_group VARCHAR(30) DEFAULT NULL');

        // Backfill from the known position string mapping. Keep this in sync with
        // StaffAssignment::POSITION_TO_ROLE_GROUP — any new mapping added to the
        // constant should be handled going forward by the PrePersist/PreUpdate
        // lifecycle callback, so this backfill is a one-time catch-up for existing
        // rows only.
        $this->addSql("
            UPDATE staff_assignment
            SET role_group = CASE position
                WHEN 'Senior Facilitator'                 THEN 'Senior Facilitator'
                WHEN 'Junior Facilitator'                 THEN 'Junior Facilitator'
                WHEN 'J-Crew'                             THEN 'J-Crew'
                WHEN 'DOF'                                THEN 'Team HQ'
                WHEN 'Director of Facilitators'           THEN 'Team HQ'
                WHEN 'ADOF'                               THEN 'Team HQ'
                WHEN 'Assistant Director of Facilitators' THEN 'Team HQ'
                WHEN 'Program Director'                   THEN 'Team HQ'
                WHEN 'Seminar Chair'                      THEN 'Team HQ'
                WHEN 'Board Member'                       THEN 'Team HQ'
                WHEN 'Nurse'                              THEN 'Medical'
                WHEN 'EMT'                                THEN 'Medical'
                ELSE NULL
            END
            WHERE role_group IS NULL
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE staff_assignment DROP COLUMN role_group');
    }
}
