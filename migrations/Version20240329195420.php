<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329195420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador ADD taking_bus TINYINT(1) NOT NULL, DROP bus_to_stop, DROP bus_from_stop');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador ADD bus_to_stop VARCHAR(100) DEFAULT NULL, ADD bus_from_stop VARCHAR(100) DEFAULT NULL, DROP taking_bus');
    }
}
