<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513193103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, from_bank_account_id INT NOT NULL, to_bank_account_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_723705D15BCCFC13 (from_bank_account_id), INDEX IDX_723705D19317DD6D (to_bank_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D15BCCFC13 FOREIGN KEY (from_bank_account_id) REFERENCES bank_account (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19317DD6D FOREIGN KEY (to_bank_account_id) REFERENCES bank_account (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D15BCCFC13');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D19317DD6D');
        $this->addSql('DROP TABLE transaction');
    }
}
