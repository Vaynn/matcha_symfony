<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127104812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE preferences (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, min_age INT DEFAULT NULL, max_age INT DEFAULT NULL, distance INT DEFAULT NULL, UNIQUE INDEX UNIQ_E931A6F5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preferences_sexuality (preferences_id INT NOT NULL, sexuality_id INT NOT NULL, INDEX IDX_3072795D7CCD6FB7 (preferences_id), INDEX IDX_3072795DAAE181FA (sexuality_id), PRIMARY KEY(preferences_id, sexuality_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preferences_gender (preferences_id INT NOT NULL, gender_id INT NOT NULL, INDEX IDX_F04195C87CCD6FB7 (preferences_id), INDEX IDX_F04195C8708A0E0 (gender_id), PRIMARY KEY(preferences_id, gender_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preferences_tag (preferences_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_3D2434507CCD6FB7 (preferences_id), INDEX IDX_3D243450BAD26311 (tag_id), PRIMARY KEY(preferences_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE preferences ADD CONSTRAINT FK_E931A6F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE preferences_sexuality ADD CONSTRAINT FK_3072795D7CCD6FB7 FOREIGN KEY (preferences_id) REFERENCES preferences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preferences_sexuality ADD CONSTRAINT FK_3072795DAAE181FA FOREIGN KEY (sexuality_id) REFERENCES sexuality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preferences_gender ADD CONSTRAINT FK_F04195C87CCD6FB7 FOREIGN KEY (preferences_id) REFERENCES preferences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preferences_gender ADD CONSTRAINT FK_F04195C8708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preferences_tag ADD CONSTRAINT FK_3D2434507CCD6FB7 FOREIGN KEY (preferences_id) REFERENCES preferences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preferences_tag ADD CONSTRAINT FK_3D243450BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preferences DROP FOREIGN KEY FK_E931A6F5A76ED395');
        $this->addSql('ALTER TABLE preferences_sexuality DROP FOREIGN KEY FK_3072795D7CCD6FB7');
        $this->addSql('ALTER TABLE preferences_sexuality DROP FOREIGN KEY FK_3072795DAAE181FA');
        $this->addSql('ALTER TABLE preferences_gender DROP FOREIGN KEY FK_F04195C87CCD6FB7');
        $this->addSql('ALTER TABLE preferences_gender DROP FOREIGN KEY FK_F04195C8708A0E0');
        $this->addSql('ALTER TABLE preferences_tag DROP FOREIGN KEY FK_3D2434507CCD6FB7');
        $this->addSql('ALTER TABLE preferences_tag DROP FOREIGN KEY FK_3D243450BAD26311');
        $this->addSql('DROP TABLE preferences');
        $this->addSql('DROP TABLE preferences_sexuality');
        $this->addSql('DROP TABLE preferences_gender');
        $this->addSql('DROP TABLE preferences_tag');
    }
}
