<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211010204554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abbreviation (short VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(short)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE abbreviation_translation (id INT AUTO_INCREMENT NOT NULL, abbreviation_short VARCHAR(255) NOT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_3A8ED9BF875CA2BF (abbreviation_short), INDEX abbreviation_translation_idx (abbreviation_short, locale, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(7) DEFAULT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_person (company_id VARCHAR(255) NOT NULL, person_id VARCHAR(3) NOT NULL, INDEX IDX_943B503979B1AD6 (company_id), INDEX IDX_943B503217BBB47 (person_id), PRIMARY KEY(company_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id VARCHAR(255) NOT NULL, company_id VARCHAR(255) NOT NULL, employee_id VARCHAR(3) DEFAULT NULL, trainee_id VARCHAR(3) DEFAULT NULL, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, what LONGTEXT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_590C103979B1AD6 (company_id), INDEX IDX_590C1038C03F15C (employee_id), INDEX IDX_590C10336C682D0 (trainee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience_translation (id INT AUTO_INCREMENT NOT NULL, experience_id VARCHAR(255) NOT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_76511B7746E90E27 (experience_id), INDEX experience_translation_idx (experience_id, locale, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hobby (id INT AUTO_INCREMENT NOT NULL, person_id VARCHAR(3) DEFAULT NULL, what VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_3964F337217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hobby_translation (id INT AUTO_INCREMENT NOT NULL, hobby_id INT NOT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_C0DAFBF4322B2123 (hobby_id), INDEX hobby_translation_idx (hobby_id, locale, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (code VARCHAR(2) NOT NULL, level VARCHAR(255) NOT NULL, meaning VARCHAR(255) DEFAULT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language_translation (id INT AUTO_INCREMENT NOT NULL, language_code VARCHAR(2) NOT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_D72F30DD451CDAD4 (language_code), INDEX language_translation_idx (language_code, locale, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id VARCHAR(3) NOT NULL, person_id VARCHAR(3) DEFAULT NULL, full_name VARCHAR(255) NOT NULL, position VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL, phone VARCHAR(17) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, marital_status VARCHAR(255) DEFAULT NULL, summary LONGTEXT DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(7) DEFAULT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_34DCD176217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_skill (person_id VARCHAR(3) NOT NULL, skill_name VARCHAR(255) NOT NULL, INDEX IDX_F20BFBB3217BBB47 (person_id), INDEX IDX_F20BFBB31962E2B4 (skill_name), PRIMARY KEY(person_id, skill_name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_language (person_id VARCHAR(3) NOT NULL, language_code VARCHAR(2) NOT NULL, INDEX IDX_409DD482217BBB47 (person_id), INDEX IDX_409DD482451CDAD4 (language_code), PRIMARY KEY(person_id, language_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_translation (id INT AUTO_INCREMENT NOT NULL, person_id VARCHAR(3) NOT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_D9821AE7217BBB47 (person_id), INDEX person_translation_idx (person_id, locale, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation (id INT AUTO_INCREMENT NOT NULL, experience_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_EAA5610E46E90E27 (experience_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation_translation (id INT AUTO_INCREMENT NOT NULL, realisation_id INT NOT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_72D6A797B685E551 (realisation_id), INDEX realisation_translation_idx (realisation_id, locale, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (name VARCHAR(255) NOT NULL, selectable TINYINT(1) NOT NULL, root_id VARCHAR(255) DEFAULT NULL, parent_id VARCHAR(255) DEFAULT NULL, lft INT NOT NULL, rgt INT NOT NULL, lvl INT NOT NULL, INDEX IDX_5E3DE47779066886 (root_id), INDEX IDX_5E3DE477727ACA70 (parent_id), PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill_translation (id INT AUTO_INCREMENT NOT NULL, skill_name VARCHAR(255) NOT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_A77DCA31962E2B4 (skill_name), INDEX skill_translation_idx (skill_name, locale, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, realisation_id INT NOT NULL, description LONGTEXT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_527EDB25B685E551 (realisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_translation (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_F21CE85D8DB60186 (task_id), INDEX task_translation_idx (task_id, locale, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology (version VARCHAR(15) NOT NULL, skill_id VARCHAR(255) NOT NULL, realisation_id INT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_F463524D5585C142 (skill_id), INDEX IDX_F463524DB685E551 (realisation_id), PRIMARY KEY(version, skill_id, realisation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abbreviation_translation ADD CONSTRAINT FK_3A8ED9BF875CA2BF FOREIGN KEY (abbreviation_short) REFERENCES abbreviation (short)');
        $this->addSql('ALTER TABLE company_person ADD CONSTRAINT FK_943B503979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_person ADD CONSTRAINT FK_943B503217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C1038C03F15C FOREIGN KEY (employee_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C10336C682D0 FOREIGN KEY (trainee_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE experience_translation ADD CONSTRAINT FK_76511B7746E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('ALTER TABLE hobby ADD CONSTRAINT FK_3964F337217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE hobby_translation ADD CONSTRAINT FK_C0DAFBF4322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id)');
        $this->addSql('ALTER TABLE language_translation ADD CONSTRAINT FK_D72F30DD451CDAD4 FOREIGN KEY (language_code) REFERENCES language (code)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_skill ADD CONSTRAINT FK_F20BFBB3217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_skill ADD CONSTRAINT FK_F20BFBB31962E2B4 FOREIGN KEY (skill_name) REFERENCES skill (name)');
        $this->addSql('ALTER TABLE person_language ADD CONSTRAINT FK_409DD482217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_language ADD CONSTRAINT FK_409DD482451CDAD4 FOREIGN KEY (language_code) REFERENCES language (code)');
        $this->addSql('ALTER TABLE person_translation ADD CONSTRAINT FK_D9821AE7217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610E46E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('ALTER TABLE realisation_translation ADD CONSTRAINT FK_72D6A797B685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id)');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE47779066886 FOREIGN KEY (root_id) REFERENCES skill (name) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE477727ACA70 FOREIGN KEY (parent_id) REFERENCES skill (name) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_translation ADD CONSTRAINT FK_A77DCA31962E2B4 FOREIGN KEY (skill_name) REFERENCES skill (name)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id)');
        $this->addSql('ALTER TABLE task_translation ADD CONSTRAINT FK_F21CE85D8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE technology ADD CONSTRAINT FK_F463524D5585C142 FOREIGN KEY (skill_id) REFERENCES skill (name)');
        $this->addSql('ALTER TABLE technology ADD CONSTRAINT FK_F463524DB685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abbreviation_translation DROP FOREIGN KEY FK_3A8ED9BF875CA2BF');
        $this->addSql('ALTER TABLE company_person DROP FOREIGN KEY FK_943B503979B1AD6');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103979B1AD6');
        $this->addSql('ALTER TABLE experience_translation DROP FOREIGN KEY FK_76511B7746E90E27');
        $this->addSql('ALTER TABLE realisation DROP FOREIGN KEY FK_EAA5610E46E90E27');
        $this->addSql('ALTER TABLE hobby_translation DROP FOREIGN KEY FK_C0DAFBF4322B2123');
        $this->addSql('ALTER TABLE language_translation DROP FOREIGN KEY FK_D72F30DD451CDAD4');
        $this->addSql('ALTER TABLE person_language DROP FOREIGN KEY FK_409DD482451CDAD4');
        $this->addSql('ALTER TABLE company_person DROP FOREIGN KEY FK_943B503217BBB47');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C1038C03F15C');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C10336C682D0');
        $this->addSql('ALTER TABLE hobby DROP FOREIGN KEY FK_3964F337217BBB47');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176217BBB47');
        $this->addSql('ALTER TABLE person_skill DROP FOREIGN KEY FK_F20BFBB3217BBB47');
        $this->addSql('ALTER TABLE person_language DROP FOREIGN KEY FK_409DD482217BBB47');
        $this->addSql('ALTER TABLE person_translation DROP FOREIGN KEY FK_D9821AE7217BBB47');
        $this->addSql('ALTER TABLE realisation_translation DROP FOREIGN KEY FK_72D6A797B685E551');
        $this->addSql('ALTER TABLE technology DROP FOREIGN KEY FK_F463524DB685E551');
        $this->addSql('ALTER TABLE person_skill DROP FOREIGN KEY FK_F20BFBB31962E2B4');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE47779066886');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE477727ACA70');
        $this->addSql('ALTER TABLE skill_translation DROP FOREIGN KEY FK_A77DCA31962E2B4');
        $this->addSql('ALTER TABLE technology DROP FOREIGN KEY FK_F463524D5585C142');
        $this->addSql('DROP TABLE abbreviation');
        $this->addSql('DROP TABLE abbreviation_translation');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_person');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE experience_translation');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE hobby_translation');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE language_translation');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_skill');
        $this->addSql('DROP TABLE person_language');
        $this->addSql('DROP TABLE person_translation');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE realisation_translation');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE skill_translation');
        $this->addSql('DROP TABLE technology');
    }
}
