<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200913172656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE planet (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE characters ADD planet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EA25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
        $this->addSql('CREATE INDEX IDX_3A29410EA25E9820 ON characters (planet_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EA25E9820');
        $this->addSql('DROP TABLE planet');
        $this->addSql('DROP INDEX IDX_3A29410EA25E9820 ON characters');
        $this->addSql('ALTER TABLE characters DROP planet_id');
    }
}
