<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104000413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE applicant ADD q1 LONGTEXT DEFAULT NULL, ADD q2 LONGTEXT DEFAULT NULL, ADD q3 LONGTEXT DEFAULT NULL, ADD q4 LONGTEXT DEFAULT NULL, ADD q5 LONGTEXT DEFAULT NULL, ADD q6 LONGTEXT DEFAULT NULL, ADD q7 LONGTEXT DEFAULT NULL, ADD q8 LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE applicant DROP q1, DROP q2, DROP q3, DROP q4, DROP q5, DROP q6, DROP q7, DROP q8');
    }
}
