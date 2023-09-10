<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910223607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experience CHANGE what role VARCHAR(255) NOT NULL, ADD generic_summary LONGTEXT NOT NULL AFTER role');
        $this->addSql('SET foreign_key_checks = 0;');
        $this->addSql('ALTER TABLE hobby CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('SET foreign_key_checks = 1;');
    }
    
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experience DROP generic_summary, CHANGE role what LONGTEXT NOT NULL');
        $this->addSql('SET foreign_key_checks = 0;');
        $this->addSql('ALTER TABLE hobby CHANGE id id INT NOT NULL');
        $this->addSql('SET foreign_key_checks = 1;');
    }
}
