<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301205545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solde DROP FOREIGN KEY FK_669183672FA70B96');
        $this->addSql('ALTER TABLE solde ADD CONSTRAINT FK_669183672FA70B96 FOREIGN KEY (id_terrain_id) REFERENCES terrain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B198DE13AC');
        $this->addSql('DROP INDEX IDX_C87653B198DE13AC ON terrain');
        $this->addSql('ALTER TABLE terrain DROP partenaire_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solde DROP FOREIGN KEY FK_669183672FA70B96');
        $this->addSql('ALTER TABLE solde ADD CONSTRAINT FK_669183672FA70B96 FOREIGN KEY (id_terrain_id) REFERENCES terrain (id)');
        $this->addSql('ALTER TABLE terrain ADD partenaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B198DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('CREATE INDEX IDX_C87653B198DE13AC ON terrain (partenaire_id)');
    }
}
