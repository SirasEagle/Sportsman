<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727174529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C53E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id)');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C53A6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id)');
        $this->addSql('CREATE INDEX IDX_DCBB0C53E934951A ON unit (exercise_id)');
        $this->addSql('CREATE INDEX IDX_DCBB0C53A6CCCFC9 ON unit (workout_id)');
        $this->addSql('ALTER TABLE workout ADD `real` TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unit DROP FOREIGN KEY FK_DCBB0C53E934951A');
        $this->addSql('ALTER TABLE unit DROP FOREIGN KEY FK_DCBB0C53A6CCCFC9');
        $this->addSql('DROP INDEX IDX_DCBB0C53E934951A ON unit');
        $this->addSql('DROP INDEX IDX_DCBB0C53A6CCCFC9 ON unit');
        $this->addSql('ALTER TABLE workout DROP `real`');
    }
}
