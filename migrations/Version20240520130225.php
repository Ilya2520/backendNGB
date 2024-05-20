<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520130225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE completed_quest_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quest_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE bank_account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bank_account_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE talk_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transaction_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bank_account (id INT NOT NULL, user_id INT NOT NULL, type_id INT DEFAULT NULL, amount VARCHAR(255) NOT NULL, information JSON DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_53A23E0AA76ED395 ON bank_account (user_id)');
        $this->addSql('CREATE INDEX IDX_53A23E0AC54C8C93 ON bank_account (type_id)');
        $this->addSql('CREATE TABLE bank_account_type (id INT NOT NULL, type VARCHAR(255) NOT NULL, conditions DOUBLE PRECISION DEFAULT \'0\', PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, talk_id INT NOT NULL, from_user_id INT NOT NULL, to_user_id INT DEFAULT NULL, send_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, text TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307F6F0601D5 ON message (talk_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F2130303A ON message (from_user_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F29F6EE60 ON message (to_user_id)');
        $this->addSql('COMMENT ON COLUMN message.send_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE talk (id INT NOT NULL, user_id INT NOT NULL, taked_by_id INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, closed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) DEFAULT NULL, at_work BOOLEAN DEFAULT NULL, is_solved BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F24D5BBA76ED395 ON talk (user_id)');
        $this->addSql('CREATE INDEX IDX_9F24D5BBB3804BB8 ON talk (taked_by_id)');
        $this->addSql('COMMENT ON COLUMN talk.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN talk.closed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN talk.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, from_bank_account_id INT NOT NULL, to_bank_account_id INT DEFAULT NULL, transaction_type_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, message VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D15BCCFC13 ON transaction (from_bank_account_id)');
        $this->addSql('CREATE INDEX IDX_723705D19317DD6D ON transaction (to_bank_account_id)');
        $this->addSql('CREATE INDEX IDX_723705D1B3E6B071 ON transaction (transaction_type_id)');
        $this->addSql('CREATE TABLE transaction_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, info JSON DEFAULT NULL, image_link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0AC54C8C93 FOREIGN KEY (type_id) REFERENCES bank_account_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F6F0601D5 FOREIGN KEY (talk_id) REFERENCES talk (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F2130303A FOREIGN KEY (from_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F29F6EE60 FOREIGN KEY (to_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BBB3804BB8 FOREIGN KEY (taked_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D15BCCFC13 FOREIGN KEY (from_bank_account_id) REFERENCES bank_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19317DD6D FOREIGN KEY (to_bank_account_id) REFERENCES bank_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B3E6B071 FOREIGN KEY (transaction_type_id) REFERENCES transaction_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE quest');
        $this->addSql('ALTER TABLE "user" ADD email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD surname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD last_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD is_active BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD settings JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" DROP balance');
        $this->addSql('ALTER TABLE "user" ALTER name DROP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE bank_account_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bank_account_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE talk_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transaction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transaction_type_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE completed_quest_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quest_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quest (id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE bank_account DROP CONSTRAINT FK_53A23E0AA76ED395');
        $this->addSql('ALTER TABLE bank_account DROP CONSTRAINT FK_53A23E0AC54C8C93');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F6F0601D5');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F2130303A');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F29F6EE60');
        $this->addSql('ALTER TABLE talk DROP CONSTRAINT FK_9F24D5BBA76ED395');
        $this->addSql('ALTER TABLE talk DROP CONSTRAINT FK_9F24D5BBB3804BB8');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D15BCCFC13');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D19317DD6D');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1B3E6B071');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE bank_account_type');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE talk');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_type');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL');
        $this->addSql('ALTER TABLE "user" ADD balance DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP email');
        $this->addSql('ALTER TABLE "user" DROP roles');
        $this->addSql('ALTER TABLE "user" DROP password');
        $this->addSql('ALTER TABLE "user" DROP surname');
        $this->addSql('ALTER TABLE "user" DROP last_name');
        $this->addSql('ALTER TABLE "user" DROP phone');
        $this->addSql('ALTER TABLE "user" DROP is_active');
        $this->addSql('ALTER TABLE "user" DROP settings');
        $this->addSql('ALTER TABLE "user" DROP username');
        $this->addSql('ALTER TABLE "user" ALTER name SET NOT NULL');
    }
}
