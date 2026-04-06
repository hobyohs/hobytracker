<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260405300000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add seminar_year to ambassador, letter_group, comings_and_goings, applicant. Add status to ambassador.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ambassador ADD seminar_year SMALLINT NOT NULL DEFAULT 2026, ADD status VARCHAR(20) NOT NULL DEFAULT \'registered\'');
        $this->addSql('ALTER TABLE letter_group ADD seminar_year SMALLINT NOT NULL DEFAULT 2026');
        $this->addSql('ALTER TABLE comings_and_goings ADD seminar_year SMALLINT NOT NULL DEFAULT 2026');
        $this->addSql('ALTER TABLE applicant ADD seminar_year SMALLINT NOT NULL DEFAULT 2026');

        $this->addSql('CREATE INDEX IDX_ambassador_year ON ambassador (seminar_year)');
        $this->addSql('CREATE INDEX IDX_letter_group_year ON letter_group (seminar_year)');
        $this->addSql('CREATE INDEX IDX_comings_and_goings_year ON comings_and_goings (seminar_year)');
        $this->addSql('CREATE INDEX IDX_applicant_year ON applicant (seminar_year)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_ambassador_year ON ambassador');
        $this->addSql('DROP INDEX IDX_letter_group_year ON letter_group');
        $this->addSql('DROP INDEX IDX_comings_and_goings_year ON comings_and_goings');
        $this->addSql('DROP INDEX IDX_applicant_year ON applicant');

        $this->addSql('ALTER TABLE ambassador DROP seminar_year, DROP status');
        $this->addSql('ALTER TABLE letter_group DROP seminar_year');
        $this->addSql('ALTER TABLE comings_and_goings DROP seminar_year');
        $this->addSql('ALTER TABLE applicant DROP seminar_year');
    }
}
