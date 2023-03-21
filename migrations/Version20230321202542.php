<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321202542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurant_weekday_timetable (id INT AUTO_INCREMENT NOT NULL, id_weekday_id INT NOT NULL, open_am TIME NOT NULL, close_am TIME NOT NULL, open_pm TIME NOT NULL, close_pm TIME NOT NULL, UNIQUE INDEX UNIQ_B4647DBAEDDBE4EE (id_weekday_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurant_weekday_timetable ADD CONSTRAINT FK_B4647DBAEDDBE4EE FOREIGN KEY (id_weekday_id) REFERENCES restaurant_weekday (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant_weekday_timetable DROP FOREIGN KEY FK_B4647DBAEDDBE4EE');
        $this->addSql('DROP TABLE restaurant_weekday_timetable');
    }
}
