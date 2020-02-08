<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200203195700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agent CHANGE is_online is_online TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE quote_order ADD tax_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF9B2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id)');
        $this->addSql('CREATE INDEX IDX_F8D61EF9B2A824D8 ON quote_order (tax_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agent CHANGE is_online is_online TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF9B2A824D8');
        $this->addSql('DROP INDEX IDX_F8D61EF9B2A824D8 ON quote_order');
        $this->addSql('ALTER TABLE quote_order DROP tax_id');
    }
}
