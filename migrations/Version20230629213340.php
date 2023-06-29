<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230629213340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receipt DROP CONSTRAINT fk_5399b6459d86650f');
        $this->addSql('ALTER TABLE receipt DROP CONSTRAINT fk_5399b6456c755722');
        $this->addSql('DROP INDEX idx_5399b6456c755722');
        $this->addSql('DROP INDEX idx_5399b6459d86650f');
        $this->addSql('ALTER TABLE receipt DROP user_id_id');
        $this->addSql('ALTER TABLE receipt DROP buyer_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE receipt ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE receipt ADD buyer_id INT NOT NULL');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT fk_5399b6459d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT fk_5399b6456c755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5399b6456c755722 ON receipt (buyer_id)');
        $this->addSql('CREATE INDEX idx_5399b6459d86650f ON receipt (user_id_id)');
    }
}
