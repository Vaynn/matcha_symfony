<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110102353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gender (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sexuality (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD gender_id INT DEFAULT NULL, ADD sexuality_id INT DEFAULT NULL, DROP gender, DROP sexuality');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AAE181FA FOREIGN KEY (sexuality_id) REFERENCES sexuality (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649708A0E0 ON user (gender_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AAE181FA ON user (sexuality_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649708A0E0');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AAE181FA');
        $this->addSql('DROP TABLE gender');
        $this->addSql('DROP TABLE sexuality');
        $this->addSql('DROP INDEX IDX_8D93D649708A0E0 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649AAE181FA ON user');
        $this->addSql('ALTER TABLE user ADD gender VARCHAR(50) DEFAULT NULL, ADD sexuality VARCHAR(50) DEFAULT NULL, DROP gender_id, DROP sexuality_id');
    }
}
