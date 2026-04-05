<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426210612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador ADD bed_thursday_user_id INT DEFAULT NULL, ADD bed_friday_user_id INT DEFAULT NULL, ADD bed_saturday_user_id INT DEFAULT NULL, ADD bed_thursday TINYINT(1) DEFAULT NULL, ADD bed_friday TINYINT(1) DEFAULT NULL, ADD bed_saturday TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE ambassador ADD CONSTRAINT FK_6C62F0CE8A93F972 FOREIGN KEY (bed_thursday_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ambassador ADD CONSTRAINT FK_6C62F0CE1A4E7881 FOREIGN KEY (bed_friday_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ambassador ADD CONSTRAINT FK_6C62F0CE7AC1BD29 FOREIGN KEY (bed_saturday_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6C62F0CE8A93F972 ON ambassador (bed_thursday_user_id)');
        $this->addSql('CREATE INDEX IDX_6C62F0CE1A4E7881 ON ambassador (bed_friday_user_id)');
        $this->addSql('CREATE INDEX IDX_6C62F0CE7AC1BD29 ON ambassador (bed_saturday_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador DROP FOREIGN KEY FK_6C62F0CE8A93F972');
        $this->addSql('ALTER TABLE ambassador DROP FOREIGN KEY FK_6C62F0CE1A4E7881');
        $this->addSql('ALTER TABLE ambassador DROP FOREIGN KEY FK_6C62F0CE7AC1BD29');
        $this->addSql('DROP INDEX IDX_6C62F0CE8A93F972 ON ambassador');
        $this->addSql('DROP INDEX IDX_6C62F0CE1A4E7881 ON ambassador');
        $this->addSql('DROP INDEX IDX_6C62F0CE7AC1BD29 ON ambassador');
        $this->addSql('ALTER TABLE ambassador DROP bed_thursday_user_id, DROP bed_friday_user_id, DROP bed_saturday_user_id, DROP bed_thursday, DROP bed_friday, DROP bed_saturday');
    }
}
