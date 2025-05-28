<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528124919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `portion` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE portion_product (portion_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_ABD6061F162BE352 (portion_id), INDEX IDX_ABD6061F4584665A (product_id), PRIMARY KEY(portion_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE portion_product ADD CONSTRAINT FK_ABD6061F162BE352 FOREIGN KEY (portion_id) REFERENCES `portion` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE portion_product ADD CONSTRAINT FK_ABD6061F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE portion_product DROP FOREIGN KEY FK_ABD6061F162BE352');
        $this->addSql('ALTER TABLE portion_product DROP FOREIGN KEY FK_ABD6061F4584665A');
        $this->addSql('DROP TABLE `portion`');
        $this->addSql('DROP TABLE portion_product');
    }
}
