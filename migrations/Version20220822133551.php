<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220822133551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense ADD COLUMN user VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__expense AS SELECT id, name, amount, date, description, category FROM expense');
        $this->addSql('DROP TABLE expense');
        $this->addSql('CREATE TABLE expense (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, amount INTEGER NOT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO expense (id, name, amount, date, description, category) SELECT id, name, amount, date, description, category FROM __temp__expense');
        $this->addSql('DROP TABLE __temp__expense');
    }
}
