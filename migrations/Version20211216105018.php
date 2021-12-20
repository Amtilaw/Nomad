<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216105018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C24262871C15D5C');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C24262871C15D5C FOREIGN KEY (id_formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nmd_config_etl CHANGE etl_username etl_username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC3536353B48');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC3536353B48 FOREIGN KEY (id_question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E2FF709B6');
        $this->addSql('ALTER TABLE question CHANGE ordering ordering INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E2FF709B6 FOREIGN KEY (id_module_id) REFERENCES module (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C24262871C15D5C');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C24262871C15D5C FOREIGN KEY (id_formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE nmd_config_etl CHANGE etl_username etl_username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC3536353B48');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC3536353B48 FOREIGN KEY (id_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E2FF709B6');
        $this->addSql('ALTER TABLE question CHANGE ordering ordering INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E2FF709B6 FOREIGN KEY (id_module_id) REFERENCES module (id)');
    }
}
