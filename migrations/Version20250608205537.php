<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250608205537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE assignment_check_in assignment_check_in VARCHAR(100) DEFAULT NULL, CHANGE assignment_closing_ceremonies assignment_closing_ceremonies VARCHAR(100) DEFAULT NULL, CHANGE assignment_check_out assignment_check_out VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE assignment_check_in assignment_check_in LONGTEXT DEFAULT NULL, CHANGE assignment_closing_ceremonies assignment_closing_ceremonies LONGTEXT DEFAULT NULL, CHANGE assignment_check_out assignment_check_out LONGTEXT DEFAULT NULL');
    }
}
