<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428023137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador ADD current_conditions LONGTEXT DEFAULT NULL, ADD exercise_limits LONGTEXT DEFAULT NULL, ADD allergies LONGTEXT DEFAULT NULL, ADD med_allergies LONGTEXT DEFAULT NULL, ADD approved_otc LONGTEXT DEFAULT NULL, ADD current_rx LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador DROP current_conditions, DROP exercise_limits, DROP allergies, DROP med_allergies, DROP approved_otc, DROP current_rx');
    }
}
