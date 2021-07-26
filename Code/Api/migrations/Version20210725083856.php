<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725083856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_drink DROP FOREIGN KEY FK_EF7CBB8936AA4BB4');
        $this->addSql('ALTER TABLE user_drink DROP FOREIGN KEY FK_EF7CBB89A76ED395');
        $this->addSql('ALTER TABLE user_drink ADD id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE drink_id drink_id INT UNSIGNED DEFAULT NULL, CHANGE user_id user_id INT UNSIGNED DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user_drink ADD CONSTRAINT FK_EF7CBB8936AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id)');
        $this->addSql('ALTER TABLE user_drink ADD CONSTRAINT FK_EF7CBB89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_drink MODIFY id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE user_drink DROP FOREIGN KEY FK_EF7CBB89A76ED395');
        $this->addSql('ALTER TABLE user_drink DROP FOREIGN KEY FK_EF7CBB8936AA4BB4');
        $this->addSql('ALTER TABLE user_drink DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_drink DROP id, CHANGE user_id user_id INT UNSIGNED NOT NULL, CHANGE drink_id drink_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE user_drink ADD CONSTRAINT FK_EF7CBB89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_drink ADD CONSTRAINT FK_EF7CBB8936AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_drink ADD PRIMARY KEY (drink_id, user_id)');
    }
}
