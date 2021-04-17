<?php
	$row = select_user();
	if (!$row['moderation']) {
		$msg[] = '<p id="msg_errors">Пользователь не одобрен!!!</p>';
	}
	if (($row['moderation'])||($_SESSION['type_user'] == 'admin')) {	
		//Если пользователь авторизирован и от него пришел запрос на добавление человека в друзья,
		//то добавляем в таблицу запись об отправке запроса на дружбу
		if (isset($_GET['invite'])) {
			$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			$invite = mysqli_real_escape_string($db,trim($_GET['invite'])); //id кого приглашаем
			$user_id = $_SESSION['user_id'];								//id кто приглашает
			$sql = "SELECT * FROM friends WHERE (user_id=$user_id AND friend_id=$invite) OR (user_id=$invite AND friend_id=$user_id)";
			$data = mysqli_query($db,$sql);
			if (($user_id !== $invite) && (mysqli_num_rows($data) == 0)) { //Если пользователь не приглашает сам себя и
																			//такой пары user_id и friend_id нет в БД
				$sql = "INSERT INTO friends (user_id, friend_id, send_invitation) VALUES ($user_id, $invite, 1)";
				$data = mysqli_query($db,$sql);
				$sql = "INSERT INTO friends (user_id, friend_id) VALUES ($invite, $user_id)";
				$data = mysqli_query($db,$sql);
			}
			@mysqli_close($db);
		}
		
		//Если пользователь авторизирован и подтвердил дружбу,
		//то заносим в таблицу обоюдную дружбу
		if (isset($_GET['upinvite'])) {
			$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			$upinvite = mysqli_real_escape_string($db,trim($_GET['upinvite'])); //id кого приглашаем
			$user_id = $_SESSION['user_id'];								//id кто приглашает
			$sql = "SELECT * FROM friends WHERE user_id=$user_id AND friend_id=$upinvite";
			$data = mysqli_query($db,$sql);
			$row1 = mysqli_fetch_array($data);
			if (($user_id !== $upinvite) && (mysqli_num_rows($data) !== 0) && (! $row1['send_invitation']) && (! $row1['confirmed'])) {
			//Если пользователь не приглашает сам себя и такая пара user_id и friend_id присутствует в БД и действительно подтверждается принятие дружбы
			//то апдейтим 2 записи в БД
				$sql = "UPDATE friends SET send_invitation=1, confirmed=1 WHERE user_id=$user_id AND friend_id=$upinvite";
				$data = mysqli_query($db,$sql);
				$sql = "UPDATE friends SET send_invitation=0, confirmed=1 WHERE user_id=$upinvite AND friend_id=$user_id";
				$data = mysqli_query($db,$sql);				
			}
			@mysqli_close($db);
		}
		
		//Если пользователь авторизирован и от него пришел запрос отозвать запрос на дружбу,
		//то удаляем в таблице запись об отправке запроса на дружбу
		//
		//Решено объединить запрос на удаление человека из друзей и отзыв запроса на дружбу
		if (isset($_GET['deinvite'])) {
			$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			$deinvite = mysqli_real_escape_string($db,trim($_GET['deinvite'])); //id кого приглашали
			$user_id = $_SESSION['user_id'];								//id кто приглашал
			$sql = "SELECT * FROM friends WHERE (user_id=$user_id AND friend_id=$deinvite) OR (user_id=$deinvite AND friend_id=$user_id)";
			$data = mysqli_query($db,$sql);
			if (mysqli_num_rows($data) !== 0) { //Если данный пользователь действительно посылал запрос на дружбу ИЛИ уже дружат
												//то удаляем этот запрос
				$sql = "DELETE FROM friends WHERE (user_id=$user_id AND friend_id=$deinvite) OR (user_id=$deinvite AND friend_id=$user_id)";
				$data = mysqli_query($db,$sql);
			}
			@mysqli_close($db);
		}
	}
	
	//Функция вставляет аватарку
	function avatarka($row) {
		if (empty($row['avatarka'])) {
			$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.DIR_IMAGES.'no_photo.jpg';
			echo '<img id="avatarka" src="'.$dir.'" alt="Моя фотография" title="Моя фотография">';
		}
		else {
			$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.$row['avatarka'];
			echo '<img id="avatarka" src="'.$dir.'" alt="Моя фотография" title="Моя фотография">';
		}
	} //function avatarka()

	//Функция добавляет ссылки на добавление/удаление в друзья на страничку
	function set_under_img_links() {
		$page_id = $_GET['page_id'];
		if ($_GET['page_id'] != $_SESSION['user_id']) {
			//В ссылку добавляем переменную dialog, которая будет заменяться в файле "communication.php" на номер диалога 
			echo '<a href="/index.php/communication?dialog=0&dialog_add='.$_GET['page_id'].'">Написать сообщение</a><br>';
		}
		$user_id = $_SESSION['user_id'];
		if(($user_id == $page_id)||($_SESSION['type_user'] == 'admin')){ //$page_id задан в файле select_user.php
			echo '<a href="/index.php/page_edit?page_id='.$_GET['page_id'].'&edit=1">Редактировать</a><br>';
		}
		set_friend_links($page_id);
	} //function set_under_img_links()

	//Выводим друзей текущего пользователя, кроме самого себя
	function set_friends_list() {
		if (isset($_GET['page_id'])) {
			if ($_GET['page_id'] !== $_SESSION['user_id']) {
				$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
				$user_id=$_SESSION['user_id'];
				$page_id=$_GET['page_id'];
				$sql = "SELECT * FROM users WHERE user_id IN (SELECT friend_id FROM friends WHERE user_id=$page_id AND confirmed=1) AND moderation=1";
				$data = mysqli_query($db,$sql);
				echo '<br><br><fieldset>';
				echo '<legend>Друзья пользователя</legend>';

				//Странно. Нельзя использовать переменную $row.
				while ($row2 = mysqli_fetch_array($data)) {
					$user=$row2['user_id'];
					$dat=$row2['first_name'].' '.$row2['last_name'];
					echo '<p><a href="index.php?page_id='.$user.'">'.$dat.'</a></p>';
				}
				echo '</fieldset>';
				mysqli_close($db);
			}
		}
	} //function set_friends_list()
?>