<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331183212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, 
                                   name VARCHAR(255) NOT NULL, 
                                   slug VARCHAR(255) NOT NULL, 
                                   description VARCHAR(255) DEFAULT NULL, 
                                   imageUrl LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', 
                                   isMega TINYINT(1) DEFAULT NULL, 
                                   updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', 
                                   created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', 
                                   PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, 
                                  name VARCHAR(255) NOT NULL, 
                                  slug VARCHAR(255) NOT NULL, 
                                  description VARCHAR(255) NOT NULL, 
                                  more_description LONGTEXT DEFAULT NULL, 
                                  additional_info LONGTEXT DEFAULT NULL, 
                                  stock INT DEFAULT NULL, 
                                  solde_price INT DEFAULT NULL, 
                                  regular_price INT NOT NULL, 
                                  imageUrls JSON NOT NULL COMMENT '(DC2Type:json)', 
                                  brand VARCHAR(255) DEFAULT NULL, 
                                  isAvailable TINYINT(1) DEFAULT NULL, 
                                  isBestSeller TINYINT(1) DEFAULT NULL, 
                                  isNewArrival TINYINT(1) DEFAULT NULL, 
                                  isFeatured TINYINT(1) DEFAULT NULL, 
                                  isSpecialOffer TINYINT(1) DEFAULT NULL, 
                                  created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', 
                                  updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', 
                                  PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_category (product_id INT NOT NULL, 
                                           category_id INT NOT NULL, 
                                           INDEX IDX_CDFC73564584665A (product_id), 
                                           INDEX IDX_CDFC735612469DE2 (category_id), 
                                           PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_category
        SQL);
    }
}
