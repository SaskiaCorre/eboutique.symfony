<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331190203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, 
            website_name VARCHAR(255) NOT NULL, 
            description VARCHAR(255) NOT NULL, 
            currency VARCHAR(255) NOT NULL, 
            taxe_rate VARCHAR(255) DEFAULT NULL, 
            logo VARCHAR(255) NOT NULL, 
            street VARCHAR(255) NOT NULL, 
            city VARCHAR(255) NOT NULL, 
            code_postal VARCHAR(255) NOT NULL, 
            state VARCHAR(255) NOT NULL, 
            updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', 
            created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', 
            phone VARCHAR(255) DEFAULT NULL, 
            facebookLink VARCHAR(255) DEFAULT NULL, 
            youtubeLink VARCHAR(255) DEFAULT NULL, 
            instagramLink VARCHAR(255) DEFAULT NULL, 
            email VARCHAR(255) DEFAULT NULL, 
            copyright VARCHAR(255) DEFAULT NULL, 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE setting
        SQL);
    }
}
 