<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614123034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tax_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tax (id INT NOT NULL, code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E81BA7677153098 ON tax (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E81BA765373C966 ON tax (country)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1392A5D877153098 ON voucher (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tax_id_seq CASCADE');
        $this->addSql('DROP TABLE tax');
        $this->addSql('DROP INDEX UNIQ_1392A5D877153098');
    }
}
