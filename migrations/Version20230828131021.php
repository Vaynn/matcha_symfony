<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230828131021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matchs (id INT AUTO_INCREMENT NOT NULL, first_user_id INT NOT NULL, second_user_id INT NOT NULL, matched_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6B1E6041B4E2BF69 (first_user_id), INDEX IDX_6B1E6041B02C53F8 (second_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE views (id INT AUTO_INCREMENT NOT NULL, is_view_id INT NOT NULL, view_by_id INT NOT NULL, viewed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_11F09C8715299E0F (is_view_id), INDEX IDX_11F09C87C8C48717 (view_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E6041B4E2BF69 FOREIGN KEY (first_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E6041B02C53F8 FOREIGN KEY (second_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE views ADD CONSTRAINT FK_11F09C8715299E0F FOREIGN KEY (is_view_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE views ADD CONSTRAINT FK_11F09C87C8C48717 FOREIGN KEY (view_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD liked_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E6041B4E2BF69');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E6041B02C53F8');
        $this->addSql('ALTER TABLE views DROP FOREIGN KEY FK_11F09C8715299E0F');
        $this->addSql('ALTER TABLE views DROP FOREIGN KEY FK_11F09C87C8C48717');
        $this->addSql('DROP TABLE matchs');
        $this->addSql('DROP TABLE views');
        $this->addSql('ALTER TABLE `like` DROP liked_at');
    }
}
