<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240519122750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador DROP dorm, DROP room');
        $this->addSql('ALTER TABLE user DROP dorm, DROP room');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador ADD dorm VARCHAR(25) DEFAULT NULL, ADD room VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD dorm VARCHAR(25) DEFAULT NULL, ADD room VARCHAR(25) DEFAULT NULL');
    }
}
