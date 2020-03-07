<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200307125834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED6307EED');
        $this->addSql('DROP INDEX UNIQ_1F1B251ED6307EED ON item');
        $this->addSql('ALTER TABLE item CHANGE ean_code_id imei_code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EA4D07301 FOREIGN KEY (imei_code_id) REFERENCES imei_code (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251EA4D07301 ON item (imei_code_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EA4D07301');
        $this->addSql('DROP INDEX UNIQ_1F1B251EA4D07301 ON item');
        $this->addSql('ALTER TABLE item CHANGE imei_code_id ean_code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED6307EED FOREIGN KEY (ean_code_id) REFERENCES ean_code (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251ED6307EED ON item (ean_code_id)');
    }
}
