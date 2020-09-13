<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200912072226 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE characters_episodes (characters_id INT NOT NULL, episodes_id INT NOT NULL, INDEX IDX_81245AD8C70F0E28 (characters_id), INDEX IDX_81245AD8319135AF (episodes_id), PRIMARY KEY(characters_id, episodes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE characters_episodes ADD CONSTRAINT FK_81245AD8C70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE characters_episodes ADD CONSTRAINT FK_81245AD8319135AF FOREIGN KEY (episodes_id) REFERENCES episodes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE characters_episodes');
    }
}
