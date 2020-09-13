<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200913173511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE characters_characters (characters_source INT NOT NULL, characters_target INT NOT NULL, INDEX IDX_40338DA361C56FD4 (characters_source), INDEX IDX_40338DA378203F5B (characters_target), PRIMARY KEY(characters_source, characters_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE characters_characters ADD CONSTRAINT FK_40338DA361C56FD4 FOREIGN KEY (characters_source) REFERENCES characters (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE characters_characters ADD CONSTRAINT FK_40338DA378203F5B FOREIGN KEY (characters_target) REFERENCES characters (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE characters_characters');
    }
}
