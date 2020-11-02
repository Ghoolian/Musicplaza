<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201102122638 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cluster (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE password_recovery (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, created DATETIME NOT NULL, expires DATETIME NOT NULL, is_used TINYINT(1) NOT NULL, INDEX IDX_63D40109A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission_cluster (permission_id INT NOT NULL, cluster_id INT NOT NULL, INDEX IDX_D2D488C3FED90CCA (permission_id), INDEX IDX_D2D488C3C36A3328 (cluster_id), PRIMARY KEY(permission_id, cluster_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, updated DATETIME NOT NULL, is_super TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_cluster (user_id INT NOT NULL, cluster_id INT NOT NULL, INDEX IDX_BDC8779EA76ED395 (user_id), INDEX IDX_BDC8779EC36A3328 (cluster_id), PRIMARY KEY(user_id, cluster_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE password_recovery ADD CONSTRAINT FK_63D40109A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE permission_cluster ADD CONSTRAINT FK_D2D488C3FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_cluster ADD CONSTRAINT FK_D2D488C3C36A3328 FOREIGN KEY (cluster_id) REFERENCES cluster (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cluster ADD CONSTRAINT FK_BDC8779EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cluster ADD CONSTRAINT FK_BDC8779EC36A3328 FOREIGN KEY (cluster_id) REFERENCES cluster (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission_cluster DROP FOREIGN KEY FK_D2D488C3C36A3328');
        $this->addSql('ALTER TABLE user_cluster DROP FOREIGN KEY FK_BDC8779EC36A3328');
        $this->addSql('ALTER TABLE permission_cluster DROP FOREIGN KEY FK_D2D488C3FED90CCA');
        $this->addSql('ALTER TABLE password_recovery DROP FOREIGN KEY FK_63D40109A76ED395');
        $this->addSql('ALTER TABLE user_cluster DROP FOREIGN KEY FK_BDC8779EA76ED395');
        $this->addSql('DROP TABLE cluster');
        $this->addSql('DROP TABLE password_recovery');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE permission_cluster');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_cluster');
    }
}
