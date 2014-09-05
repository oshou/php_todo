CREATE DATABASE todo_app;
GRANT all ON todo_app.* to admindb@localhost identified by 'password';

use todo_app;

CREATE TABLE tasks(
	id int NOT NULL auto_increment primary key,
	seq int NOT NULL,
	type enum('notyet','done','deleted') default 'notyet',
	title text,
	plan Date,
	created datetime,
	modified datetime,
	KEY type(type),
	KEY seq(seq)
);

INSERT INTO tasks(seq,type,title,plan,created,modified) VALUES
	(1,'notyet','テスト1',now(),now(),now()),
	(2,'notyet','テスト2',now(),now(),now()),
	(3,'done','テスト3',now(),now(),now());	