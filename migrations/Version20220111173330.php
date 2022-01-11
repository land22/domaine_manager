<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220111173330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hebergement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, email_client VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domaine_name ADD hebergement_id INT DEFAULT NULL, ADD email_client VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE domaine_name ADD CONSTRAINT FK_759C020023BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
        $this->addSql('CREATE INDEX IDX_759C020023BB0F66 ON domaine_name (hebergement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domaine_name DROP FOREIGN KEY FK_759C020023BB0F66');
        $this->addSql('DROP TABLE hebergement');
        $this->addSql('DROP INDEX IDX_759C020023BB0F66 ON domaine_name');
        $this->addSql('ALTER TABLE domaine_name DROP hebergement_id, DROP email_client');
    }
}
