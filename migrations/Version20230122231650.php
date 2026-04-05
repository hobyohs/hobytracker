<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230122231650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador ADD diet_restrictions LONGTEXT DEFAULT NULL, ADD diet_info LONGTEXT DEFAULT NULL, ADD diet_severity VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD diet_info LONGTEXT DEFAULT NULL, ADD diet_restrictions LONGTEXT DEFAULT NULL, ADD diet_severity VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador DROP diet_restrictions, DROP diet_info, DROP diet_severity');
        $this->addSql('ALTER TABLE user DROP diet_info, DROP diet_restrictions, DROP diet_severity');
    }
}
