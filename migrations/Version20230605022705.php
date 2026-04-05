<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605022705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador ADD eval_engaged VARCHAR(5) DEFAULT NULL, ADD eval_service VARCHAR(5) DEFAULT NULL, ADD eval_pros LONGTEXT DEFAULT NULL, ADD eval_cons LONGTEXT DEFAULT NULL, ADD eval_recommendation VARCHAR(5) DEFAULT NULL, ADD eval_comments LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD eval_pros LONGTEXT DEFAULT NULL, ADD eval_cons LONGTEXT DEFAULT NULL, ADD eval_discussions VARCHAR(5) DEFAULT NULL, ADD eval_enthusiastic VARCHAR(5) NOT NULL, ADD eval_organized VARCHAR(5) DEFAULT NULL, ADD eval_equally VARCHAR(5) DEFAULT NULL, ADD eval_responsible VARCHAR(5) DEFAULT NULL, ADD eval_attentive VARCHAR(5) DEFAULT NULL, ADD eval_include VARCHAR(5) DEFAULT NULL, ADD eval_professional VARCHAR(5) DEFAULT NULL, ADD eval_punctual VARCHAR(5) DEFAULT NULL, ADD eval_whynot LONGTEXT DEFAULT NULL, ADD eval_comments LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ambassador DROP eval_engaged, DROP eval_service, DROP eval_pros, DROP eval_cons, DROP eval_recommendation, DROP eval_comments');
        $this->addSql('ALTER TABLE user DROP eval_pros, DROP eval_cons, DROP eval_discussions, DROP eval_enthusiastic, DROP eval_organized, DROP eval_equally, DROP eval_responsible, DROP eval_attentive, DROP eval_include, DROP eval_professional, DROP eval_punctual, DROP eval_whynot, DROP eval_comments');
    }
}
