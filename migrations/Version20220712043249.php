<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220712043249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assign DROP FOREIGN KEY FK_7222A9A1961DBFB3');
        $this->addSql('ALTER TABLE assign DROP FOREIGN KEY FK_7222A9A19D86650F');
        $this->addSql('DROP INDEX IDX_7222A9A19D86650F ON assign');
        $this->addSql('DROP INDEX IDX_7222A9A1961DBFB3 ON assign');
        $this->addSql('ALTER TABLE assign ADD user_id INT DEFAULT NULL, ADD equipment_id INT DEFAULT NULL, DROP user_id_id, DROP equipment_id_id');
        $this->addSql('ALTER TABLE assign ADD CONSTRAINT FK_7222A9A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE assign ADD CONSTRAINT FK_7222A9A1517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('CREATE INDEX IDX_7222A9A1A76ED395 ON assign (user_id)');
        $this->addSql('CREATE INDEX IDX_7222A9A1517FE9FE ON assign (equipment_id)');
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D5839777D11E');
        $this->addSql('DROP INDEX IDX_D338D5839777D11E ON equipment');
        $this->addSql('ALTER TABLE equipment CHANGE category_id_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D58312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D338D58312469DE2 ON equipment (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assign DROP FOREIGN KEY FK_7222A9A1A76ED395');
        $this->addSql('ALTER TABLE assign DROP FOREIGN KEY FK_7222A9A1517FE9FE');
        $this->addSql('DROP INDEX IDX_7222A9A1A76ED395 ON assign');
        $this->addSql('DROP INDEX IDX_7222A9A1517FE9FE ON assign');
        $this->addSql('ALTER TABLE assign ADD user_id_id INT DEFAULT NULL, ADD equipment_id_id INT DEFAULT NULL, DROP user_id, DROP equipment_id');
        $this->addSql('ALTER TABLE assign ADD CONSTRAINT FK_7222A9A1961DBFB3 FOREIGN KEY (equipment_id_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE assign ADD CONSTRAINT FK_7222A9A19D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7222A9A19D86650F ON assign (user_id_id)');
        $this->addSql('CREATE INDEX IDX_7222A9A1961DBFB3 ON assign (equipment_id_id)');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D58312469DE2');
        $this->addSql('DROP INDEX IDX_D338D58312469DE2 ON equipment');
        $this->addSql('ALTER TABLE equipment CHANGE serial_number serial_number VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE status status VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE category_id category_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D5839777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D338D5839777D11E ON equipment (category_id_id)');
        $this->addSql('ALTER TABLE role CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
