<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315155908 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etats CHANGE no_etat no_etat INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE lieux CHANGE no_lieu no_lieu INT AUTO_INCREMENT NOT NULL, CHANGE villes_no_ville villes_no_ville INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participants ADD password VARCHAR(20) NOT NULL, DROP mot_de_passe, CHANGE no_participant no_participant INT AUTO_INCREMENT NOT NULL, CHANGE mail mail VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE sites CHANGE no_site no_site INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE sorties CHANGE no_sortie no_sortie INT AUTO_INCREMENT NOT NULL, CHANGE organisateur organisateur INT DEFAULT NULL, CHANGE lieux_no_lieu lieux_no_lieu INT DEFAULT NULL, CHANGE etats_no_etat etats_no_etat INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inscriptions DROP FOREIGN KEY inscriptions_participants_fk');
        $this->addSql('ALTER TABLE inscriptions DROP date_inscription');
        $this->addSql('DROP INDEX inscriptions_participants_fk ON inscriptions');
        $this->addSql('CREATE INDEX IDX_74E0281CEF759E07 ON inscriptions (participants_no_participant)');
        $this->addSql('ALTER TABLE inscriptions ADD CONSTRAINT inscriptions_participants_fk FOREIGN KEY (participants_no_participant) REFERENCES participants (no_participant) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE villes CHANGE no_ville no_ville INT AUTO_INCREMENT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etats CHANGE no_etat no_etat INT NOT NULL');
        $this->addSql('ALTER TABLE inscriptions DROP FOREIGN KEY FK_74E0281CEF759E07');
        $this->addSql('ALTER TABLE inscriptions ADD date_inscription DATETIME NOT NULL');
        $this->addSql('DROP INDEX idx_74e0281cef759e07 ON inscriptions');
        $this->addSql('CREATE INDEX inscriptions_participants_fk ON inscriptions (participants_no_participant)');
        $this->addSql('ALTER TABLE inscriptions ADD CONSTRAINT FK_74E0281CEF759E07 FOREIGN KEY (participants_no_participant) REFERENCES participants (no_participant)');
        $this->addSql('ALTER TABLE lieux CHANGE no_lieu no_lieu INT NOT NULL, CHANGE villes_no_ville villes_no_ville INT NOT NULL');
        $this->addSql('ALTER TABLE participants ADD mot_de_passe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, DROP password, CHANGE no_participant no_participant INT NOT NULL, CHANGE mail mail VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE sites CHANGE no_site no_site INT NOT NULL');
        $this->addSql('ALTER TABLE sorties CHANGE no_sortie no_sortie INT NOT NULL, CHANGE etats_no_etat etats_no_etat INT NOT NULL, CHANGE lieux_no_lieu lieux_no_lieu INT NOT NULL, CHANGE organisateur organisateur INT NOT NULL');
        $this->addSql('ALTER TABLE villes CHANGE no_ville no_ville INT NOT NULL');
    }
}
