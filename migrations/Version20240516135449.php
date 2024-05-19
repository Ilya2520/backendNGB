<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516135449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE talk ADD taked_by_id INT DEFAULT NULL, ADD at_work TINYINT(1) DEFAULT NULL, ADD is_solved TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BBB3804BB8 FOREIGN KEY (taked_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9F24D5BBB3804BB8 ON talk (taked_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE talk DROP FOREIGN KEY FK_9F24D5BBB3804BB8');
        $this->addSql('DROP INDEX IDX_9F24D5BBB3804BB8 ON talk');
        $this->addSql('ALTER TABLE talk DROP taked_by_id, DROP at_work, DROP is_solved');
    }
}
