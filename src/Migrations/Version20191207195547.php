<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191207195547 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, parent_comment_id INT DEFAULT NULL, written_by_id INT NOT NULL, ad_id INT NOT NULL, created_at DATETIME NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_9474526CBF2AF943 (parent_comment_id), INDEX IDX_9474526CAB69C8EF (written_by_id), INDEX IDX_9474526C4F34D596 (ad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, category_id INT NOT NULL, created_at DATETIME NOT NULL, send_email TINYINT(1) NOT NULL, duration INT DEFAULT NULL, INDEX IDX_A3C664D3A76ED395 (user_id), INDEX IDX_A3C664D312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sent_from_id INT NOT NULL, sent_to_id INT NOT NULL, file_id INT DEFAULT NULL, written_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_B6BD307F247EE14 (sent_from_id), INDEX IDX_B6BD307F3E89D3ED (sent_to_id), UNIQUE INDEX UNIQ_B6BD307F93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE viewed_ad (id INT AUTO_INCREMENT NOT NULL, ad_id INT NOT NULL, user_id INT NOT NULL, viewed_at DATETIME NOT NULL, INDEX IDX_D1F245194F34D596 (ad_id), INDEX IDX_D1F24519A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, registered_at DATETIME NOT NULL, birth_date DATE DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, language VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, enabled TINYINT(1) NOT NULL, temp_token VARCHAR(255) DEFAULT NULL, temp_password_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE boost (id INT AUTO_INCREMENT NOT NULL, date_from DATETIME NOT NULL, duration INT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_ad (id INT AUTO_INCREMENT NOT NULL, ad_id INT NOT NULL, user_id INT NOT NULL, saved_at DATETIME NOT NULL, INDEX IDX_308EED44F34D596 (ad_id), INDEX IDX_308EED4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, context VARCHAR(255) NOT NULL, original_name VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8C9F36105E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_64C19C1B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, breed VARCHAR(255) DEFAULT NULL, weight VARCHAR(255) DEFAULT NULL, volume VARCHAR(255) DEFAULT NULL, height VARCHAR(255) DEFAULT NULL, born_at DATETIME NOT NULL, addictions LONGTEXT DEFAULT NULL, trained TINYINT(1) DEFAULT NULL, vaccinated TINYINT(1) DEFAULT NULL, safe_to_transport TINYINT(1) DEFAULT NULL, friendly TINYINT(1) DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, INDEX IDX_6AAB231FB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ad (id INT AUTO_INCREMENT NOT NULL, boost_id INT DEFAULT NULL, created_by_id INT NOT NULL, animal_id INT NOT NULL, title VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, view_count INT NOT NULL, hidden TINYINT(1) NOT NULL, report_count INT NOT NULL, UNIQUE INDEX UNIQ_77E0ED58B09F48DA (boost_id), INDEX IDX_77E0ED58B03A8386 (created_by_id), UNIQUE INDEX UNIQ_77E0ED588E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBF2AF943 FOREIGN KEY (parent_comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CAB69C8EF FOREIGN KEY (written_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F247EE14 FOREIGN KEY (sent_from_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3E89D3ED FOREIGN KEY (sent_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE viewed_ad ADD CONSTRAINT FK_D1F245194F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE viewed_ad ADD CONSTRAINT FK_D1F24519A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_ad ADD CONSTRAINT FK_308EED44F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE saved_ad ADD CONSTRAINT FK_308EED4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58B09F48DA FOREIGN KEY (boost_id) REFERENCES boost (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED588E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CBF2AF943');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CAB69C8EF');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F247EE14');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3E89D3ED');
        $this->addSql('ALTER TABLE viewed_ad DROP FOREIGN KEY FK_D1F24519A76ED395');
        $this->addSql('ALTER TABLE saved_ad DROP FOREIGN KEY FK_308EED4A76ED395');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1B03A8386');
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FB03A8386');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58B03A8386');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58B09F48DA');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F93CB796C');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D312469DE2');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED588E962C16');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4F34D596');
        $this->addSql('ALTER TABLE viewed_ad DROP FOREIGN KEY FK_D1F245194F34D596');
        $this->addSql('ALTER TABLE saved_ad DROP FOREIGN KEY FK_308EED44F34D596');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE viewed_ad');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE boost');
        $this->addSql('DROP TABLE saved_ad');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE ad');
    }
}
