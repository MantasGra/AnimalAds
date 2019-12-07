<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191207181129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ad ADD created_by_id INT NOT NULL, CHANGE boost_id boost_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_77E0ED58B03A8386 ON ad (created_by_id)');
        $this->addSql('ALTER TABLE animal CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL, CHANGE breed breed VARCHAR(255) DEFAULT NULL, CHANGE weight weight VARCHAR(255) DEFAULT NULL, CHANGE volume volume VARCHAR(255) DEFAULT NULL, CHANGE height height VARCHAR(255) DEFAULT NULL, CHANGE trained trained TINYINT(1) DEFAULT NULL, CHANGE vaccinated vaccinated TINYINT(1) DEFAULT NULL, CHANGE safe_to_transport safe_to_transport TINYINT(1) DEFAULT NULL, CHANGE friendly friendly TINYINT(1) DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE created_by_id created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE parent_comment_id parent_comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE file CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE file_id file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription CHANGE duration duration INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE birth_date birth_date DATE DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL, CHANGE language language VARCHAR(255) DEFAULT NULL, CHANGE gender gender VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58B03A8386');
        $this->addSql('DROP INDEX IDX_77E0ED58B03A8386 ON ad');
        $this->addSql('ALTER TABLE ad DROP created_by_id, CHANGE boost_id boost_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE breed breed VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE weight weight VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE volume volume VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE height height VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE trained trained TINYINT(1) DEFAULT \'NULL\', CHANGE vaccinated vaccinated TINYINT(1) DEFAULT \'NULL\', CHANGE safe_to_transport safe_to_transport TINYINT(1) DEFAULT \'NULL\', CHANGE friendly friendly TINYINT(1) DEFAULT \'NULL\', CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE category CHANGE created_by_id created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE parent_comment_id parent_comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE file CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE message CHANGE file_id file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription CHANGE duration duration INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE first_name first_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE birth_date birth_date DATE DEFAULT \'NULL\', CHANGE city city VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE language language VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE gender gender VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
