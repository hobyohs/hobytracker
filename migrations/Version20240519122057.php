<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240519122057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dorm_room (id INT AUTO_INCREMENT NOT NULL, dorm VARCHAR(100) NOT NULL, room VARCHAR(50) NOT NULL, bathroom_type VARCHAR(255) NOT NULL, floor VARCHAR(50) NOT NULL, sort_order SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ambassador ADD dorm_room_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ambassador ADD CONSTRAINT FK_6C62F0CE89408E37 FOREIGN KEY (dorm_room_id) REFERENCES dorm_room (id)');
        $this->addSql('CREATE INDEX IDX_6C62F0CE89408E37 ON ambassador (dorm_room_id)');
        $this->addSql('ALTER TABLE user ADD dorm_room_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64989408E37 FOREIGN KEY (dorm_room_id) REFERENCES dorm_room (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64989408E37 ON user (dorm_room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador DROP FOREIGN KEY FK_6C62F0CE89408E37');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64989408E37');
        $this->addSql('DROP TABLE dorm_room');
        $this->addSql('DROP INDEX IDX_6C62F0CE89408E37 ON ambassador');
        $this->addSql('ALTER TABLE ambassador DROP dorm_room_id');
        $this->addSql('DROP INDEX IDX_8D93D64989408E37 ON user');
        $this->addSql('ALTER TABLE user DROP dorm_room_id');
    }
}
