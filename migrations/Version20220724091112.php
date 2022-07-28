<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220724091112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE events_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE person (id INT NOT NULL, event_id INT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE person_event (person_id INT NOT NULL, event_id INT NOT NULL, PRIMARY KEY(person_id, event_id))');
        $this->addSql('CREATE INDEX IDX_97981563217BBB47 ON person_event (person_id)');
        $this->addSql('CREATE INDEX IDX_9798156371F7E88B ON person_event (event_id)');
        $this->addSql('ALTER TABLE person_event ADD CONSTRAINT FK_97981563217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_event ADD CONSTRAINT FK_9798156371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE person_event DROP CONSTRAINT FK_97981563217BBB47');
        $this->addSql('DROP SEQUENCE person_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE events_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_event');
    }
}
