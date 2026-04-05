<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250608203524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD assignment_check_in LONGTEXT DEFAULT NULL, ADD assignment_closing_ceremonies LONGTEXT DEFAULT NULL, ADD assignment_check_out LONGTEXT DEFAULT NULL, ADD assignment_check_in_notes LONGTEXT DEFAULT NULL, ADD assignment_closing_ceremonies_notes LONGTEXT DEFAULT NULL, ADD assignment_check_out_notes LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP assignment_check_in, DROP assignment_closing_ceremonies, DROP assignment_check_out, DROP assignment_check_in_notes, DROP assignment_closing_ceremonies_notes, DROP assignment_check_out_notes');
    }
}
