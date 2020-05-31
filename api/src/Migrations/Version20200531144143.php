<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200531144143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("
    create table public.notes
        (
      id serial primary key,
      title varchar (255) not null,
      text text not null,
      user_id int not null,
      created timestamp(0),
      updated timestamp(0),
      constraint notes_users_fk foreign key (user_id) references public.users(id) on update cascade on delete cascade 
        );
        ");

    }

    public function down(Schema $schema) : void
    {
        $this->addSql("drop table if exists public.notes");

    }
}
