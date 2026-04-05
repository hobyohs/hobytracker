<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116041747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ambassador (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, photo VARCHAR(255) DEFAULT NULL, pref_name VARCHAR(100) DEFAULT NULL, ethnicity VARCHAR(25) DEFAULT NULL, pronouns VARCHAR(50) DEFAULT NULL, county VARCHAR(50) DEFAULT NULL, home_phone VARCHAR(25) DEFAULT NULL, cell_phone VARCHAR(25) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, school VARCHAR(100) NOT NULL, shirt_size VARCHAR(10) DEFAULT NULL, parent1_first_name VARCHAR(100) DEFAULT NULL, parent1_last_name VARCHAR(100) DEFAULT NULL, parent1_phone1 VARCHAR(25) DEFAULT NULL, parent1_phone2 VARCHAR(25) DEFAULT NULL, parent1_email VARCHAR(100) DEFAULT NULL, parent2_first_name VARCHAR(100) DEFAULT NULL, parent2_last_name VARCHAR(100) DEFAULT NULL, parent2_phone1 VARCHAR(25) DEFAULT NULL, parent2_phone2 VARCHAR(25) DEFAULT NULL, parent2_email VARCHAR(100) DEFAULT NULL, dorm VARCHAR(25) DEFAULT NULL, room VARCHAR(25) DEFAULT NULL, checked_in TINYINT(1) NOT NULL, checked_out TINYINT(1) NOT NULL, checkout_deposit_decision VARCHAR(50) DEFAULT NULL, checkin_paperwork TINYINT(1) DEFAULT NULL, checkin_deposit TINYINT(1) DEFAULT NULL, checkin_deposit_method VARCHAR(25) DEFAULT NULL, checkin_deposit_notes VARCHAR(255) DEFAULT NULL, checkin_meds TINYINT(1) DEFAULT NULL, cg_form TINYINT(1) DEFAULT NULL, psms_uploaded_on DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ambassador');
    }
}
