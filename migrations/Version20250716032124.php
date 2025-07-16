<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250716032124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE label ADD user_id INT DEFAULT NULL, ADD category_id INT NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD keywords VARCHAR(255) DEFAULT NULL, ADD slug VARCHAR(255) NOT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD content LONGTEXT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD published_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', DROP country, DROP sector, DROP latitude, DROP longitude, CHANGE certified is_published TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE label ADD CONSTRAINT FK_EA750E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE label ADD CONSTRAINT FK_EA750E812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_EA750E8C53D045F ON label (image)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EA750E8A76ED395 ON label (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EA750E812469DE2 ON label (category_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE label DROP FOREIGN KEY FK_EA750E8A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE label DROP FOREIGN KEY FK_EA750E812469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_EA750E8C53D045F ON label
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EA750E8A76ED395 ON label
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EA750E812469DE2 ON label
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE label ADD country VARCHAR(255) NOT NULL, ADD sector VARCHAR(255) NOT NULL, ADD latitude DOUBLE PRECISION NOT NULL, ADD longitude DOUBLE PRECISION NOT NULL, DROP user_id, DROP category_id, DROP description, DROP keywords, DROP slug, DROP image, DROP content, DROP created_at, DROP updated_at, DROP published_at, CHANGE is_published certified TINYINT(1) NOT NULL
        SQL);
    }
}
