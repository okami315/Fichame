<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230620084220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE link_form link_form VARCHAR(255) DEFAULT NULL, CHANGE start_date start_date DATETIME DEFAULT NULL, CHANGE end_date end_date DATETIME DEFAULT NULL, CHANGE edit_date edit_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE task CHANGE start_time start_time DATETIME DEFAULT NULL, CHANGE end_time end_time DATETIME DEFAULT NULL, CHANGE state_request state_request VARCHAR(255) DEFAULT NULL, CHANGE status_resolve_date status_resolve_date DATETIME DEFAULT NULL, CHANGE chore chore JSON DEFAULT NULL, CHANGE start_time_compare start_time_compare DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE type CHANGE icon icon VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE naf naf VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_event CHANGE estimated_hours estimated_hours DOUBLE PRECISION DEFAULT NULL, CHANGE real_hours real_hours DOUBLE PRECISION DEFAULT NULL, CHANGE extra_hours extra_hours DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE link_form link_form VARCHAR(255) DEFAULT \'NULL\', CHANGE start_date start_date DATETIME DEFAULT \'NULL\', CHANGE end_date end_date DATETIME DEFAULT \'NULL\', CHANGE edit_date edit_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE task CHANGE start_time start_time DATETIME DEFAULT \'NULL\', CHANGE end_time end_time DATETIME DEFAULT \'NULL\', CHANGE state_request state_request VARCHAR(255) DEFAULT \'NULL\', CHANGE status_resolve_date status_resolve_date DATETIME DEFAULT \'NULL\', CHANGE chore chore LONGTEXT DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE start_time_compare start_time_compare DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE type CHANGE icon icon VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE naf naf VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user_event CHANGE estimated_hours estimated_hours DOUBLE PRECISION DEFAULT \'NULL\', CHANGE real_hours real_hours DOUBLE PRECISION DEFAULT \'NULL\', CHANGE extra_hours extra_hours DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}
