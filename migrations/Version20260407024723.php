<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260407024723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_ambassador_year ON ambassador');
        $this->addSql('ALTER TABLE ambassador CHANGE seminar_year seminar_year SMALLINT NOT NULL, CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('DROP INDEX IDX_applicant_year ON applicant');
        $this->addSql('ALTER TABLE applicant CHANGE seminar_year seminar_year SMALLINT NOT NULL');
        $this->addSql('DROP INDEX IDX_comings_and_goings_year ON comings_and_goings');
        $this->addSql('ALTER TABLE comings_and_goings CHANGE seminar_year seminar_year SMALLINT NOT NULL');
        $this->addSql('DROP INDEX IDX_letter_group_year ON letter_group');
        $this->addSql('ALTER TABLE letter_group CHANGE seminar_year seminar_year SMALLINT NOT NULL');
        $this->addSql('DROP INDEX IDX_SA_YEAR ON staff_assignment');
        $this->addSql('ALTER TABLE staff_assignment CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE staff_assignment RENAME INDEX idx_sa_user TO IDX_D697546BA76ED395');
        $this->addSql('ALTER TABLE staff_assignment RENAME INDEX idx_sa_letter_group TO IDX_D697546B60C5456B');
        $this->addSql('ALTER TABLE staff_assignment RENAME INDEX idx_sa_dorm_room TO IDX_D697546B89408E37');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64960C5456B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64989408E37');
        $this->addSql('DROP INDEX IDX_8D93D64960C5456B ON user');
        $this->addSql('DROP INDEX IDX_8D93D64989408E37 ON user');
        $this->addSql('ALTER TABLE user DROP letter_group_id, DROP dorm_room_id, DROP photo, DROP shirt_size, DROP position, DROP ec_first_name, DROP ec_last_name, DROP ec_relationship, DROP ec_phone1, DROP ec_phone2, DROP diet_info, DROP diet_restrictions, DROP diet_severity, DROP current_rx, DROP current_conditions, DROP exercise_limits, DROP allergies, DROP med_allergies, DROP paperwork_complete, DROP hoby_app_complete, DROP hours_complete, DROP amb_registered, DROP fundraising_complete, DROP bg_check_submitted, DROP bg_check_complete, DROP age, DROP requirement_notes, DROP psms_uploaded_on, DROP eval_pros, DROP eval_cons, DROP eval_discussions, DROP eval_enthusiastic, DROP eval_organized, DROP eval_equally, DROP eval_responsible, DROP eval_attentive, DROP eval_include, DROP eval_professional, DROP eval_punctual, DROP eval_whynot, DROP eval_comments, DROP eval_status, DROP assignment_check_in, DROP assignment_closing_ceremonies, DROP assignment_check_out, DROP assignment_check_in_notes, DROP assignment_closing_ceremonies_notes, DROP assignment_check_out_notes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador CHANGE seminar_year seminar_year SMALLINT DEFAULT 2026 NOT NULL, CHANGE status status VARCHAR(20) DEFAULT \'registered\' NOT NULL');
        $this->addSql('CREATE INDEX IDX_ambassador_year ON ambassador (seminar_year)');
        $this->addSql('ALTER TABLE applicant CHANGE seminar_year seminar_year SMALLINT DEFAULT 2026 NOT NULL');
        $this->addSql('CREATE INDEX IDX_applicant_year ON applicant (seminar_year)');
        $this->addSql('ALTER TABLE comings_and_goings CHANGE seminar_year seminar_year SMALLINT DEFAULT 2026 NOT NULL');
        $this->addSql('CREATE INDEX IDX_comings_and_goings_year ON comings_and_goings (seminar_year)');
        $this->addSql('ALTER TABLE letter_group CHANGE seminar_year seminar_year SMALLINT DEFAULT 2026 NOT NULL');
        $this->addSql('CREATE INDEX IDX_letter_group_year ON letter_group (seminar_year)');
        $this->addSql('ALTER TABLE staff_assignment CHANGE status status VARCHAR(20) DEFAULT \'active\' NOT NULL');
        $this->addSql('CREATE INDEX IDX_SA_YEAR ON staff_assignment (seminar_year)');
        $this->addSql('ALTER TABLE staff_assignment RENAME INDEX idx_d697546ba76ed395 TO IDX_SA_USER');
        $this->addSql('ALTER TABLE staff_assignment RENAME INDEX idx_d697546b60c5456b TO IDX_SA_LETTER_GROUP');
        $this->addSql('ALTER TABLE staff_assignment RENAME INDEX idx_d697546b89408e37 TO IDX_SA_DORM_ROOM');
        $this->addSql('ALTER TABLE user ADD letter_group_id INT DEFAULT NULL, ADD dorm_room_id INT DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL, ADD shirt_size VARCHAR(10) DEFAULT NULL, ADD position VARCHAR(50) DEFAULT NULL, ADD ec_first_name VARCHAR(100) DEFAULT NULL, ADD ec_last_name VARCHAR(100) DEFAULT NULL, ADD ec_relationship VARCHAR(100) DEFAULT NULL, ADD ec_phone1 VARCHAR(20) DEFAULT NULL, ADD ec_phone2 VARCHAR(20) DEFAULT NULL, ADD diet_info LONGTEXT DEFAULT NULL, ADD diet_restrictions LONGTEXT DEFAULT NULL, ADD diet_severity VARCHAR(100) DEFAULT NULL, ADD current_rx LONGTEXT DEFAULT NULL, ADD current_conditions LONGTEXT DEFAULT NULL, ADD exercise_limits LONGTEXT DEFAULT NULL, ADD allergies LONGTEXT DEFAULT NULL, ADD med_allergies LONGTEXT DEFAULT NULL, ADD paperwork_complete TINYINT(1) DEFAULT NULL, ADD hoby_app_complete TINYINT(1) DEFAULT NULL, ADD hours_complete TINYINT(1) DEFAULT NULL, ADD amb_registered TINYINT(1) DEFAULT NULL, ADD fundraising_complete TINYINT(1) DEFAULT NULL, ADD bg_check_submitted TINYINT(1) DEFAULT NULL, ADD bg_check_complete TINYINT(1) DEFAULT NULL, ADD age INT DEFAULT NULL, ADD requirement_notes LONGTEXT DEFAULT NULL, ADD psms_uploaded_on VARCHAR(100) DEFAULT NULL, ADD eval_pros LONGTEXT DEFAULT NULL, ADD eval_cons LONGTEXT DEFAULT NULL, ADD eval_discussions VARCHAR(5) DEFAULT NULL, ADD eval_enthusiastic VARCHAR(5) DEFAULT NULL, ADD eval_organized VARCHAR(5) DEFAULT NULL, ADD eval_equally VARCHAR(5) DEFAULT NULL, ADD eval_responsible VARCHAR(5) DEFAULT NULL, ADD eval_attentive VARCHAR(5) DEFAULT NULL, ADD eval_include VARCHAR(5) DEFAULT NULL, ADD eval_professional VARCHAR(5) DEFAULT NULL, ADD eval_punctual VARCHAR(5) DEFAULT NULL, ADD eval_whynot LONGTEXT DEFAULT NULL, ADD eval_comments LONGTEXT DEFAULT NULL, ADD eval_status TINYINT(1) DEFAULT 0 NOT NULL, ADD assignment_check_in VARCHAR(100) DEFAULT NULL, ADD assignment_closing_ceremonies VARCHAR(100) DEFAULT NULL, ADD assignment_check_out VARCHAR(100) DEFAULT NULL, ADD assignment_check_in_notes LONGTEXT DEFAULT NULL, ADD assignment_closing_ceremonies_notes LONGTEXT DEFAULT NULL, ADD assignment_check_out_notes LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64960C5456B FOREIGN KEY (letter_group_id) REFERENCES letter_group (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64989408E37 FOREIGN KEY (dorm_room_id) REFERENCES dorm_room (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64960C5456B ON user (letter_group_id)');
        $this->addSql('CREATE INDEX IDX_8D93D64989408E37 ON user (dorm_room_id)');
    }
}
