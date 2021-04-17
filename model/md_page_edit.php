<?php
	$row = select_user();
	//Если нажата кнопка сохранить изменения, сохраняем их в БД
	if(isset($_POST['page_edit'])){
		$msg = array();
		require_once('config.php');
		if (!empty($_FILES['avatarka']['name'])) {
			if (!(($_FILES['avatarka']['type'] == 'image/gif')||($_FILES['avatarka']['type'] == 'image/jpeg')||($_FILES['avatarka']['type'] == 'image/jpg')||($_FILES['avatarka']['type'] == 'image/png'))) {
				$msg[] = 'Разрешено загружать только картинки';
			};
			if ($_FILES['avatarka']['size'] <= 0) {
				$msg[] = 'Ошибка загрузки файла';
			};

			//
			// Отключим пока что контроль размера загружаемого файла
			//
			// if ($_FILES['avatarka']['size'] >= MAX_FILE_SIZE) {
			// 	$msg[] = 'Максимальный размер файла 32 Кб';
			// };
			
			if (empty($msg)) {
				$avatarka = DIR_IMAGES.$_GET['page_id'].'_'.time().'.'.@end(explode('/', $_FILES['avatarka']['type']));
				move_uploaded_file($_FILES['avatarka']['tmp_name'],$avatarka);
				$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
				$page_id = mysqli_real_escape_string($db,trim($_GET['page_id']));
				$sql = "UPDATE users SET avatarka='$avatarka' WHERE user_id=$page_id";
				$data = mysqli_query($db,$sql);
				@mysqli_close($db);		
			}		
		};
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$status = mysqli_real_escape_string($db,trim($_POST['status']));
		$first_name = mysqli_real_escape_string($db,trim($_POST['first_name']));
		$last_name = mysqli_real_escape_string($db,trim($_POST['last_name']));
		$birthday = mysqli_real_escape_string($db,trim($_POST['birthday']));
		$city = mysqli_real_escape_string($db,trim($_POST['city']));
		$about_me = mysqli_real_escape_string($db,trim($_POST['about_me']));
		$page_id = mysqli_real_escape_string($db,trim($_GET['page_id']));
		if (isset($_POST['moderation'])) {
			$moderation = $_POST['moderation'];
			$sql = "UPDATE users SET moderation=$moderation, status='$status', first_name='$first_name', last_name='$last_name', birthday='$birthday', city='$city',
			 about_me='$about_me' WHERE user_id=$page_id";
		}
		else {
			$sql = "UPDATE users SET status='$status', first_name='$first_name', last_name='$last_name', birthday='$birthday', city='$city', about_me='$about_me'
					WHERE user_id=$page_id";
		};
		$data = mysqli_query($db,$sql);
		@mysqli_query($db,'COMMIT;');
		@mysqli_close($db);
		if (empty($msg)) {
			echo '<p id="msg_sucses">Профиль успешно изменен.</p>';
		}
		else {
				foreach ($msg as $message) {
					echo '<p id="msg_errors">'.$message.'</p>';
				}
			}
	}
	
	//Если нажата ссылка удалить фото, то удаляем с БД и файл
	if ((isset($_GET['ft']))&&(!empty($_GET['ft']))) {
		$dir = './'.$_GET['ft'];
		unlink($dir);
		require_once('config.php');
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$page_id = mysqli_real_escape_string($db,trim($_GET['page_id']));
		$sql = "UPDATE users SET avatarka=NULL WHERE user_id=$page_id";
		$data = mysqli_query($db,$sql);
		@mysqli_close($db);
		echo '<p id="msg_sucses">Аватарка удалена</p>';
	}
?>