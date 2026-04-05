<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230122210040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador ADD ec_first_name VARCHAR(100) DEFAULT NULL, ADD ec_last_name VARCHAR(100) DEFAULT NULL, ADD ec_relationship VARCHAR(100) DEFAULT NULL, ADD ec_phone1 VARCHAR(20) DEFAULT NULL, ADD ec_phone2 VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD ec_first_name VARCHAR(100) DEFAULT NULL, ADD ec_last_name VARCHAR(100) DEFAULT NULL, ADD ec_relationship VARCHAR(100) DEFAULT NULL, ADD ec_phone1 VARCHAR(20) DEFAULT NULL, ADD ec_phone2 VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador DROP ec_first_name, DROP ec_last_name, DROP ec_relationship, DROP ec_phone1, DROP ec_phone2');
        $this->addSql('ALTER TABLE user DROP ec_first_name, DROP ec_last_name, DROP ec_relationship, DROP ec_phone1, DROP ec_phone2');
    }
}
