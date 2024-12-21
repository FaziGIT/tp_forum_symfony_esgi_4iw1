<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241221132948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__language AS SELECT id, name, code FROM language');
        $this->addSql('DROP TABLE language');
        $this->addSql('CREATE TABLE language (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(2) NOT NULL)');
        $this->addSql('INSERT INTO language (id, name, code) SELECT id, name, code FROM __temp__language');
        $this->addSql('DROP TABLE __temp__language');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__language AS SELECT id, name, code FROM language');
        $this->addSql('DROP TABLE language');
        $this->addSql('CREATE TABLE language (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO language (id, name, code) SELECT id, name, code FROM __temp__language');
        $this->addSql('DROP TABLE __temp__language');
    }
}
