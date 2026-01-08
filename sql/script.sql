drop table if exists purchase;
drop table if exists photo;
drop table if exists ad;
drop table if exists category;
drop table if exists delivery_mode;
drop table if exists user;

create table user (
    id int auto_increment primary key,
    email varchar(255) unique not null,
    password varchar(255) not null,
    is_admin tinyint(1) default 0
);

create table category (
    id int auto_increment primary key,
    name varchar(100) unique not null
);

create table delivery_mode (
    id int auto_increment primary key,
    name varchar(50) not null
);

create table ad (
    id int auto_increment primary key,
    title varchar(30) not null,
    description varchar(200) not null,
    price decimal(10,2) default 0.00,
    sold tinyint(1) default 0,
    user_id int not null,
    category_id int not null,
    delivery_mode_id int not null,
    created_at datetime default current_timestamp,
    constraint fk_ad_user foreign key (user_id) references user(id) on delete cascade,
    constraint fk_ad_category foreign key (category_id) references category(id),
    constraint kf_ad_delivery foreign key (delivery_mode_id) references delivery_mode(id)
);

create table photo (
    id int auto_increment primary key,
    filename varchar(255) not null,
    ad_id int not null,
    constraint fk_photo_ad foreign key (ad_id) references ad(id) on delete cascade
);

create table purchase (
    id int auto_increment primary key,
    ad_id int null,
    ad_title varchar(30) not null,
    ad_price decimal(10,2) not null,
    buyer_id int not null,
    seller_id int not null,
    delivery_mode_id int not null,
    received tinyint(1) default 0,
    created_at datetime default current_timestamp,
    constraint fk_purchase_ad foreign key (ad_id) references ad(id) on delete set null,
    constraint fk_purchase_delivery foreign key (delivery_mode_id) references delivery_mode(id)
);

insert into delivery_mode (name)
values ('Envoi postal'), ('Remise en main propre');

insert into user (email, password, is_admin)
values ('contact@ebazar.com', '$2y$10$Ke38qwevS1l2IzgWnfdabeINz/TFdDDqTOXQCxunj2ob06n0mfFua', 1);

insert into category (name)
values ('immobilier'), ('véhicule'), ('mode'), ('électronique');