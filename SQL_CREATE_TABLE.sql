
Создание Базы Данных
1) Создаем саму БД с именем "pexhenki"
2) Создаем все таблицы
3) Создаем администратора сайта


Создаем таблицу пользователей

CREATE TABLE users (
	user_id int (10) AUTO_INCREMENT,
	type_user enum ('admin', 'user') DEFAULT 'user',
	moderation BOOLEAN DEFAULT 0,
	login varchar (20) NOT NULL,
	password varchar (40) NOT NULL,
	first_name varchar (20) NOT NULL,
	last_name varchar (20) NOT NULL,
	birthday date NOT NULL,
	city varchar (20) NOT NULL,
	e_mail varchar (30),
	phone varchar (10),
	status text,
	about_me text,
	avatarka varchar (30),
	PRIMARY KEY (user_id)
	);

Добавляем администратора в таблицу users

INSERT INTO users (type_user, moderation, login, password, first_name, last_name, birthday, city, e_mail, phone) VALUES ('admin', 1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Administrator', 'Admin', '1990-01-01', 'Москва', 'admin@mail.ru', '9660428605');
	
Создаем таблицу друзей

CREATE TABLE friends (
	id int AUTO_INCREMENT,
	user_id int (10) NOT NULL,
	friend_id int (10) NOT NULL,
	send_invitation BOOLEAN DEFAULT 0,
	confirmed BOOLEAN DEFAULT 0,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES users (user_id),
	FOREIGN KEY (friend_id) REFERENCES users (user_id)
	);

Вставляем 1 дружбу

INSERT INTO friends (user_id, friend_id, send_invitation, confirmed) VALUES (3, 2, 1, 1);
INSERT INTO friends (user_id, friend_id, send_invitation, confirmed) VALUES (2, 3, 0, 1);

Создаем таблицу общения

CREATE TABLE communication (
	id int AUTO_INCREMENT,
	sender int (10) NOT NULL,
	listen int (10) NOT NULL,
	dialog_id int NOT NULL,
	message text NOT NULL,
	datetime_msg timestamp NOT NULL,
	vis_sender boolean DEFAULT 1 NOT NULL,
	vis_listen boolean DEFAULT 1 NOT NULL,
	read_msg boolean DEFAULT 0 NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (sender) REFERENCES users (user_id),
	FOREIGN KEY (listen) REFERENCES users (user_id)
);

Добавляем переписку в таблицу communication

INSERT INTO communication (sender, listen, dialog_id, message, datetime_msg) VALUES (3, 2, 1, 'Привет мой новый друг!', NOW());
INSERT INTO communication (sender, listen, dialog_id, message, datetime_msg) VALUES (2, 3, 1, 'Здорова!', NOW());
INSERT INTO communication (sender, listen, dialog_id, message, datetime_msg) VALUES (2, 3, 1, 'Как поживаешь?', NOW());
INSERT INTO communication (sender, listen, dialog_id, message, datetime_msg) VALUES (3, 2, 1, 'Спасибо, все хорошо. А ты?', NOW());
INSERT INTO communication (sender, listen, dialog_id, message, datetime_msg) VALUES (2, 3, 1, 'Да и я отлично. Спасибо.', NOW());
INSERT INTO communication (sender, listen, dialog_id, message, datetime_msg) VALUES (3, 2, 1, 'Ну и хорошо, что все хорошо.', NOW());

Создаем таблицу диалогов

Создаем таблицу общения

CREATE TABLE dialogs (
	id int AUTO_INCREMENT,
	user1 int (10) NOT NULL,
	user2 int (10) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (user1) REFERENCES users (user_id),
	FOREIGN KEY (user2) REFERENCES users (user_id)
);

Добавляем 1 открытый диалог в таблицу dialogs

INSERT INTO dialogs (user1, user2) VALUES (2, 3);

Создадим таблицу session в которой будем хранить уникальные идентификаторы сессии (SID), назначенные посетителям

CREATE TABLE session (
	id_session tinytext NOT NULL,
	putdate datetime NOT NULL default '0000-00-00 00:00:00',
	user int (10) NOT NULL
);