<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200529201241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, ean_code_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, culture VARCHAR(255) DEFAULT NULL, INDEX IDX_5373C966D6307EED (ean_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966D6307EED FOREIGN KEY (ean_code_id) REFERENCES ean_code (id)');
        $this->addSql('ALTER TABLE ean_code DROP FOREIGN KEY FK_E04EF53A6E44244');
        $this->addSql('DROP INDEX IDX_E04EF53A6E44244 ON ean_code');
        $this->addSql('ALTER TABLE ean_code DROP pays_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE country');
        $this->addSql('ALTER TABLE ean_code ADD pays_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ean_code ADD CONSTRAINT FK_E04EF53A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('CREATE INDEX IDX_E04EF53A6E44244 ON ean_code (pays_id)');
    }
}
