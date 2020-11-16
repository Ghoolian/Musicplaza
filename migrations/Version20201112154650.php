<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201112154650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE likes_user');
        $this->addSql('ALTER TABLE likes ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_49CA4E7DA76ED395 ON likes (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE likes_user (likes_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_17BC4AF82F23775F (likes_id), INDEX IDX_17BC4AF8A76ED395 (user_id), PRIMARY KEY(likes_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE likes_user ADD CONSTRAINT FK_17BC4AF82F23775F FOREIGN KEY (likes_id) REFERENCES likes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes_user ADD CONSTRAINT FK_17BC4AF8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DA76ED395');
        $this->addSql('DROP INDEX IDX_49CA4E7DA76ED395 ON likes');
        $this->addSql('ALTER TABLE likes DROP user_id');
    }
}
