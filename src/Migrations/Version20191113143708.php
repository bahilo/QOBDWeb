<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191113143708 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quote_order ADD contact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF9E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('CREATE INDEX IDX_F8D61EF9E7A1254A ON quote_order (contact_id)');
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC10F5B7AF75');
        $this->addSql('DROP INDEX IDX_3781EC10F5B7AF75 ON delivery');
        $this->addSql('ALTER TABLE delivery DROP address_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE delivery ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_3781EC10F5B7AF75 ON delivery (address_id)');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF9E7A1254A');
        $this->addSql('DROP INDEX IDX_F8D61EF9E7A1254A ON quote_order');
        $this->addSql('ALTER TABLE quote_order DROP contact_id');
    }
}
