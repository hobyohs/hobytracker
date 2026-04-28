<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260414000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create bed_check_assignment table for scheduling staff to dorm floors on specific nights';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE bed_check_assignment (
                id                  INT AUTO_INCREMENT NOT NULL,
                staff_assignment_id INT NOT NULL,
                dorm                VARCHAR(100) NOT NULL,
                floor               VARCHAR(50) NOT NULL,
                night               VARCHAR(10) NOT NULL,
                seminar_year        SMALLINT NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY bed_check_unique (staff_assignment_id, dorm, floor, night, seminar_year),
                CONSTRAINT FK_bed_check_staff FOREIGN KEY (staff_assignment_id) REFERENCES staff_assignment (id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bed_check_assignment');
    }
}
