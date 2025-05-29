<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250529054813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nutritional_table (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, kcal DOUBLE PRECISION DEFAULT NULL, fat DOUBLE PRECISION DEFAULT NULL, saturated_fat DOUBLE PRECISION DEFAULT NULL, carbohydrates DOUBLE PRECISION DEFAULT NULL, sugars DOUBLE PRECISION DEFAULT NULL, protein DOUBLE PRECISION DEFAULT NULL, salt DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_754A7B8B4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nutritional_table ADD CONSTRAINT FK_754A7B8B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE portion_product ADD CONSTRAINT FK_ABD6061F162BE352 FOREIGN KEY (portion_id) REFERENCES `portion` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE portion_product ADD CONSTRAINT FK_ABD6061F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nutritional_table DROP FOREIGN KEY FK_754A7B8B4584665A');
        $this->addSql('DROP TABLE nutritional_table');
        $this->addSql('ALTER TABLE portion_product DROP FOREIGN KEY FK_ABD6061F162BE352');
        $this->addSql('ALTER TABLE portion_product DROP FOREIGN KEY FK_ABD6061F4584665A');
    }
}
