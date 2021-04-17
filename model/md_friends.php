<?php
	//Выводим список друзей пользователя
	function myfriends() {
		//Делаем запрос в БД на наших друзей
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$user_id=$_SESSION['user_id'];
		$sql = "SELECT * FROM users WHERE user_id IN (SELECT friend_id FROM friends WHERE user_id=$user_id AND confirmed=1)
				AND moderation=1";
		$data = mysqli_query($db,$sql);
		mysqli_close($db);
		while ($row = mysqli_fetch_array($data)) {
			if (empty($row['avatarka'])) {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.DIR_IMAGES.'no_photo.jpg';
			}
			else {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.$row['avatarka'];
			}
			include 'template/tm_friends_my.php';
		}
	}

	//Выводим друзей пользователя, которые сейчас в онлайне
	function friendonline() {
		//Делаем запрос в БД на наших друзей
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$user_id=$_SESSION['user_id'];
		$sql = "SELECT * FROM users WHERE (user_id 
				IN (SELECT user FROM session)
				AND (user_id IN (SELECT friend_id FROM friends WHERE user_id=$user_id AND confirmed=1))
				AND user_id <> $user_id)
				AND moderation=1";
		$data = mysqli_query($db,$sql);
		mysqli_close($db);

		while ($row = mysqli_fetch_array($data)) {
			if (empty($row['avatarka'])) {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.DIR_IMAGES.'no_photo.jpg';
			}
			else {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.$row['avatarka'];
			}
			include 'template/tm_friends_online.php';
		}
	}
	
	//Выводим заявки на добавление в друзья
	function friendsend() {
		//Делаем запрос в БД на наших друзей
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$user_id=$_SESSION['user_id'];
		$sql = "SELECT * FROM users WHERE user_id 
				IN (SELECT friend_id FROM friends WHERE user_id=$user_id AND confirmed=0)
				AND moderation=1";
		$data = mysqli_query($db,$sql);
		mysqli_close($db);
		while ($row = mysqli_fetch_array($data)) {
			if (empty($row['avatarka'])) {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.DIR_IMAGES.'no_photo.jpg';
			}
			else {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.$row['avatarka'];
			}
			include 'template/tm_friends_sent.php';
		}
	}
?>