<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012181815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_last_muscle_group ADD muscle_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_last_muscle_group ADD CONSTRAINT FK_C3D4C8F144004D0 FOREIGN KEY (muscle_group_id) REFERENCES muscle_group (id)');
        $this->addSql('CREATE INDEX IDX_C3D4C8F144004D0 ON user_last_muscle_group (muscle_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_last_muscle_group DROP FOREIGN KEY FK_C3D4C8F144004D0');
        $this->addSql('DROP INDEX IDX_C3D4C8F144004D0 ON user_last_muscle_group');
        $this->addSql('ALTER TABLE user_last_muscle_group DROP muscle_group_id');
    }
}
