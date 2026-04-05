<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117020422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comings_and_goings (id INT AUTO_INCREMENT NOT NULL, ambassador_id INT DEFAULT NULL, checked_out_by_id INT DEFAULT NULL, checked_in_by_id INT DEFAULT NULL, departure DATETIME DEFAULT NULL, arrival DATETIME DEFAULT NULL, checked_out TINYINT(1) NOT NULL, checked_in TINYINT(1) NOT NULL, INDEX IDX_95DE01E34A709FDF (ambassador_id), INDEX IDX_95DE01E33D4EB07D (checked_out_by_id), INDEX IDX_95DE01E342569552 (checked_in_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE letter_group (id INT AUTO_INCREMENT NOT NULL, letter VARCHAR(5) NOT NULL, home_building VARCHAR(100) DEFAULT NULL, home_room VARCHAR(100) DEFAULT NULL, color VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comings_and_goings ADD CONSTRAINT FK_95DE01E34A709FDF FOREIGN KEY (ambassador_id) REFERENCES ambassador (id)');
        $this->addSql('ALTER TABLE comings_and_goings ADD CONSTRAINT FK_95DE01E33D4EB07D FOREIGN KEY (checked_out_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comings_and_goings ADD CONSTRAINT FK_95DE01E342569552 FOREIGN KEY (checked_in_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ambassador ADD letter_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ambassador ADD CONSTRAINT FK_6C62F0CE60C5456B FOREIGN KEY (letter_group_id) REFERENCES letter_group (id)');
        $this->addSql('CREATE INDEX IDX_6C62F0CE60C5456B ON ambassador (letter_group_id)');
        $this->addSql('ALTER TABLE user ADD letter_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64960C5456B FOREIGN KEY (letter_group_id) REFERENCES letter_group (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64960C5456B ON user (letter_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador DROP FOREIGN KEY FK_6C62F0CE60C5456B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64960C5456B');
        $this->addSql('ALTER TABLE comings_and_goings DROP FOREIGN KEY FK_95DE01E34A709FDF');
        $this->addSql('ALTER TABLE comings_and_goings DROP FOREIGN KEY FK_95DE01E33D4EB07D');
        $this->addSql('ALTER TABLE comings_and_goings DROP FOREIGN KEY FK_95DE01E342569552');
        $this->addSql('DROP TABLE comings_and_goings');
        $this->addSql('DROP TABLE letter_group');
        $this->addSql('DROP INDEX IDX_6C62F0CE60C5456B ON ambassador');
        $this->addSql('ALTER TABLE ambassador DROP letter_group_id');
        $this->addSql('DROP INDEX IDX_8D93D64960C5456B ON user');
        $this->addSql('ALTER TABLE user DROP letter_group_id');
    }
}
