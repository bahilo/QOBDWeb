<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200529203915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C966D6307EED');
        $this->addSql('DROP INDEX IDX_5373C966D6307EED ON country');
        $this->addSql('ALTER TABLE country DROP ean_code_id');
        $this->addSql('ALTER TABLE ean_code ADD country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ean_code ADD CONSTRAINT FK_E04EF53F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_E04EF53F92F3E70 ON ean_code (country_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE country ADD ean_code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966D6307EED FOREIGN KEY (ean_code_id) REFERENCES ean_code (id)');
        $this->addSql('CREATE INDEX IDX_5373C966D6307EED ON country (ean_code_id)');
        $this->addSql('ALTER TABLE ean_code DROP FOREIGN KEY FK_E04EF53F92F3E70');
        $this->addSql('DROP INDEX IDX_E04EF53F92F3E70 ON ean_code');
        $this->addSql('ALTER TABLE ean_code DROP country_id');
    }
}
