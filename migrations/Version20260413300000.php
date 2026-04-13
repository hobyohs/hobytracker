<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260413300000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Re-backfill role_group using the updated canonical position taxonomy';
    }

    public function up(Schema $schema): void
    {
        // Recompute role_group from the new authoritative mapping. Rows whose position
        // doesn't match any canonical value are set to NULL (fail closed on Seminar Ops).
        // Keep this in sync with StaffAssignment::POSITION_TO_ROLE_GROUP — this migration
        // is a one-time catch-up for existing data only. Going forward, the entity's
        // PrePersist/PreUpdate lifecycle callback handles all new and modified rows.
        $this->addSql("
            UPDATE staff_assignment
            SET role_group = CASE position
                WHEN 'Senior Facilitator'       THEN 'Senior Facilitator'
                WHEN 'Junior Facilitator'       THEN 'Junior Facilitator'
                WHEN 'J-Crew'                   THEN 'J-Crew'
                WHEN 'Team HQ'                  THEN 'Team HQ'
                WHEN 'Nurse'                    THEN 'Medical'
                WHEN 'Counselor'                THEN 'Medical'
                WHEN 'Leadership Seminar Chair' THEN 'Team HQ'
                WHEN 'Director of Facilitators' THEN 'Team HQ'
                WHEN 'Director of Program'      THEN 'Team HQ'
                WHEN 'Director of Operations'   THEN 'Team HQ'
                WHEN 'Director of Fundraising'  THEN 'Team HQ'
                WHEN 'Director of Media'        THEN 'Team HQ'
                WHEN 'J-Crew Lead'              THEN 'Team HQ'
                WHEN 'Board President'          THEN 'Team HQ'
                WHEN 'Board Vice President'     THEN 'Team HQ'
                WHEN 'Board Secretary'          THEN 'Team HQ'
                WHEN 'Board Treasurer'          THEN 'Team HQ'
                WHEN 'Board Member'             THEN 'Team HQ'
                ELSE NULL
            END
        ");
    }

    public function down(Schema $schema): void
    {
        // No-op: this migration only rewrites data, it doesn't change schema. Rolling
        // back the data would mean restoring the previous mapping's classifications,
        // but since that mapping is itself an earlier migration's concern, we just
        // leave the data as-is on down().
    }
}
