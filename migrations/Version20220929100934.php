<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929100934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_by_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(20) NOT NULL, author VARCHAR(255) DEFAULT NULL, price NUMERIC(4, 2) NOT NULL, released_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_CBE5A331B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CBE5A331B03A8386 ON book (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(20) NOT NULL, author VARCHAR(255) DEFAULT NULL, price NUMERIC(4, 2) NOT NULL, released_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
    }
}
