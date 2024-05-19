<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514134823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank_account_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, conditions VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, talk_id INT NOT NULL, from_user_id INT NOT NULL, to_user_id INT DEFAULT NULL, send_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', text LONGTEXT DEFAULT NULL, INDEX IDX_B6BD307F6F0601D5 (talk_id), INDEX IDX_B6BD307F2130303A (from_user_id), INDEX IDX_B6BD307F29F6EE60 (to_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F6F0601D5 FOREIGN KEY (talk_id) REFERENCES talk (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F2130303A FOREIGN KEY (from_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F29F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bank_account ADD type_id INT DEFAULT NULL, DROP type');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0AC54C8C93 FOREIGN KEY (type_id) REFERENCES bank_account_type (id)');
        $this->addSql('CREATE INDEX IDX_53A23E0AC54C8C93 ON bank_account (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0AC54C8C93');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F6F0601D5');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F2130303A');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F29F6EE60');
        $this->addSql('DROP TABLE bank_account_type');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP INDEX IDX_53A23E0AC54C8C93 ON bank_account');
        $this->addSql('ALTER TABLE bank_account ADD type VARCHAR(255) NOT NULL, DROP type_id');
    }
}
