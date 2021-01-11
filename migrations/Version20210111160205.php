<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111160205 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chats MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE chats DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE chats CHANGE id chatid INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE chats ADD PRIMARY KEY (chatid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chats MODIFY chatid INT NOT NULL');
        $this->addSql('ALTER TABLE chats DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE chats CHANGE chatid id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE chats ADD PRIMARY KEY (id)');
    }
}
