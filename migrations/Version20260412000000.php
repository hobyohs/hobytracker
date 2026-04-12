<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260412000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create ambassador_evaluation and staff_evaluation tables; backfill from legacy eval columns';
    }

    public function up(Schema $schema): void
    {
        // ── ambassador_evaluation ─────────────────────────────────────────────
        $this->addSql('
            CREATE TABLE ambassador_evaluation (
                id              INT AUTO_INCREMENT NOT NULL,
                ambassador_id   INT NOT NULL,
                submitted_by_id INT DEFAULT NULL,
                seminar_year    SMALLINT NOT NULL,
                status          VARCHAR(20) NOT NULL DEFAULT \'draft\',
                submitted_at    DATETIME DEFAULT NULL,
                eval_engaged        VARCHAR(5) DEFAULT NULL,
                eval_service        VARCHAR(5) DEFAULT NULL,
                eval_recommendation VARCHAR(5) DEFAULT NULL,
                eval_pros       LONGTEXT DEFAULT NULL,
                eval_cons       LONGTEXT DEFAULT NULL,
                eval_comments   LONGTEXT DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY amb_eval_year_unique (ambassador_id, seminar_year),
                KEY idx_amb_eval_ambassador (ambassador_id),
                CONSTRAINT fk_amb_eval_ambassador FOREIGN KEY (ambassador_id) REFERENCES ambassador (id),
                CONSTRAINT fk_amb_eval_submitted_by FOREIGN KEY (submitted_by_id) REFERENCES staff_assignment (id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');

        // ── staff_evaluation ──────────────────────────────────────────────────
        $this->addSql('
            CREATE TABLE staff_evaluation (
                id             INT AUTO_INCREMENT NOT NULL,
                subject_id     INT NOT NULL,
                evaluator_id   INT DEFAULT NULL,
                seminar_year   SMALLINT NOT NULL,
                status         VARCHAR(20) NOT NULL DEFAULT \'draft\',
                submitted_at   DATETIME DEFAULT NULL,
                eval_discussions   VARCHAR(5) DEFAULT NULL,
                eval_enthusiastic  VARCHAR(5) DEFAULT NULL,
                eval_organized     VARCHAR(5) DEFAULT NULL,
                eval_equally       VARCHAR(5) DEFAULT NULL,
                eval_responsible   VARCHAR(5) DEFAULT NULL,
                eval_attentive     VARCHAR(5) DEFAULT NULL,
                eval_include       VARCHAR(5) DEFAULT NULL,
                eval_professional  VARCHAR(5) DEFAULT NULL,
                eval_punctual      VARCHAR(5) DEFAULT NULL,
                eval_pros      LONGTEXT DEFAULT NULL,
                eval_cons      LONGTEXT DEFAULT NULL,
                eval_whynot    LONGTEXT DEFAULT NULL,
                eval_comments  LONGTEXT DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY staff_eval_unique (subject_id, evaluator_id, seminar_year),
                KEY idx_staff_eval_subject (subject_id),
                KEY idx_staff_eval_evaluator (evaluator_id),
                CONSTRAINT fk_staff_eval_subject FOREIGN KEY (subject_id) REFERENCES staff_assignment (id),
                CONSTRAINT fk_staff_eval_evaluator FOREIGN KEY (evaluator_id) REFERENCES staff_assignment (id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');

        // ── Backfill: ambassador_evaluation ───────────────────────────────────
        // Only backfill rows where at least one eval field is populated and seminar_year is set.
        // submitted_by is left NULL (unknown for legacy data).
        // status is set to 'submitted' since these were previously the final state.
        $this->addSql("
            INSERT INTO ambassador_evaluation
                (ambassador_id, seminar_year, status, eval_engaged, eval_service, eval_recommendation, eval_pros, eval_cons, eval_comments)
            SELECT
                id,
                seminar_year,
                'submitted',
                eval_engaged,
                eval_service,
                eval_recommendation,
                eval_pros,
                eval_cons,
                eval_comments
            FROM ambassador
            WHERE seminar_year IS NOT NULL
              AND (
                eval_engaged        IS NOT NULL OR
                eval_service        IS NOT NULL OR
                eval_recommendation IS NOT NULL OR
                eval_pros           IS NOT NULL OR
                eval_cons           IS NOT NULL OR
                eval_comments       IS NOT NULL
              )
        ");

        // ── Backfill: staff_evaluation ────────────────────────────────────────
        // evaluator_id left NULL for legacy records.
        // eval_status = 1 in old schema means submitted.
        $this->addSql("
            INSERT INTO staff_evaluation
                (subject_id, evaluator_id, seminar_year, status, eval_discussions, eval_enthusiastic, eval_organized,
                 eval_equally, eval_responsible, eval_attentive, eval_include, eval_professional, eval_punctual,
                 eval_pros, eval_cons, eval_whynot, eval_comments)
            SELECT
                id,
                NULL,
                seminar_year,
                CASE WHEN eval_status = 1 THEN 'submitted' ELSE 'draft' END,
                eval_discussions,
                eval_enthusiastic,
                eval_organized,
                eval_equally,
                eval_responsible,
                eval_attentive,
                eval_include,
                eval_professional,
                eval_punctual,
                eval_pros,
                eval_cons,
                eval_whynot,
                eval_comments
            FROM staff_assignment
            WHERE seminar_year IS NOT NULL
              AND (
                eval_discussions   IS NOT NULL OR
                eval_enthusiastic  IS NOT NULL OR
                eval_organized     IS NOT NULL OR
                eval_equally       IS NOT NULL OR
                eval_responsible   IS NOT NULL OR
                eval_attentive     IS NOT NULL OR
                eval_include       IS NOT NULL OR
                eval_professional  IS NOT NULL OR
                eval_punctual      IS NOT NULL OR
                eval_pros          IS NOT NULL OR
                eval_cons          IS NOT NULL OR
                eval_whynot        IS NOT NULL OR
                eval_comments      IS NOT NULL
              )
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS staff_evaluation');
        $this->addSql('DROP TABLE IF EXISTS ambassador_evaluation');
    }
}
