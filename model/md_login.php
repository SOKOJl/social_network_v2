<?php
	//Если пользователь нажал кнопку Войти для авторизации
	if(isset($_POST['submit'])){
		if(isset($_POST['login'])&&isset($_POST['password'])&&!empty($_POST['login'])&&!empty($_POST['password'])){
			$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			$login = mysqli_real_escape_string($db,trim($_POST['login']));
			$password = mysqli_real_escape_string($db,trim($_POST['password']));
			$sql = "SELECT user_id, type_user FROM users WHERE login='$login' AND password=SHA('$password') AND moderation=1";
			$data = mysqli_query($db,$sql);
			@mysqli_close($db);
			if(mysqli_num_rows($data) == 1){
				//Вход в приложение прошел успешно, присваиваем значения идентификатора пользователя и его типа пользователя (type_user)
				//переменным сессии и куки и переадресуем пользователя на главную страницу
				$row = mysqli_fetch_array($data);
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['type_user'] = $row['type_user'];
				setcookie ('user_id', $row['user_id'], time()+(60 * 60 * 24 * 30)); //Срок действия куки истекает через 30 дней
				setcookie ('type_user', $row['type_user'], time()+(60 * 60 * 24 * 30)); //Срок действия куки истекает через 30 дней
				
				//Когда пользователь прошел авторизацию перенаправляем его на его страничку
				// $page_id = берем из запроса в БД и передаем в GET запросе
				$home_url='http://'.$_SERVER['HTTP_HOST'].'/index.php/page?page_id='.$row['user_id']; //Если скрипт вложен в папку, то добавить  
																		  //    .dirname($_SERVER['PHP_SELF']).
				header('Location: '.$home_url);
			}
			else
				$msg[] = '<p id="msg_errors">Не существует такой комбинации Логина и Пароля!</p>';
		}
		else
			$msg[] = '<p id="msg_errors">Заполните поля Логин и Пароль!</p>';

	}
?>