create database todo_app;
grant all on todo_app.* to admindb@localhost identified by 'password';

use todo_app;

create table tasks(
	id int not null auto_increment primary key,
	seq int not null,
	type enum('notyet','done','deleted') default 'notyet',
	title text,
	plan Date,
	created datetime,
	modified datetime,
	KEY type(type),
	KEY seq(seq)
);

insert into tasks(seq,type,title,plan,created,modified) values
	(1,'notyet','テスト1',now(),now(),now()),
	(2,'notyet','テスト2',now(),now(),now()),
	(3,'done','テスト3',now(),now(),now());	