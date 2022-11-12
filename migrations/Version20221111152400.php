<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111152400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD illustration_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E665926566C FOREIGN KEY (illustration_id) REFERENCES images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E665926566C ON article (illustration_id)');
        $this->addSql('ALTER TABLE images CHANGE name nom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images CHANGE nom name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E665926566C');
        $this->addSql('DROP INDEX UNIQ_23A0E665926566C ON article');
        $this->addSql('ALTER TABLE article DROP illustration_id');
    }
}
