<?php

//Задаем максимальный размер загружаемого файла
define('MAX_FILE_SIZE', '32768'); //32Кб

//Задаем пути к директориям
define('DIR_IMAGES', 'images/');

//Задаем значения для подключения к Базе Данных
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'pechenki');

// // сейчас выставлен сервер локальной машины
// $dblocation = "localhost";
// // Имя базы данных, на хостинге или локальной машине
// $dbname = "dbase";
// // Имя пользователя базы данных
// $dbuser = "root";
// // и его пароль
// $dbpasswd = "";

// // Устанавливаем соединение с базой данных
// $dbcnx = @mysql_connect($dblocation,$dbuser,$dbpasswd);
// if (!$dbcnx) {
// exit( "<P>В настоящий момент сервер базы данных не доступен, поэтому корректное отображение страницы невозможно.</P>" );
// }
// // Выбираем базу данных
// if (! @mysql_select_db($dbname,$dbcnx) ) {
// exit( "<P>В настоящий момент база данных не доступна, поэтому корректное отображение страницы невозможно.</P>" );
// }

// // Устанавливаем кодировку соединения
// @mysql_query("SET NAMES 'cp1251'");
?>