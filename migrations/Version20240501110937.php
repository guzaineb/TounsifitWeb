<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501110937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, seen TINYINT(1) NOT NULL, crated_at DATETIME NOT NULL, user VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allergie_produit DROP FOREIGN KEY allergie_produit_ibfk_1');
        $this->addSql('ALTER TABLE allergie_produit DROP FOREIGN KEY allergie_produit_ibfk_2');
        $this->addSql('DROP TABLE allergie_produit');
        $this->addSql('ALTER TABLE information_educatif CHANGE symptome symptome VARCHAR(255) NOT NULL, CHANGE causes causes LONGTEXT NOT NULL, CHANGE traitement traitement VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE likes likes INT DEFAULT 0 NOT NULL, CHANGE dislikes dislikes INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE information_educatif ADD CONSTRAINT FK_40345EB44E0372DF FOREIGN KEY (id_allergie) REFERENCES allergie (id)');
        $this->addSql('DROP INDEX id_allergie ON information_educatif');
        $this->addSql('CREATE INDEX IDX_40345EB44E0372DF ON information_educatif (id_allergie)');
        $this->addSql('DROP INDEX id_user ON panier');
        $this->addSql('ALTER TABLE panier DROP id_produit, DROP nom_produit, DROP prix_produit, DROP id_user');
        $this->addSql('ALTER TABLE produit DROP nom, DROP description, DROP prix, DROP Quantity, DROP image');
        $this->addSql('DROP INDEX email ON user');
        $this->addSql('ALTER TABLE user DROP email, DROP nom, DROP prenom, DROP password, DROP roles, DROP isconnected, DROP isbanned');
        $this->addSql('ALTER TABLE user_allergie DROP FOREIGN KEY user_allergie_ibfk_1');
        $this->addSql('ALTER TABLE user_allergie ADD CONSTRAINT FK_FE557A4AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_allergie ADD CONSTRAINT FK_FE557A4A7C86304A FOREIGN KEY (allergie_id) REFERENCES allergie (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX allergie_id ON user_allergie');
        $this->addSql('CREATE INDEX IDX_FE557A4A7C86304A ON user_allergie (allergie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allergie_produit (allergie_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, INDEX produit_id (produit_id), INDEX allergie_id (allergie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE allergie_produit ADD CONSTRAINT allergie_produit_ibfk_1 FOREIGN KEY (allergie_id) REFERENCES allergie (id)');
        $this->addSql('ALTER TABLE allergie_produit ADD CONSTRAINT allergie_produit_ibfk_2 FOREIGN KEY (produit_id) REFERENCES produit (id_produit)');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE information_educatif DROP FOREIGN KEY FK_40345EB44E0372DF');
        $this->addSql('ALTER TABLE information_educatif DROP FOREIGN KEY FK_40345EB44E0372DF');
        $this->addSql('ALTER TABLE information_educatif CHANGE symptome symptome VARCHAR(255) DEFAULT NULL, CHANGE causes causes TEXT DEFAULT NULL, CHANGE traitement traitement TEXT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE likes likes INT DEFAULT 0, CHANGE dislikes dislikes INT DEFAULT 0');
        $this->addSql('DROP INDEX idx_40345eb44e0372df ON information_educatif');
        $this->addSql('CREATE INDEX id_allergie ON information_educatif (id_allergie)');
        $this->addSql('ALTER TABLE information_educatif ADD CONSTRAINT FK_40345EB44E0372DF FOREIGN KEY (id_allergie) REFERENCES allergie (id)');
        $this->addSql('ALTER TABLE panier ADD id_produit INT NOT NULL, ADD nom_produit VARCHAR(255) NOT NULL, ADD prix_produit INT NOT NULL, ADD id_user INT NOT NULL');
        $this->addSql('CREATE INDEX id_user ON panier (id_user)');
        $this->addSql('ALTER TABLE produit ADD nom VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD prix INT NOT NULL, ADD Quantity INT NOT NULL, ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD email VARCHAR(255) DEFAULT NULL, ADD nom VARCHAR(255) DEFAULT NULL, ADD prenom VARCHAR(255) DEFAULT NULL, ADD password VARCHAR(255) DEFAULT NULL, ADD roles LONGTEXT DEFAULT NULL, ADD isconnected TINYINT(1) DEFAULT NULL, ADD isbanned TINYINT(1) DEFAULT 0');
        $this->addSql('CREATE UNIQUE INDEX email ON user (email)');
        $this->addSql('ALTER TABLE user_allergie DROP FOREIGN KEY FK_FE557A4AA76ED395');
        $this->addSql('ALTER TABLE user_allergie DROP FOREIGN KEY FK_FE557A4A7C86304A');
        $this->addSql('ALTER TABLE user_allergie DROP FOREIGN KEY FK_FE557A4A7C86304A');
        $this->addSql('ALTER TABLE user_allergie ADD CONSTRAINT user_allergie_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX idx_fe557a4a7c86304a ON user_allergie');
        $this->addSql('CREATE INDEX allergie_id ON user_allergie (allergie_id)');
        $this->addSql('ALTER TABLE user_allergie ADD CONSTRAINT FK_FE557A4A7C86304A FOREIGN KEY (allergie_id) REFERENCES allergie (id) ON DELETE CASCADE');
    }
}
