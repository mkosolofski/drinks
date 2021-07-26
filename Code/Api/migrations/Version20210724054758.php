<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210724054758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE configuration (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, value VARCHAR(255) NOT NULL, UNIQUE INDEX name_uidx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drink (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, caffeine INT UNSIGNED NOT NULL, UNIQUE INDEX name_uidx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_drink (drink_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, INDEX IDX_EF7CBB8936AA4BB4 (drink_id), INDEX IDX_EF7CBB89A76ED395 (user_id), PRIMARY KEY(drink_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX name_uidx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_drink ADD CONSTRAINT FK_EF7CBB8936AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_drink ADD CONSTRAINT FK_EF7CBB89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('
            INSERT INTO drink (`name`, `description`, `caffeine`) VALUES
                ("Monster Ultra Sunrise", "A refreshing orange beverage that has 75mg of caffeine per serving. Every can has two servings.", 75),
                ("Black Coffee", "The classic, the average 8oz. serving of black coffee has 95mg of caffeine.", 95),
                ("Americano", "Sometimes you need to water it down a bit... and in comes the americano with an average of 77mg. of caffeine per serving.", 77),
                ("Sugar free NOS", "Another orange delight without the sugar. It has 130 mg. per serving and each can has two servings.", 130),
                ("5 Hour Energy", "And amazing shot of get up and go! Each 2 fl. oz. container has 200mg of caffeine to get you going.", 200)
        ');

        $this->addSql('
            INSERT INTO configuration (`name`, `value`) VALUES ("max_caffeine_mg", "500")
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_drink DROP FOREIGN KEY FK_EF7CBB8936AA4BB4');
        $this->addSql('ALTER TABLE user_drink DROP FOREIGN KEY FK_EF7CBB89A76ED395');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE drink');
        $this->addSql('DROP TABLE user_drink');
        $this->addSql('DROP TABLE user');
    }
}
