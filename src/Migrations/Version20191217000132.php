<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191217000132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F93CB796C');
        $this->addSql('DROP INDEX UNIQ_B6BD307F93CB796C ON message');
        $this->addSql('ALTER TABLE message ADD file_name VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, DROP file_id');
        $this->addSql('DROP INDEX UNIQ_8C9F36105E237E06 ON file');
        $this->addSql('ALTER TABLE file DROP name, DROP context, DROP original_name');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file ADD name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD context VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD original_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C9F36105E237E06 ON file (name)');
        $this->addSql('ALTER TABLE message ADD file_id INT DEFAULT NULL, DROP file_name, DROP updated_at');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6BD307F93CB796C ON message (file_id)');
    }
}
