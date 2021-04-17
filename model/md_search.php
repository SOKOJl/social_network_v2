<?php
	//Если Администратор нажал кнопку вывести всех пользователей ожидающих модерации
	if ((isset($_GET['new_people']))&&($_SESSION['type_user'] == 'admin')) {
		require_once('config.php');
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$sql = "SELECT user_id, moderation, last_name, first_name, city, avatarka
				FROM users 
				WHERE moderation=0";
		$data = mysqli_query($db,$sql);
		while ($row = mysqli_fetch_array($data)) {
		
		//Выводим людей ожидающих модерацию
			if (empty($row['avatarka'])) {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.DIR_IMAGES.'no_photo.jpg';
			}
			else {
				$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.$row['avatarka'];
			}
			include '/template/tm_search_moderation.php';
		}
		@mysqli_close($db);
	}

	//Обработчик нажатия кнопки "Поиск"
	if ((isset($_GET['search']))&&(isset($_SESSION['user_id']))) {
		require_once('config.php');
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$search_first_name = mysqli_real_escape_string($db,trim($_GET['search_first_name']));
		$search_last_name = mysqli_real_escape_string($db,trim($_GET['search_last_name']));
		$search_city = mysqli_real_escape_string($db,trim($_GET['search_city']));
		$search_year_old = mysqli_real_escape_string($db,trim($_GET['search_year_old']));
		if ((empty($search_first_name))&&(empty($search_last_name))&&(empty($search_city))&&(empty($search_year_old))) {
			$msg[] = 'Не заданы критерии для поиска. Укажите хотя бы одно.';
			echo 'Не заданы критерии для поиска. Укажите хотя бы одно.';
		}
		
		if (empty($msg)) {
			$str = '(user_id > 0)';
			if (!(empty($search_first_name))) {$str = $str."AND first_name='$search_first_name'";};
			if (!(empty($search_last_name))) {$str = $str."AND last_name='$search_last_name'";};
			if (!(empty($search_city))) {$str = $str."AND city='$search_city'";};
			if (!(empty($search_year_old))) {
				$date1 = date((date('Y') - $search_year_old - 1) . '-m-d');
				$date2 = date((date('Y') - $search_year_old) . '-m-d');
				$str = $str." AND (birthday BETWEEN '$date1' AND '$date2') ";
			};
			$sql = "SELECT user_id, moderation, first_name, last_name, city, avatarka
					FROM users 
					WHERE ".$str;
			if ($_SESSION['type_user'] != 'admin') {
				$sql = $sql.'AND moderation=1';
			}
			$data = mysqli_query($db,$sql);
			ob_start();
				while ($row = mysqli_fetch_array($data)) {
					if ($_SESSION['user_id'] !== $row['user_id']) {
					
						//Выводим список пользователей, отвечающих критериям поиска
						if (empty($row['avatarka'])) {
							$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.DIR_IMAGES.'no_photo.jpg';
						}
						else {
							$dir = 'http://'.$_SERVER['HTTP_HOST'].'/'.$row['avatarka'];
						}
						include '/template/tm_search_poisk.php';
					}
				}
			$content = ob_get_clean();
			@mysqli_close($db);
		}
	}
?>