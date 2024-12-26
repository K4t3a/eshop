
create table if not exists catalog (
    id int primary key auto_increment,
    title varchar(255) not null,
    author varchar(255) not null,
    price int not null,
    pubyear int not null
);

create table if not exists orders (
    id int primary key auto_increment,
    order_id varchar(20) unique not null,
    customer varchar(255) not null,
    email varchar(255) not null,
    phone varchar(20) not null,
    address varchar(255) not null,
    created timestamp default current_timestamp
);

create table if not exists admins (
    id int primary key auto_increment,
    login varchar(36) unique not null,
    password varchar(255) not null,
    email varchar(255) not null,
    created timestamp default current_timestamp
);

create table if not exists ordered_items (
    id int primary key auto_increment,
    quantity int not null,
    order_id varchar(20) not null,
    item_id int not null,
    foreign key (order_id) references orders (order_id) on delete cascade,
    foreign key (item_id) references catalog (id) on delete cascade
);


delimiter //

create procedure if not exists sp_add_item_to_catalog(
    in title varchar(255),
    in author varchar(255),
    in price int,
    in pubyear int
)
begin
    insert into catalog (title, author, price, pubyear)
    values (title, author, price, pubyear);
end //

create procedure if not exists sp_get_catalog()
begin
    select id, title, author, price, pubyear from catalog;
end //

create procedure if not exists sp_get_items_for_basket(in ids text)
begin
    select id, title, author, price, pubyear
    from catalog
    where find_in_set(id, ids);
end //

create procedure if not exists sp_save_order(
    in order_id varchar(20),
    in customer varchar(255),
    in email varchar(255),
    in phone varchar(20),
    in address varchar(255)
)
begin
    insert into orders (order_id, customer, email, phone, address)
    values (order_id, customer, email, phone, address);
end //

create procedure if not exists sp_save_ordered_items(
    in order_id varchar(20),
    in item_id int,
    in quantity int
)
begin
    insert into ordered_items (order_id, item_id, quantity)
    values (order_id, item_id, quantity);
end //

create procedure if not exists sp_get_orders()
begin
    select id, order_id, customer, email, phone, address, unix_timestamp(created) as created
    from orders;
end //

create procedure if not exists sp_get_ordered_items(in order_ids text)
begin
    select ordered_items.order_id, ordered_items.quantity, catalog.title, catalog.author, catalog.price, catalog.pubyear
    from ordered_items
    inner join catalog on ordered_items.item_id = catalog.id
    where find_in_set(ordered_items.order_id, order_ids);
end //

create procedure if not exists sp_save_admin(
    in login varchar(36),
    in password varchar(255),
    in email varchar(255)
)
begin
    insert into admins (login, password, email)
    values (login, password, email);
end //

create procedure if not exists sp_get_admin(in login varchar(36))
begin
    select id, login, password, email, unix_timestamp(created) as created
    from admins
    where admins.login = login;
end //

delimiter ;


call sp_save_admin('root', '$2y$12$ln8.AI63S0abD0.hNHS8mOP38kJigjY6eCURIvRwBwzcQLtqn3YJi', 'root@gmail.com');

call sp_add_item_to_catalog('Война и мир', 'Лев Толстой', 2999, 1869);
call sp_add_item_to_catalog('Преступление и наказание', 'Фёдор Достоевский', 1999, 1866);
call sp_add_item_to_catalog('Мастер и Маргарита', 'Михаил Булгаков', 2499, 1940);
call sp_add_item_to_catalog('Евгений Онегин', 'Александр Пушкин', 1599, 1833);
call sp_add_item_to_catalog('1984', 'Джордж Оруэлл', 2199, 1949);
call sp_add_item_to_catalog('Анна Каренина', 'Лев Толстой', 2799, 1877);

call sp_save_order('setupid1', 'peter', 'peter@gmail.com', '+81234567890', 'far far away');
call sp_save_ordered_items('setupid1', 1, 1);
call sp_save_ordered_items('setupid1', 4, 1);

call sp_save_order('setupid2', 'daria', 'daria@gmail.com', '+80987654321', 'behind you');
call sp_save_ordered_items('setupid2', 2, 1);
call sp_save_ordered_items('setupid2', 3, 1);
call sp_save_ordered_items('setupid2', 5, 1);
call sp_save_ordered_items('setupid2', 6, 1);

drop procedure if exists sp_add_item_to_catalog;
drop procedure if exists sp_get_catalog;
drop procedure if exists sp_get_items_for_basket;
drop procedure if exists sp_save_order;
drop procedure if exists sp_save_ordered_items;
drop procedure if exists sp_get_orders;
drop procedure if exists sp_get_ordered_items;
drop procedure if exists sp_save_admin;
drop procedure if exists sp_get_admin;

drop table if exists ordered_items;
drop table if exists admins;
drop table if exists orders;
drop table if exists catalog;
