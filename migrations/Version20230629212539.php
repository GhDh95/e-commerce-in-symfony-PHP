<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230629212539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receipt ADD buyer_id INT NOT NULL');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B6456C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5399B6456C755722 ON receipt (buyer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE receipt DROP CONSTRAINT FK_5399B6456C755722');
        $this->addSql('DROP INDEX IDX_5399B6456C755722');
        $this->addSql('ALTER TABLE receipt DROP buyer_id');
    }
}
