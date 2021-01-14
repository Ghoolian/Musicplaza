<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210112091132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chats (id INT AUTO_INCREMENT NOT NULL, user1_id INT NOT NULL, user2_id INT NOT NULL, text VARCHAR(255) NOT NULL, created DATETIME NOT NULL, INDEX IDX_2D68180F56AE248B (user1_id), INDEX IDX_2D68180F441B8B65 (user2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chats ADD CONSTRAINT FK_2D68180F56AE248B FOREIGN KEY (user1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chats ADD CONSTRAINT FK_2D68180F441B8B65 FOREIGN KEY (user2_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE chats');
    }
}
