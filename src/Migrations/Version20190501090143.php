<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190501090143 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_roles (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, role VARCHAR(50) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_54FCD59FA76ED395 (user_id), INDEX IDX_54FCD59F57698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (code VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departments (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departments_users (department_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_82BCE56DAE80F5DF (department_id), INDEX IDX_82BCE56DA76ED395 (user_id), PRIMARY KEY(department_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json_array)\', password_change_required TINYINT(1) NOT NULL, password_change_token VARCHAR(255) DEFAULT NULL, password_change_date DATETIME DEFAULT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E986383B10 (avatar_id), UNIQUE INDEX user_unique (username_canonical), UNIQUE INDEX token_unique (password_change_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contacts (id INT AUTO_INCREMENT NOT NULL, profile_id INT NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_33401573CCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profiles (user_id INT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birth_day DATE DEFAULT NULL, biography LONGTEXT DEFAULT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE files (id INT AUTO_INCREMENT NOT NULL, file_system VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, size VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59F57698A6A FOREIGN KEY (role) REFERENCES roles (code)');
        $this->addSql('ALTER TABLE departments_users ADD CONSTRAINT FK_82BCE56DAE80F5DF FOREIGN KEY (department_id) REFERENCES departments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE departments_users ADD CONSTRAINT FK_82BCE56DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E986383B10 FOREIGN KEY (avatar_id) REFERENCES files (id)');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_33401573CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profiles (user_id)');
        $this->addSql('ALTER TABLE profiles ADD CONSTRAINT FK_8B308530A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59F57698A6A');
        $this->addSql('ALTER TABLE departments_users DROP FOREIGN KEY FK_82BCE56DAE80F5DF');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE departments_users DROP FOREIGN KEY FK_82BCE56DA76ED395');
        $this->addSql('ALTER TABLE profiles DROP FOREIGN KEY FK_8B308530A76ED395');
        $this->addSql('ALTER TABLE contacts DROP FOREIGN KEY FK_33401573CCFA12B8');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E986383B10');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE departments');
        $this->addSql('DROP TABLE departments_users');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE profiles');
        $this->addSql('DROP TABLE files');
    }
}
