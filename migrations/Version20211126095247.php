<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211126095247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chapitre (id INT AUTO_INCREMENT NOT NULL, nom_chapitre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, reponse_exact VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_chapitre (question_id INT NOT NULL, chapitre_id INT NOT NULL, INDEX IDX_EF6A00291E27F6BF (question_id), INDEX IDX_EF6A00291FBEEF7B (chapitre_id), PRIMARY KEY(question_id, chapitre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, id_question_id INT NOT NULL, id_user_id INT DEFAULT NULL, reponse VARCHAR(255) NOT NULL, question_1 VARCHAR(255) NOT NULL, question_2 VARCHAR(255) NOT NULL, question_3 VARCHAR(255) NOT NULL, question_4 VARCHAR(255) NOT NULL, INDEX IDX_5FB6DEC76353B48 (id_question_id), UNIQUE INDEX UNIQ_5FB6DEC779F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question_chapitre ADD CONSTRAINT FK_EF6A00291E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_chapitre ADD CONSTRAINT FK_EF6A00291FBEEF7B FOREIGN KEY (chapitre_id) REFERENCES chapitre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC76353B48 FOREIGN KEY (id_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC779F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE nmd_details_tickets');
        $this->addSql('DROP TABLE nmd_incident_ticket');
        $this->addSql('DROP TABLE nmd_notifications');
        $this->addSql('ALTER TABLE nmd_bonus_malus_query CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE nmd_cbl ADD date_1_er_rdv_deb DATETIME DEFAULT NULL, ADD date_1_er_rdv_fin DATETIME DEFAULT NULL, DROP date_1er_rdv_deb, DROP date_1er_rdv_fin');
        $this->addSql('ALTER TABLE nmd_config_etl CHANGE etl_username etl_username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE nmd_remuneration_status CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_chapitre DROP FOREIGN KEY FK_EF6A00291FBEEF7B');
        $this->addSql('ALTER TABLE question_chapitre DROP FOREIGN KEY FK_EF6A00291E27F6BF');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC76353B48');
        $this->addSql('CREATE TABLE nmd_details_tickets (id INT NOT NULL, ticket_id INT DEFAULT NULL, author_id INT DEFAULT NULL, response_at DATETIME DEFAULT NULL, response_text LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, cpv_courtier VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE nmd_incident_ticket (id INT NOT NULL, title VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, picto VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, proposition LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, tips LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, is_autorized_update TINYINT(1) DEFAULT NULL, proposition_bo LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE nmd_notifications (id INT NOT NULL, vendeur_id INT DEFAULT NULL, responsable_id INT DEFAULT NULL, partner_id INT DEFAULT NULL, date_com_rm DATETIME DEFAULT NULL, nom_com_rm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, prenom_com_rm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email_com_rm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, tel_com_rm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nom_clt_rm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, prenom_clt_rm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, adresse_clt_rm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ref_cmd_rm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, raccordement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, eligibilite TINYINT(1) NOT NULL, pb_outils VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, explication LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, etat INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE chapitre');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_chapitre');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('ALTER TABLE nmd_bonus_malus_query MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE nmd_bonus_malus_query DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE nmd_bonus_malus_query CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE nmd_cbl ADD date_1er_rdv_deb DATETIME DEFAULT NULL, ADD date_1er_rdv_fin DATETIME DEFAULT NULL, DROP date_1_er_rdv_deb, DROP date_1_er_rdv_fin');
        $this->addSql('ALTER TABLE nmd_config_etl CHANGE etl_username etl_username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nmd_remuneration_status MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE nmd_remuneration_status DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE nmd_remuneration_status CHANGE id id INT NOT NULL');
    }
}
