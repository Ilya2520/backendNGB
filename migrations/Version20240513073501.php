<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513073501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_tasks (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tasks_quest (user_tasks_id INT NOT NULL, quest_id INT NOT NULL, INDEX IDX_53800A253AAC931 (user_tasks_id), INDEX IDX_53800A25209E9EF4 (quest_id), PRIMARY KEY(user_tasks_id, quest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_tasks_quest ADD CONSTRAINT FK_53800A253AAC931 FOREIGN KEY (user_tasks_id) REFERENCES user_tasks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tasks_quest ADD CONSTRAINT FK_53800A25209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_quest DROP FOREIGN KEY FK_A1D5034F209E9EF4');
        $this->addSql('ALTER TABLE user_quest DROP FOREIGN KEY FK_A1D5034FA76ED395');
        $this->addSql('DROP TABLE user_quest');
        $this->addSql('ALTER TABLE completed_quest DROP FOREIGN KEY FK_6C1C0665A76ED395');
        $this->addSql('ALTER TABLE completed_quest ADD CONSTRAINT FK_6C1C0665A76ED395 FOREIGN KEY (user_id) REFERENCES user_tasks (id)');
        $this->addSql('ALTER TABLE user ADD email VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD surname VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD is_active TINYINT(1) DEFAULT NULL, ADD settings JSON DEFAULT NULL, DROP balance');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE completed_quest DROP FOREIGN KEY FK_6C1C0665A76ED395');
        $this->addSql('CREATE TABLE user_quest (user_id INT NOT NULL, quest_id INT NOT NULL, INDEX IDX_A1D5034FA76ED395 (user_id), INDEX IDX_A1D5034F209E9EF4 (quest_id), PRIMARY KEY(user_id, quest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_quest ADD CONSTRAINT FK_A1D5034F209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_quest ADD CONSTRAINT FK_A1D5034FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tasks_quest DROP FOREIGN KEY FK_53800A253AAC931');
        $this->addSql('ALTER TABLE user_tasks_quest DROP FOREIGN KEY FK_53800A25209E9EF4');
        $this->addSql('DROP TABLE user_tasks');
        $this->addSql('DROP TABLE user_tasks_quest');
        $this->addSql('ALTER TABLE completed_quest DROP FOREIGN KEY FK_6C1C0665A76ED395');
        $this->addSql('ALTER TABLE completed_quest ADD CONSTRAINT FK_6C1C0665A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user ADD balance DOUBLE PRECISION NOT NULL, DROP email, DROP roles, DROP password, DROP surname, DROP last_name, DROP phone, DROP is_active, DROP settings');
    }
}
