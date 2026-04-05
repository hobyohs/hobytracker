<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104001928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE applicant ADD decision VARCHAR(255) DEFAULT NULL, ADD eval_facilitators VARCHAR(255) DEFAULT NULL, ADD eval_recommendation SMALLINT DEFAULT NULL, ADD eval_engaged SMALLINT DEFAULT NULL, ADD eval_service SMALLINT DEFAULT NULL, ADD eval_pros LONGTEXT DEFAULT NULL, ADD eval_cons LONGTEXT DEFAULT NULL, ADD eval_comments LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE applicant DROP decision, DROP eval_facilitators, DROP eval_recommendation, DROP eval_engaged, DROP eval_service, DROP eval_pros, DROP eval_cons, DROP eval_comments');
    }
}
