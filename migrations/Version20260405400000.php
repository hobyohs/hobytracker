<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260405400000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create staff_assignment table and copy existing user data into it.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE staff_assignment (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            seminar_year SMALLINT NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT \'active\',
            photo VARCHAR(255) DEFAULT NULL,
            position VARCHAR(50) DEFAULT NULL,
            shirt_size VARCHAR(10) DEFAULT NULL,
            age INT DEFAULT NULL,
            letter_group_id INT DEFAULT NULL,
            dorm_room_id INT DEFAULT NULL,
            ec_first_name VARCHAR(100) DEFAULT NULL,
            ec_last_name VARCHAR(100) DEFAULT NULL,
            ec_relationship VARCHAR(100) DEFAULT NULL,
            ec_phone1 VARCHAR(20) DEFAULT NULL,
            ec_phone2 VARCHAR(20) DEFAULT NULL,
            diet_info LONGTEXT DEFAULT NULL,
            diet_restrictions LONGTEXT DEFAULT NULL,
            diet_severity VARCHAR(100) DEFAULT NULL,
            current_rx LONGTEXT DEFAULT NULL,
            current_conditions LONGTEXT DEFAULT NULL,
            exercise_limits LONGTEXT DEFAULT NULL,
            allergies LONGTEXT DEFAULT NULL,
            med_allergies LONGTEXT DEFAULT NULL,
            paperwork_complete TINYINT(1) DEFAULT NULL,
            hoby_app_complete TINYINT(1) DEFAULT NULL,
            hours_complete TINYINT(1) DEFAULT NULL,
            amb_registered TINYINT(1) DEFAULT NULL,
            fundraising_complete TINYINT(1) DEFAULT NULL,
            bg_check_submitted TINYINT(1) DEFAULT NULL,
            bg_check_complete TINYINT(1) DEFAULT NULL,
            requirement_notes LONGTEXT DEFAULT NULL,
            psms_uploaded_on VARCHAR(100) DEFAULT NULL,
            eval_pros LONGTEXT DEFAULT NULL,
            eval_cons LONGTEXT DEFAULT NULL,
            eval_discussions VARCHAR(5) DEFAULT NULL,
            eval_enthusiastic VARCHAR(5) DEFAULT NULL,
            eval_organized VARCHAR(5) DEFAULT NULL,
            eval_equally VARCHAR(5) DEFAULT NULL,
            eval_responsible VARCHAR(5) DEFAULT NULL,
            eval_attentive VARCHAR(5) DEFAULT NULL,
            eval_include VARCHAR(5) DEFAULT NULL,
            eval_professional VARCHAR(5) DEFAULT NULL,
            eval_punctual VARCHAR(5) DEFAULT NULL,
            eval_whynot LONGTEXT DEFAULT NULL,
            eval_comments LONGTEXT DEFAULT NULL,
            eval_status TINYINT(1) NOT NULL DEFAULT 0,
            assignment_check_in VARCHAR(100) DEFAULT NULL,
            assignment_closing_ceremonies VARCHAR(100) DEFAULT NULL,
            assignment_check_out VARCHAR(100) DEFAULT NULL,
            assignment_check_in_notes LONGTEXT DEFAULT NULL,
            assignment_closing_ceremonies_notes LONGTEXT DEFAULT NULL,
            assignment_check_out_notes LONGTEXT DEFAULT NULL,
            INDEX IDX_SA_USER (user_id),
            INDEX IDX_SA_YEAR (seminar_year),
            INDEX IDX_SA_LETTER_GROUP (letter_group_id),
            INDEX IDX_SA_DORM_ROOM (dorm_room_id),
            UNIQUE INDEX user_year_unique (user_id, seminar_year),
            PRIMARY KEY(id),
            CONSTRAINT FK_SA_USER FOREIGN KEY (user_id) REFERENCES user (id),
            CONSTRAINT FK_SA_LETTER_GROUP FOREIGN KEY (letter_group_id) REFERENCES letter_group (id),
            CONSTRAINT FK_SA_DORM_ROOM FOREIGN KEY (dorm_room_id) REFERENCES dorm_room (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Copy existing user data into staff_assignment with seminar_year 2026
        $this->addSql('INSERT INTO staff_assignment (
            user_id, seminar_year, status,
            photo, position, shirt_size, age,
            letter_group_id, dorm_room_id,
            ec_first_name, ec_last_name, ec_relationship, ec_phone1, ec_phone2,
            diet_info, diet_restrictions, diet_severity,
            current_rx, current_conditions, exercise_limits, allergies, med_allergies,
            paperwork_complete, hoby_app_complete, hours_complete, amb_registered, fundraising_complete,
            bg_check_submitted, bg_check_complete, requirement_notes, psms_uploaded_on,
            eval_pros, eval_cons, eval_discussions, eval_enthusiastic, eval_organized,
            eval_equally, eval_responsible, eval_attentive, eval_include, eval_professional,
            eval_punctual, eval_whynot, eval_comments, eval_status,
            assignment_check_in, assignment_closing_ceremonies, assignment_check_out,
            assignment_check_in_notes, assignment_closing_ceremonies_notes, assignment_check_out_notes
        ) SELECT
            id, 2026, \'active\',
            photo, position, shirt_size, age,
            letter_group_id, dorm_room_id,
            ec_first_name, ec_last_name, ec_relationship, ec_phone1, ec_phone2,
            diet_info, diet_restrictions, diet_severity,
            current_rx, current_conditions, exercise_limits, allergies, med_allergies,
            paperwork_complete, hoby_app_complete, hours_complete, amb_registered, fundraising_complete,
            bg_check_submitted, bg_check_complete, requirement_notes, psms_uploaded_on,
            eval_pros, eval_cons, eval_discussions, eval_enthusiastic, eval_organized,
            eval_equally, eval_responsible, eval_attentive, eval_include, eval_professional,
            eval_punctual, eval_whynot, eval_comments, eval_status,
            assignment_check_in, assignment_closing_ceremonies, assignment_check_out,
            assignment_check_in_notes, assignment_closing_ceremonies_notes, assignment_check_out_notes
        FROM user');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE staff_assignment');
    }
}
