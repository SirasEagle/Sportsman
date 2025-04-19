<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012181116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_last_muscle_group (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATE DEFAULT NULL, INDEX IDX_C3D4C8F1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_last_muscle_group ADD CONSTRAINT FK_C3D4C8F1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        // $this->addSql('DROP TABLE enum_test');
        // $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C44004D0 FOREIGN KEY (muscle_group_id) REFERENCES muscle_group (id)');
        // $this->addSql('CREATE INDEX IDX_AEDAD51C44004D0 ON exercise (muscle_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE enum_test (enum_fld VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'No\' NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_last_muscle_group DROP FOREIGN KEY FK_C3D4C8F1A76ED395');
        $this->addSql('DROP TABLE user_last_muscle_group');
        // $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C44004D0');
        // $this->addSql('DROP INDEX IDX_AEDAD51C44004D0 ON exercise');
    }
}
