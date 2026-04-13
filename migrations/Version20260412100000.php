<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260412100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop legacy eval columns from ambassador and staff_assignment (data already in ambassador_evaluation / staff_evaluation)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ambassador
            DROP COLUMN eval_engaged,
            DROP COLUMN eval_service,
            DROP COLUMN eval_pros,
            DROP COLUMN eval_cons,
            DROP COLUMN eval_recommendation,
            DROP COLUMN eval_comments
        ');

        $this->addSql('ALTER TABLE staff_assignment
            DROP COLUMN eval_pros,
            DROP COLUMN eval_cons,
            DROP COLUMN eval_discussions,
            DROP COLUMN eval_enthusiastic,
            DROP COLUMN eval_organized,
            DROP COLUMN eval_equally,
            DROP COLUMN eval_responsible,
            DROP COLUMN eval_attentive,
            DROP COLUMN eval_include,
            DROP COLUMN eval_professional,
            DROP COLUMN eval_punctual,
            DROP COLUMN eval_whynot,
            DROP COLUMN eval_comments,
            DROP COLUMN eval_status
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE ambassador
            ADD COLUMN eval_engaged VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_service VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_pros LONGTEXT DEFAULT NULL,
            ADD COLUMN eval_cons LONGTEXT DEFAULT NULL,
            ADD COLUMN eval_recommendation VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_comments LONGTEXT DEFAULT NULL
        ");

        $this->addSql("ALTER TABLE staff_assignment
            ADD COLUMN eval_pros LONGTEXT DEFAULT NULL,
            ADD COLUMN eval_cons LONGTEXT DEFAULT NULL,
            ADD COLUMN eval_discussions VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_enthusiastic VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_organized VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_equally VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_responsible VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_attentive VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_include VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_professional VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_punctual VARCHAR(5) DEFAULT NULL,
            ADD COLUMN eval_whynot LONGTEXT DEFAULT NULL,
            ADD COLUMN eval_comments LONGTEXT DEFAULT NULL,
            ADD COLUMN eval_status TINYINT(1) NOT NULL DEFAULT 0
        ");
    }
}
