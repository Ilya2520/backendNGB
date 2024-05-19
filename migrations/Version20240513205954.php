<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513205954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE talk (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', closed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) DEFAULT NULL, INDEX IDX_9F24D5BBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, info JSON DEFAULT NULL, image_link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD transaction_type_id INT NOT NULL, ADD message VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B3E6B071 FOREIGN KEY (transaction_type_id) REFERENCES transaction_type (id)');
        $this->addSql('CREATE INDEX IDX_723705D1B3E6B071 ON transaction (transaction_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1B3E6B071');
        $this->addSql('ALTER TABLE talk DROP FOREIGN KEY FK_9F24D5BBA76ED395');
        $this->addSql('DROP TABLE talk');
        $this->addSql('DROP TABLE transaction_type');
        $this->addSql('DROP INDEX IDX_723705D1B3E6B071 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP transaction_type_id, DROP message');
    }
}
