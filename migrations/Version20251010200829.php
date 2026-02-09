<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251010200829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE category CHANGE isMega is_mega TINYINT(1) NOT NULL DEFAULT 0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE collections CHANGE isMega is_mega TINYINT(1) NOT NULL DEFAULT 0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product 
                CHANGE isAvailable is_available TINYINT(1) NOT NULL DEFAULT 0,
                CHANGE isBestSeller is_best_seller TINYINT(1) NOT NULL DEFAULT 0,
                CHANGE isNewArrival is_new_arrival TINYINT(1) NOT NULL DEFAULT 0,
                CHANGE isFeatured is_featured TINYINT(1) NOT NULL DEFAULT 0,
                CHANGE isSpecialOffer is_special_offer TINYINT(1) NOT NULL DEFAULT 0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE isVerified is_verified TINYINT(1) NOT NULL DEFAULT 0
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE category CHANGE isMega is_mega TINYINT(1) NOT NULL DEFAULT 0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE collections CHANGE isMega is_mega TINYINT(1) NOT NULL DEFAULT 0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product 
                CHANGE isAvailable is_available TINYINT(1) NOT NULL DEFAULT 0,
                CHANGE isBestSeller is_best_seller TINYINT(1) NOT NULL DEFAULT 0,
                CHANGE isNewArrival is_new_arrival TINYINT(1) NOT NULL DEFAULT 0,
                CHANGE isFeatured is_featured TINYINT(1) NOT NULL DEFAULT 0,
                CHANGE isSpecialOffer is_special_offer TINYINT(1) NOT NULL DEFAULT 0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE isVerified is_verified TINYINT(1) NOT NULL DEFAULT 0
        SQL);
    }
}
