<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230601075428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD status SMALLINT NOT NULL, ADD workers_available INT NOT NULL, ADD distance DOUBLE PRECISION NOT NULL, ADD drivers_number INT NOT NULL, CHANGE link_form link_form VARCHAR(255) DEFAULT NULL, CHANGE start_date start_date DATETIME DEFAULT NULL, CHANGE end_date end_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE task CHANGE start_time start_time DATETIME DEFAULT NULL, CHANGE end_time end_time DATETIME DEFAULT NULL, CHANGE state_request state_request VARCHAR(255) DEFAULT NULL, CHANGE status_resolve_date status_resolve_date DATETIME DEFAULT NULL, CHANGE chore chore JSON DEFAULT NULL, CHANGE start_time_compare start_time_compare DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE naf naf VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_event ADD private_car TINYINT(1) NOT NULL, ADD asistance SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP status, DROP workers_available, DROP distance, DROP drivers_number, CHANGE link_form link_form VARCHAR(255) DEFAULT \'NULL\', CHANGE start_date start_date DATETIME DEFAULT \'NULL\', CHANGE end_date end_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE task CHANGE start_time start_time DATETIME DEFAULT \'NULL\', CHANGE end_time end_time DATETIME DEFAULT \'NULL\', CHANGE state_request state_request VARCHAR(255) DEFAULT \'NULL\', CHANGE status_resolve_date status_resolve_date DATETIME DEFAULT \'NULL\', CHANGE chore chore LONGTEXT DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE start_time_compare start_time_compare DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE naf naf VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user_event DROP private_car, DROP asistance');
    }
}
