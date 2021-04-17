<?php
	if (!isset($_SESSION['user_id'])){
		//Если в сессии нет данных о пользователе, пробуем получить их из куки
		if(isset($_COOKIE['user_id'])){
			$_SESSION['user_id'] = $_COOKIE['user_id'];
			$_SESSION['type_user'] = $_COOKIE['type_user'];
		}
	}
	else
	{
		if (!isset($_GET['page_id'])) {
			$_GET['page_id'] = $_SESSION['user_id'];
		}
		online_user();
	}
	
	function logout() {
		//Если пользователь вошел в приложение - удаление переменных сессии для того, чтобы он вышел из приложения	
		session_start();//Почему-то если отсюда убрать повторное открытие сессии (первый раз в index.php) то выход не осуществляется и далее скрипт разваливается
		if (isset($_SESSION['user_id'])){

			//Удаление переменных сессии путем присвоения суперглобальному массиву $_SESSION значения пустого массива
			$_SESSION = array();
			//Удаление куки, содержащего идентификатор сессии, путем установки
			//срока его действия на час (3600 секунд) ранее текущего времени
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(),'', time()-3600,'/');
			}
			//Удаление куки, содержащих идентификатор пользователя и тип пользователя,
			//путем установки срока их действия на час (3600 секунд) ранее текущего времени
			setcookie('user_id','', time()-3600,'/');
			setcookie('type_user','', time()-3600,'/');
			
			//Удаление сессии
			session_destroy();
			$home_url='http://'.$_SERVER['HTTP_HOST'].'/index.php'; //Если скрипт вложен в папку, то добавить  
															//    .dirname($_SERVER['PHP_SELF']).
			header('Location: '.$home_url);
			exit;
		}
	}
?>