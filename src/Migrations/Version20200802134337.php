<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200802134337 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address RENAME INDEX fk_d4e6f81f92f3e70 TO IDX_D4E6F81F92F3E70');
        $this->addSql('ALTER TABLE item CHANGE stock stock INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address RENAME INDEX idx_d4e6f81f92f3e70 TO FK_D4E6F81F92F3E70');
        $this->addSql('ALTER TABLE item CHANGE stock stock INT NOT NULL');
    }
}
