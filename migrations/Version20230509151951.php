<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509151951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD paperwork_complete TINYINT(1) DEFAULT NULL, ADD hoby_app_complete TINYINT(1) DEFAULT NULL, ADD hours_complete TINYINT(1) DEFAULT NULL, ADD amb_registered TINYINT(1) DEFAULT NULL, ADD fundraising_complete TINYINT(1) DEFAULT NULL, ADD bg_check_submitted TINYINT(1) DEFAULT NULL, ADD bg_check_complete TINYINT(1) DEFAULT NULL, ADD age INT DEFAULT NULL, ADD requirement_notes LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP paperwork_complete, DROP hoby_app_complete, DROP hours_complete, DROP amb_registered, DROP fundraising_complete, DROP bg_check_submitted, DROP bg_check_complete, DROP age, DROP requirement_notes');
    }
}
