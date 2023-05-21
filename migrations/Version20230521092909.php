<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521092909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant_weekday_timetable DROP FOREIGN KEY FK_B4647DBAEDDBE4EE');
        $this->addSql('DROP INDEX UNIQ_B4647DBAEDDBE4EE ON restaurant_weekday_timetable');
        $this->addSql('ALTER TABLE restaurant_weekday_timetable CHANGE id_weekday_id weekday_id INT NOT NULL');
        $this->addSql('ALTER TABLE restaurant_weekday_timetable ADD CONSTRAINT FK_B4647DBA48516439 FOREIGN KEY (weekday_id) REFERENCES restaurant_weekday (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4647DBA48516439 ON restaurant_weekday_timetable (weekday_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant_weekday_timetable DROP FOREIGN KEY FK_B4647DBA48516439');
        $this->addSql('DROP INDEX UNIQ_B4647DBA48516439 ON restaurant_weekday_timetable');
        $this->addSql('ALTER TABLE restaurant_weekday_timetable CHANGE weekday_id id_weekday_id INT NOT NULL');
        $this->addSql('ALTER TABLE restaurant_weekday_timetable ADD CONSTRAINT FK_B4647DBAEDDBE4EE FOREIGN KEY (id_weekday_id) REFERENCES restaurant_weekday (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4647DBAEDDBE4EE ON restaurant_weekday_timetable (id_weekday_id)');
    }
}
