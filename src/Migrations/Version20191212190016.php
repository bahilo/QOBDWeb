<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191212190016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE agent (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, user_name VARCHAR(255) NOT NULL, password VARCHAR(300) NOT NULL, picture VARCHAR(255) DEFAULT NULL, is_admin TINYINT(1) NOT NULL, is_online TINYINT(1) DEFAULT 0, list_size INT DEFAULT NULL, is_activated TINYINT(1) NOT NULL, ipaddress VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_268B9C9DF8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agent_role (agent_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_FAF230893414710B (agent_id), INDEX IDX_FAF23089D60322AC (role_id), PRIMARY KEY(agent_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, item_groupe_id INT DEFAULT NULL, item_brand_id INT DEFAULT NULL, tax_id INT DEFAULT NULL, ref VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, sell_price DOUBLE PRECISION NOT NULL, purchase_price DOUBLE PRECISION NOT NULL, stock INT NOT NULL, picture VARCHAR(255) DEFAULT NULL, is_erasable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1F1B251EF8697D13 (comment_id), INDEX IDX_1F1B251E769F237C (item_groupe_id), INDEX IDX_1F1B251E28F818C3 (item_brand_id), INDEX IDX_1F1B251EB2A824D8 (tax_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_provider (item_id INT NOT NULL, provider_id INT NOT NULL, INDEX IDX_FEC9BB57126F525E (item_id), INDEX IDX_FEC9BB57A53A8AA (provider_id), PRIMARY KEY(item_id, provider_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discussion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, bill_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, rib VARCHAR(255) DEFAULT NULL, crn VARCHAR(255) DEFAULT NULL, pay_delay INT DEFAULT NULL, max_credit DOUBLE PRECISION DEFAULT NULL, is_activated TINYINT(1) NOT NULL, is_prospect TINYINT(1) NOT NULL, denomination VARCHAR(255) DEFAULT NULL, INDEX IDX_C74404553414710B (agent_id), INDEX IDX_C74404551A8C12F5 (bill_id), UNIQUE INDEX UNIQ_C7440455F8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, provider_id INT DEFAULT NULL, address_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, position VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, mobile VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, is_principal TINYINT(1) DEFAULT NULL, INDEX IDX_4C62E63819EB6921 (client_id), UNIQUE INDEX UNIQ_4C62E638F8697D13 (comment_id), INDEX IDX_4C62E638A53A8AA (provider_id), UNIQUE INDEX UNIQ_4C62E638F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote_order (id INT AUTO_INCREMENT NOT NULL, admin_comment_id INT DEFAULT NULL, private_comment_id INT DEFAULT NULL, public_comment_id INT DEFAULT NULL, agent_id INT DEFAULT NULL, client_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, status_id INT DEFAULT NULL, contact_id INT DEFAULT NULL, created_at DATETIME NOT NULL, is_quote TINYINT(1) DEFAULT NULL, validity_periode INT DEFAULT NULL, is_ref_visible TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_F8D61EF93057DB96 (admin_comment_id), UNIQUE INDEX UNIQ_F8D61EF9F5ACD764 (private_comment_id), UNIQUE INDEX UNIQ_F8D61EF962FBE97E (public_comment_id), INDEX IDX_F8D61EF93414710B (agent_id), INDEX IDX_F8D61EF919EB6921 (client_id), INDEX IDX_F8D61EF938248176 (currency_id), INDEX IDX_F8D61EF96BF700BD (status_id), INDEX IDX_F8D61EF9E7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bill (id INT AUTO_INCREMENT NOT NULL, private_comment_id INT DEFAULT NULL, public_comment_id INT DEFAULT NULL, income_statistic_id INT DEFAULT NULL, contact_id INT DEFAULT NULL, client_id INT DEFAULT NULL, pay_mode VARCHAR(255) DEFAULT NULL, pay DOUBLE PRECISION NOT NULL, pay_received DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, limit_date_at DATETIME DEFAULT NULL, payed_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7A2119E3F5ACD764 (private_comment_id), UNIQUE INDEX UNIQ_7A2119E362FBE97E (public_comment_id), INDEX IDX_7A2119E3B622266F (income_statistic_id), INDEX IDX_7A2119E3E7A1254A (contact_id), INDEX IDX_7A2119E319EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, status_id INT DEFAULT NULL, package INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_3781EC106BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(3000) DEFAULT NULL, code VARCHAR(255) NOT NULL, is_file TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, symbol VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL, country_code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, is_default TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, income_statistic_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, is_current TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, is_tvamarge TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8E81BA76F8697D13 (comment_id), INDEX IDX_8E81BA76B622266F (income_statistic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, rib VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_groupe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, is_principal TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_D4E6F81F8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_role (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, role_id INT DEFAULT NULL, privilege_id INT DEFAULT NULL, INDEX IDX_218831649D32F035 (action_id), INDEX IDX_21883164D60322AC (role_id), INDEX IDX_2188316432FB8AEA (privilege_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_tracker (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, agent_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_76C1A24E9D32F035 (action_id), INDEX IDX_76C1A24E3414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agent_discussion (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, discussion_id INT DEFAULT NULL, is_current TINYINT(1) NOT NULL, is_owner TINYINT(1) DEFAULT NULL, INDEX IDX_B1FF28003414710B (agent_id), INDEX IDX_B1FF28001ADED311 (discussion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, reminder_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alert_bill (alert_id INT NOT NULL, bill_id INT NOT NULL, INDEX IDX_F591CB6793035F72 (alert_id), INDEX IDX_F591CB671A8C12F5 (bill_id), PRIMARY KEY(alert_id, bill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(3000) NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE income_statistic (id INT AUTO_INCREMENT NOT NULL, company VARCHAR(255) DEFAULT NULL, purchase_total DOUBLE PRECISION NOT NULL, sell_total DOUBLE PRECISION NOT NULL, percent_income DOUBLE PRECISION NOT NULL, income DOUBLE PRECISION NOT NULL, pay_received DOUBLE PRECISION DEFAULT NULL, limit_date_at DATETIME DEFAULT NULL, pay_date_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE license (id INT AUTO_INCREMENT NOT NULL, app_version VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, hashed_key VARCHAR(255) NOT NULL, is_enable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, end_date_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, discussion_id INT DEFAULT NULL, agent_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, is_red TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6BD307F1ADED311 (discussion_id), INDEX IDX_B6BD307F3414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE privilege (id INT AUTO_INCREMENT NOT NULL, is_write TINYINT(1) NOT NULL, is_read TINYINT(1) NOT NULL, is_update TINYINT(1) NOT NULL, is_delete TINYINT(1) NOT NULL, is_send_mail TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantity_delivery (id INT AUTO_INCREMENT NOT NULL, order_detail_id INT DEFAULT NULL, delivery_id INT DEFAULT NULL, bill_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_4718AC5664577843 (order_detail_id), INDEX IDX_4718AC5612136921 (delivery_id), INDEX IDX_4718AC561A8C12F5 (bill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote_order_detail (id INT AUTO_INCREMENT NOT NULL, quote_order_id INT DEFAULT NULL, item_id INT DEFAULT NULL, tax_id INT DEFAULT NULL, quantity INT NOT NULL, item_sell_price DOUBLE PRECISION NOT NULL, quantity_delivery INT DEFAULT NULL, quantity_recieved INT DEFAULT NULL, INDEX IDX_5A68304067A5C5A9 (quote_order_id), INDEX IDX_5A683040126F525E (item_id), INDEX IDX_5A683040B2A824D8 (tax_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ref_generator (id INT AUTO_INCREMENT NOT NULL, ref INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE agent_role ADD CONSTRAINT FK_FAF230893414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agent_role ADD CONSTRAINT FK_FAF23089D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E769F237C FOREIGN KEY (item_groupe_id) REFERENCES item_groupe (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E28F818C3 FOREIGN KEY (item_brand_id) REFERENCES item_brand (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EB2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id)');
        $this->addSql('ALTER TABLE item_provider ADD CONSTRAINT FK_FEC9BB57126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_provider ADD CONSTRAINT FK_FEC9BB57A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404553414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404551A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E63819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF93057DB96 FOREIGN KEY (admin_comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF9F5ACD764 FOREIGN KEY (private_comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF962FBE97E FOREIGN KEY (public_comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF93414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF938248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF96BF700BD FOREIGN KEY (status_id) REFERENCES order_status (id)');
        $this->addSql('ALTER TABLE quote_order ADD CONSTRAINT FK_F8D61EF9E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E3F5ACD764 FOREIGN KEY (private_comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E362FBE97E FOREIGN KEY (public_comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E3B622266F FOREIGN KEY (income_statistic_id) REFERENCES income_statistic (id)');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E3E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE bill ADD CONSTRAINT FK_7A2119E319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC106BF700BD FOREIGN KEY (status_id) REFERENCES delivery_status (id)');
        $this->addSql('ALTER TABLE tax ADD CONSTRAINT FK_8E81BA76F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE tax ADD CONSTRAINT FK_8E81BA76B622266F FOREIGN KEY (income_statistic_id) REFERENCES income_statistic (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE action_role ADD CONSTRAINT FK_218831649D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE action_role ADD CONSTRAINT FK_21883164D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE action_role ADD CONSTRAINT FK_2188316432FB8AEA FOREIGN KEY (privilege_id) REFERENCES privilege (id)');
        $this->addSql('ALTER TABLE action_tracker ADD CONSTRAINT FK_76C1A24E9D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE action_tracker ADD CONSTRAINT FK_76C1A24E3414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE agent_discussion ADD CONSTRAINT FK_B1FF28003414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE agent_discussion ADD CONSTRAINT FK_B1FF28001ADED311 FOREIGN KEY (discussion_id) REFERENCES discussion (id)');
        $this->addSql('ALTER TABLE alert_bill ADD CONSTRAINT FK_F591CB6793035F72 FOREIGN KEY (alert_id) REFERENCES alert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alert_bill ADD CONSTRAINT FK_F591CB671A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1ADED311 FOREIGN KEY (discussion_id) REFERENCES discussion (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE quantity_delivery ADD CONSTRAINT FK_4718AC5664577843 FOREIGN KEY (order_detail_id) REFERENCES quote_order_detail (id)');
        $this->addSql('ALTER TABLE quantity_delivery ADD CONSTRAINT FK_4718AC5612136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE quantity_delivery ADD CONSTRAINT FK_4718AC561A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('ALTER TABLE quote_order_detail ADD CONSTRAINT FK_5A68304067A5C5A9 FOREIGN KEY (quote_order_id) REFERENCES quote_order (id)');
        $this->addSql('ALTER TABLE quote_order_detail ADD CONSTRAINT FK_5A683040126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE quote_order_detail ADD CONSTRAINT FK_5A683040B2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agent_role DROP FOREIGN KEY FK_FAF230893414710B');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404553414710B');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF93414710B');
        $this->addSql('ALTER TABLE action_tracker DROP FOREIGN KEY FK_76C1A24E3414710B');
        $this->addSql('ALTER TABLE agent_discussion DROP FOREIGN KEY FK_B1FF28003414710B');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3414710B');
        $this->addSql('ALTER TABLE item_provider DROP FOREIGN KEY FK_FEC9BB57126F525E');
        $this->addSql('ALTER TABLE quote_order_detail DROP FOREIGN KEY FK_5A683040126F525E');
        $this->addSql('ALTER TABLE agent_discussion DROP FOREIGN KEY FK_B1FF28001ADED311');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1ADED311');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E63819EB6921');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF919EB6921');
        $this->addSql('ALTER TABLE bill DROP FOREIGN KEY FK_7A2119E319EB6921');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF9E7A1254A');
        $this->addSql('ALTER TABLE bill DROP FOREIGN KEY FK_7A2119E3E7A1254A');
        $this->addSql('ALTER TABLE quote_order_detail DROP FOREIGN KEY FK_5A68304067A5C5A9');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404551A8C12F5');
        $this->addSql('ALTER TABLE alert_bill DROP FOREIGN KEY FK_F591CB671A8C12F5');
        $this->addSql('ALTER TABLE quantity_delivery DROP FOREIGN KEY FK_4718AC561A8C12F5');
        $this->addSql('ALTER TABLE quantity_delivery DROP FOREIGN KEY FK_4718AC5612136921');
        $this->addSql('ALTER TABLE action_role DROP FOREIGN KEY FK_218831649D32F035');
        $this->addSql('ALTER TABLE action_tracker DROP FOREIGN KEY FK_76C1A24E9D32F035');
        $this->addSql('ALTER TABLE agent_role DROP FOREIGN KEY FK_FAF23089D60322AC');
        $this->addSql('ALTER TABLE action_role DROP FOREIGN KEY FK_21883164D60322AC');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF938248176');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EB2A824D8');
        $this->addSql('ALTER TABLE quote_order_detail DROP FOREIGN KEY FK_5A683040B2A824D8');
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC106BF700BD');
        $this->addSql('ALTER TABLE item_provider DROP FOREIGN KEY FK_FEC9BB57A53A8AA');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638A53A8AA');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E769F237C');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E28F818C3');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF96BF700BD');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638F5B7AF75');
        $this->addSql('ALTER TABLE alert_bill DROP FOREIGN KEY FK_F591CB6793035F72');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DF8697D13');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EF8697D13');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455F8697D13');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638F8697D13');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF93057DB96');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF9F5ACD764');
        $this->addSql('ALTER TABLE quote_order DROP FOREIGN KEY FK_F8D61EF962FBE97E');
        $this->addSql('ALTER TABLE bill DROP FOREIGN KEY FK_7A2119E3F5ACD764');
        $this->addSql('ALTER TABLE bill DROP FOREIGN KEY FK_7A2119E362FBE97E');
        $this->addSql('ALTER TABLE tax DROP FOREIGN KEY FK_8E81BA76F8697D13');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81F8697D13');
        $this->addSql('ALTER TABLE bill DROP FOREIGN KEY FK_7A2119E3B622266F');
        $this->addSql('ALTER TABLE tax DROP FOREIGN KEY FK_8E81BA76B622266F');
        $this->addSql('ALTER TABLE action_role DROP FOREIGN KEY FK_2188316432FB8AEA');
        $this->addSql('ALTER TABLE quantity_delivery DROP FOREIGN KEY FK_4718AC5664577843');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE agent_role');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_provider');
        $this->addSql('DROP TABLE discussion');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE quote_order');
        $this->addSql('DROP TABLE bill');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE tax');
        $this->addSql('DROP TABLE delivery_status');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE item_groupe');
        $this->addSql('DROP TABLE item_brand');
        $this->addSql('DROP TABLE order_status');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE action_role');
        $this->addSql('DROP TABLE action_tracker');
        $this->addSql('DROP TABLE agent_discussion');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE alert_bill');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE income_statistic');
        $this->addSql('DROP TABLE license');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE privilege');
        $this->addSql('DROP TABLE quantity_delivery');
        $this->addSql('DROP TABLE quote_order_detail');
        $this->addSql('DROP TABLE ref_generator');
    }
}
