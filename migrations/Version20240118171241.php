<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240118171241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compra (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, sorteo_id INT DEFAULT NULL, numero_loteria INT NOT NULL, fecha_compra DATETIME NOT NULL, INDEX IDX_9EC131FFA76ED395 (user_id), INDEX IDX_9EC131FF663FD436 (sorteo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sorteo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, fecha_hora DATETIME NOT NULL, precio_numero INT NOT NULL, nums_avender INT NOT NULL, premio INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sorteo_user (sorteo_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EC5C2158663FD436 (sorteo_id), INDEX IDX_EC5C2158A76ED395 (user_id), PRIMARY KEY(sorteo_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, saldo_actual INT DEFAULT NULL, gasto_total INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compra ADD CONSTRAINT FK_9EC131FFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE compra ADD CONSTRAINT FK_9EC131FF663FD436 FOREIGN KEY (sorteo_id) REFERENCES sorteo (id)');
        $this->addSql('ALTER TABLE sorteo_user ADD CONSTRAINT FK_EC5C2158663FD436 FOREIGN KEY (sorteo_id) REFERENCES sorteo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sorteo_user ADD CONSTRAINT FK_EC5C2158A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compra DROP FOREIGN KEY FK_9EC131FFA76ED395');
        $this->addSql('ALTER TABLE compra DROP FOREIGN KEY FK_9EC131FF663FD436');
        $this->addSql('ALTER TABLE sorteo_user DROP FOREIGN KEY FK_EC5C2158663FD436');
        $this->addSql('ALTER TABLE sorteo_user DROP FOREIGN KEY FK_EC5C2158A76ED395');
        $this->addSql('DROP TABLE compra');
        $this->addSql('DROP TABLE sorteo');
        $this->addSql('DROP TABLE sorteo_user');
        $this->addSql('DROP TABLE user');
    }
}
