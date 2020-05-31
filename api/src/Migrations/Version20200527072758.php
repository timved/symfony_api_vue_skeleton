<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527072758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("create table public.federal_districts (id serial not null,title varchar(255) not null,code varchar(32) not null, PRIMARY KEY(id))");
        $this->addSql("create unique index federal_districts_code_uindex on public.federal_districts (code)");

        $this->addSql("insert into public.federal_districts (id, title, code) VALUES
                    (1,'Москва','01'),(2,'Санкт-Петербург','02'),(3,'Центральный ФО','03'),
                    (4,'Южный ФО','04'),(8,'Уральский ФО','08'),(6,'Дальневосточный ФО','06'),
                    (7,'Сибирский ФО','07'),(5,'Северо-Западный ФО','05'),
                    (9,'Приволжский ФО','09'),(10,'Северо-Кавказский ФО','10')");

        $this->addSql("create table public.subjects (id serial not null, federal_district_id int not null constraint subjects_federal_districts_id_fk references public.federal_districts on update cascade on delete restrict, title varchar(255) not null, code varchar(32) not null, PRIMARY KEY(id))");
        $this->addSql("create unique index subjects_code_uindex on public.subjects (code)");
        $this->addSql("
        insert into public.subjects 
        (id, federal_district_id, title, code) VALUES
            (1,4,'Республика Адыгея','01'),
            (2,7,'Республика Алтай','04'),
            (3,9,'Республика Башкортостан','02'),
            (4,7,'Республика Бурятия','03'),
            (5,10,'Республика Дагестан','05'),
            (6,10,'Республика Ингушетия','06'),
            (7,10,'Кабардино-Балкарская республика','07'),
            (8,4,'Республика Калмыкия','08'),
            (9,10,'Карачаево-Черкесская республика','09'),
            (10,5,'Республика Карелия','10'),
            (11,5,'Республика Коми','11'),
            (12,4,'Республика Крым','91'),
            (13,9,'Республика Марий Эл','12'),
            (14,9,'Республика Мордовия','13'),
            (15,6,'Республика Саха (Якутия)','14'),
            (16,10,'Республика Северная Осетия — Алания','15'),
            (17,9,'Республика Татарстан','16'),
            (18,7,'Республика Тыва','17'),
            (19,9,'Удмуртская республика','18'),
            (20,7,'Республика Хакасия','19'),
            (21,10,'Чеченская республика','20'),
            (22,9,'Чувашская республика','21'),
            (23,7,'Алтайский край','22'),
            (24,7,'Забайкальский край','75'),
            (25,6,'Камчатский край','41'),
            (26,4,'Краснодарский край','23'),
            (27,7,'Красноярский край','24'),
            (28,9,'Пермский край','59'),
            (29,6,'Приморский край','25'),
            (30,10,'Ставропольский край','26'),
            (31,6,'Хабаровский край','27'),
            (32,6,'Амурская область','28'),
            (33,5,'Архангельская область','29'),
            (34,4,'Астраханская область','30'),
            (35,3,'Белгородская область','31'),
            (36,3,'Брянская область','32'),
            (37,3,'Владимирская область','33'),
            (38,4,'Волгоградская область','34'),
            (39,5,'Вологодская область','35'),
            (40,3,'Воронежская область','36'),
            (41,3,'Ивановская область','37'),
            (42,7,'Иркутская область','38'),
            (43,5,'Калининградская область','39'),
            (44,3,'Калужская область','40'),
            (45,7,'Кемеровская область','42'),
            (46,9,'Кировская область','43'),
            (47,8,'Курганская область','45'),
            (48,3,'Курская область','46'),
            (49,5,'Ленинградская область','47'),
            (50,3,'Липецкая область','48'),
            (51,6,'Магаданская область','49'),
            (52,3,'Московская область','50'),
            (53,5,'Мурманская область','51'),
            (54,9,'Нижегородская область','52'),
            (55,5,'Новгородская область','53'),
            (56,7,'Новосибирская область','54'),
            (57,7,'Омская область','55'),
            (58,9,'Оренбургская область','56'),
            (59,3,'Орловская область','57'),
            (60,9,'Пензенская область','58'),
            (61,5,'Псковская область','60'),
            (62,4,'Ростовская область','61'),
            (63,3,'Рязанская область','62'),
            (64,9,'Самарская область','63'),
            (65,9,'Саратовская область','64'),
            (66,6,'Сахалинская область','65'),
            (67,8,'Свердловская область','66'),
            (68,3,'Смоленская область','67'),
            (69,3,'Тамбовская область','68'),
            (70,3,'Тверская область','69'),
            (71,7,'Томская область','70'),
            (72,3,'Тульская область','71'),
            (73,8,'Тюменская область','72'),
            (74,9,'Ульяновская область','73'),
            (75,8,'Челябинская область','74'),
            (76,3,'Ярославская область','76'),
            (77,1,'Москва','77'),
            (78,2,'Санкт-Петербург','78'),
            (79,4,'Севастополь','92'),
            (80,6,'Еврейская автономная область','79'),
            (81,5,'Ненецкий автономный округ','83'),
            (82,8,'Ханты-Мансийский автономный округ - Югра','86'),
            (83,6,'Чукотский автономный округ','87'),
            (84,8,'Ямало-Ненецкий автономный округ','89'),
            (87,3,'Костромская область','44')")
        ;

        $this->addSql("
    create table public.users
        (
      id serial primary key,
      login varchar (255) not null,
      password varchar(255),
      fio varchar(255), 
      email varchar(255) not null UNIQUE,
      roles json,
      subject_id int,
      created timestamp(0),
      updated timestamp(0),
      constraint users_subject_fk foreign key (subject_id) references public.subjects(id)
        );
        ");

      $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
      $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
      $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql("drop table if exists public.users");
        $this->addSql("drop table if exists public.subjects");
        $this->addSql("drop table if exists public.federal_districts");
    }
}
