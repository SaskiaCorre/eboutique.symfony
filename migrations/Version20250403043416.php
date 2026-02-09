<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403043416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE collections (id INT AUTO_INCREMENT NOT NULL, 
                                      title VARCHAR(255) NOT NULL, 
                                      description VARCHAR(255) NOT NULL, 
                                      button_text VARCHAR(255) NOT NULL, 
                                      button_link VARCHAR(255) NOT NULL, 
                                      imageUrl VARCHAR(255) NOT NULL, 
                                      updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', 
                                      created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', 
                                      isMega TINYINT(1) DEFAULT NULL, 
                                      PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE collections
        SQL);
    }
}
