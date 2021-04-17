<?php
	//
	//Скрипт регистрации посетителей в таблице session
	//
	// Получаем уникальный id сессии
	function online_user() {
		$user_id=$_SESSION['user_id'];
		$id_session = session_id(); 
		// Устанавливаем соединение с базой данных 
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		// Проверяем, присутствует ли такой id в базе данных 
		$sql = "SELECT * FROM session 
		        WHERE id_session = '$id_session'"; 
		$data = mysqli_query($db,$sql); 
		if(!$data) exit("<p>Ошибка в запросе к таблице сессий</p>"); 
		// Если сессия с таким номером уже существует, 
		// значит пользователь online - обновляем время его 
		// последнего посещения 
		if(mysqli_num_rows($data)) {
			$sql = "UPDATE session SET putdate = NOW(), 
			                             user = '$user_id' 
			          WHERE id_session = '$id_session'"; 
			mysqli_query($db,$sql);
		} 
		// Иначе, если такого номера нет - посетитель только что 
		// вошёл - помещаем в таблицу нового посетителя 
		else 
		{ 
		  $sql = "INSERT INTO session 
		            VALUES('$id_session', NOW(), '$user_id')"; 
		  if(!mysqli_query($db,$sql)) 
		  { 
		    echo "<p>Ошибка при добавлении пользователя</p>"; 
		    exit(); 
		  } 
		} 
		// Будем считать, что пользователи, которые отсутствовали 
		// в течении 20 минут - покинули ресурс - удаляем их 
		// id_session из базы данных 
		$sql = "DELETE FROM session 
		          WHERE putdate < NOW() -  INTERVAL '20' MINUTE"; 
		mysqli_query($db,$sql);
		mysqli_close($db);
	}
	//
	//Конец скрипта регистрации посетителей в таблице session
	//

	//
	//Функция выдает количество непрочитанных сообщений
	//Если определена переменная $sender, то выдает количество 
	//непрочитанных сообщений от конкретного человека
	//
	function new_msg($sender=-1) {
		$user_id=$_SESSION['user_id'];
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		if ($sender == -1) {
			$sql = "SELECT * FROM communication WHERE listen=$user_id AND read_msg=0";
		}
		else {
			$sql = "SELECT * FROM communication WHERE sender=$sender AND listen=$user_id AND read_msg=0";
		}
		$data = mysqli_query($db,$sql);
		@mysqli_close($db);
		$result=0;
		if (mysqli_num_rows($data)) {
			$result = mysqli_num_rows($data);
		}
		return $result;
	}

	//
	//Функция выдает количество поступивших заявок на дружбу
	//
	function new_friend() {
		$user_id=$_SESSION['user_id'];
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$sql = "SELECT user_id FROM friends WHERE friend_id=$user_id AND send_invitation=1 AND confirmed=0";
		$data = mysqli_query($db,$sql);
		@mysqli_close($db);
		$result=0;
		if (mysqli_num_rows($data)) {
			$result = mysqli_num_rows($data);
		}
		return $result;
	}

	//Функция вставки навигационных ссылок (главного меню) с учетом авторизации
	function nav_menu() {
		if (! isset($_SESSION['user_id'])) {
			echo '<a id="main_menu_effect" href="/index.php">Главная</a>';
	 	} 
		if (isset($_SESSION['user_id'])) {
			echo '<a id="main_menu_effect" href="/index.php">Моя страничка</a>';
			$new_friend=new_friend();
			if ($new_friend) {
				echo ' | <a id="main_menu_effect" href="/index.php/friends/friendsend">Мои друзья <sup class="msg_send"> +'.$new_friend.'</sup></a>';
			}
			else {
				echo ' | <a id="main_menu_effect" href="/index.php/friends/myfriends">Мои друзья </a>';
			}
			$new_msg=new_msg();
			if ($new_msg) {
				echo ' | <a id="main_menu_effect" href="/index.php/communication">Сообщения <sup class="msg_send"> +'.$new_msg.'</sup></a>';
			}
			else {
				echo ' | <a id="main_menu_effect" href="/index.php/communication">Сообщения </a>';
			}
	 		echo ' | <a id="main_menu_effect" href="/index.php/search">Поиск </a>';
	 		echo ' | <a id="main_menu_effect" href="/index.php/logout">ВЫХОД</a>';
	 	}
	} //function nav_menu()

	//Функция вывода сообщений об ошибках, если такие присутствуют
	function msg_error($msg) {
		if (! empty($msg)) {
			foreach ($msg as $message) {
				echo '<p id="msg_errors">'.$message.'</p>';
			}
		}
		if (! empty($msg_sucses)) {
			foreach ($msg_sucses as $message) {
				echo '<p id="msg_sucses">'.$message.'</p>';
			}
		}
	} //function msg_error()

	//Находим зарегиструровавшегося пользователя в БД и заносим информацию о нем в $row[]
	function select_user() {
		//Подключаем БД
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		if (!isset($_GET['page_id'])) {
			$_GET['page_id'] = $_SESSION['user_id'];
		}
		$page_id = mysqli_real_escape_string($db,trim($_GET['page_id']));

		//Ищем в БД пользователя по номеру странички (она соответствует идентификатору пользователя)
		//не учитываем при поиске пользователей с неактивированной страничкой
		$sql = "SELECT moderation, first_name, last_name, birthday, city, status, about_me, avatarka FROM users WHERE user_id=$page_id AND moderation=1";

		//Ищем в БД пользователя по номеру странички (она соответствует идентификатору пользователя)
		//Если зашел Admin, то учитываем при поиске пользователей с неактивированной страничкой
		if ($_SESSION['type_user'] == 'admin') {
			$sql = "SELECT moderation, first_name, last_name, birthday, city, status, about_me, avatarka FROM users WHERE user_id=$page_id";
		};
		$data = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($data);
		@mysqli_close($db);
		return $row;
	} //function select_user()

	//Функция добавляет ссылки на добавление/удаление в друзья на страничку
	function set_friend_links($page_id) {
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$user_id = $_SESSION['user_id'];
		if ($user_id !== $page_id) {
			$sql = "SELECT id, send_invitation, confirmed FROM friends WHERE user_id=$user_id AND friend_id=$page_id";
			$data = mysqli_query($db,$sql);
			@mysqli_close($db);
			if ($row1 = mysqli_fetch_array($data)) {
				if ($row1['confirmed']) {
					echo 'Пользователь у Вас в друзьях<br>';
					echo '<a href="/index.php?page_id='.$page_id.'&deinvite='.$page_id.'">Удалить из друзей</a>';
				}
				if (($row1['send_invitation']) && (! $row1['confirmed'])) {
					echo '<a href="/index.php?page_id='.$page_id.'&deinvite='.$page_id.'">Отозвать заявку на дружбу</a>';
				}
				if ((! $row1['send_invitation']) && (! $row1['confirmed'])) {
					echo '<a href="/index.php?page_id='.$page_id.'&upinvite='.$page_id.'">Подтвердить дружбу</a> | ';
					echo '<a href="/index.php?page_id='.$page_id.'&deinvite='.$page_id.'">Отклонить дружбу</a>';
				}
			}
			else {
				echo '<a href="/index.php?page_id='.$page_id.'&invite='.$page_id.'">Добавить в друзья</a>';
			}
		}
	} //function set_friend_links()
?>