<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260413000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create seminar table for per-year start/end date management (replaces SEMINAR_END_DATE env var)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE seminar (
                id         INT AUTO_INCREMENT NOT NULL,
                year       SMALLINT NOT NULL,
                start_date DATE NOT NULL,
                end_date   DATE NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY seminar_year_unique (year)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE seminar');
    }
}
