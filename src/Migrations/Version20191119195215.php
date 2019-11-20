<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191119195215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quantity_delivery (id INT AUTO_INCREMENT NOT NULL, order_detail_id INT DEFAULT NULL, delivery_id INT DEFAULT NULL, bill_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_4718AC5664577843 (order_detail_id), INDEX IDX_4718AC5612136921 (delivery_id), INDEX IDX_4718AC561A8C12F5 (bill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantity_delivery ADD CONSTRAINT FK_4718AC5664577843 FOREIGN KEY (order_detail_id) REFERENCES quote_order_detail (id)');
        $this->addSql('ALTER TABLE quantity_delivery ADD CONSTRAINT FK_4718AC5612136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE quantity_delivery ADD CONSTRAINT FK_4718AC561A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('DROP TABLE quote_order_detail_delivery');
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC101A8C12F5');
        $this->addSql('DROP INDEX UNIQ_3781EC101A8C12F5 ON delivery');
        $this->addSql('ALTER TABLE delivery DROP bill_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quote_order_detail_delivery (quote_order_detail_id INT NOT NULL, delivery_id INT NOT NULL, INDEX IDX_5752D0198AF49733 (quote_order_detail_id), INDEX IDX_5752D01912136921 (delivery_id), PRIMARY KEY(quote_order_detail_id, delivery_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE quote_order_detail_delivery ADD CONSTRAINT FK_5752D01912136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quote_order_detail_delivery ADD CONSTRAINT FK_5752D0198AF49733 FOREIGN KEY (quote_order_detail_id) REFERENCES quote_order_detail (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE quantity_delivery');
        $this->addSql('ALTER TABLE delivery ADD bill_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC101A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3781EC101A8C12F5 ON delivery (bill_id)');
    }
}
