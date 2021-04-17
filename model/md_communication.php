<?php
	//Обрабатываем GET-переменную
	//
	// 1) Если переменной $_GET['dialog'] не существует -> выводим только список существующих диалогов
	// 2) Если переменная $_GET['dialog'] задана -> Проверяем существование такого диалога. (Да?)-> Результат в $dialog
	// 							 												 				 -> 
	// 3) Если переменная $_GET['dialog_add'] задана (в ней хранится id пользователя
	//    с кторым мы хотим началь диалог)
	// -> Ищем диалог с этим пользователем
	// -> Если существует, то меняем $_GET['dialog_add'] на $_GET['dialog']=id
	// -> Иначе -> Создаем новый диалог и $_GET['dialog_add'] на $_GET['dialog']=id

	if (isset($_GET['dialog_add'])) {
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$user_id=$_SESSION['user_id'];
		$listen=$_GET['dialog_add'];
		
		//Ищем диалог с этим пользователем
		$sql="SELECT id FROM dialogs WHERE ((user1=$user_id) AND (user2=$listen) OR (user1=$listen) AND (user2=$user_id))";
		$data=mysqli_query($db,$sql);

		//Если существует, то меняем $_GET['dialog_add'] на $_GET['dialog']=id
		//!!!Изменил логику работы. Ничего менять н надо. Теперь одновременно и 
		//создается диалог и начинается общение
		
		if (mysqli_num_rows($data) == 1) {
			$row=mysqli_fetch_array($data);
			$_GET['dialog'] = $row['id'];
		}
		

		//Иначе -> Создаем новый диалог и $_GET['dialog_add'] на $_GET['dialog']=id
		else {
			//Добавляем новый диалог в БД
			$sql="INSERT INTO dialogs (user1, user2) VALUES ($user_id, $listen)";
			$data=mysqli_query($db,$sql);

			//Получаем ID нового диалога
			$sql="SELECT id FROM dialogs WHERE (user1=$user_id) AND (user2=$listen)";
			$data=mysqli_query($db,$sql);
			$row=mysqli_fetch_array($data);
			$_GET['dialog'] = $row['id'];
		}
		mysqli_close($db);	
	}

	//Если нажата ссылка удалить переписку с пользователем
	if (isset($_GET['delet_msg'])&&isset($_GET['dialog'])) {
		$dialog=$_GET['dialog'];
		$user_id=$_SESSION['user_id'];
		
		//Проставляем vis_* = 0 для своих сообщений
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$sql="UPDATE communication SET vis_sender=0  WHERE dialog_id=$dialog AND sender=$user_id";
		$data=mysqli_query($db,$sql);
		$sql="UPDATE communication SET vis_listen=0  WHERE dialog_id=$dialog AND listen=$user_id";
		$data=mysqli_query($db,$sql);

		//Удаляем из БД сообщения где vis_sender и vis_listen одновременно = 0
		$sql="DELETE FROM communication WHERE vis_sender=0 AND vis_listen=0";
		$data=mysqli_query($db,$sql);
		@mysqli_query($db,'COMMIT;');
		@mysqli_close($db);
	}

	//Если нажата кнопка удаления диалога
	if (isset($_GET['del_dialog'])) {
		$dialog=$_GET['del_dialog'];
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$sql="DELETE FROM communication WHERE dialog_id=$dialog";
		$data=mysqli_query($db,$sql);
		$sql="DELETE FROM dialogs WHERE id=$dialog";
		$data=mysqli_query($db,$sql);
		@mysqli_query($db,'COMMIT;');
		@mysqli_close($db);
	}

	//Если нажата кнопка отправить сообщение
	if (isset($_POST['send_message'])) {
		if (! empty($_GET['dialog'])) {
			if (! empty($_POST['write_message_text'])) {
				$sender=$_SESSION['user_id'];
				$dialog=$_GET['dialog'];
				$message=$_POST['write_message_text'];

				//Все условия проверили. Заносим сообщение пользователя в БД
				$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
				$sql="SELECT * FROM dialogs WHERE id=$dialog";
				$data=mysqli_query($db,$sql);
				$row=mysqli_fetch_array($data);
				if ($row['user1'] == $sender) {$listen=$row['user2'];} else  {$listen=$row['user1'];};
				$sql="INSERT INTO communication (sender, listen, dialog_id, message, datetime_msg) VALUES ($sender, $listen, $dialog, '$message', NOW());";
				$data=mysqli_query($db,$sql);
				mysqli_close($db);
			}
		}
		else {
			//По идее это условие никогда не выполнится и потом можно его удалить
			echo 'Сначала выберите пользователя, которому хотите отправить сообщение';
		}
	}

	function list_dialogs() {
		$user_id=$_SESSION['user_id'];
		$new_msg=new_msg();
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

		//Ищем диалоги с участием авторизированного пользователя
		//и выводим список пользователей, с которомы у нас открыт диалог
		$sql="SELECT * FROM dialogs WHERE ((user1=$user_id) OR (user2=$user_id))";
		$data=mysqli_query($db,$sql);
		while ($row=@mysqli_fetch_array($data)) {
			$dialog=$row['id'];
			if ($row['user1'] == $user_id) {$listen=$row['user2'];} else  {$listen=$row['user1'];};
			$sql="SELECT first_name, last_name FROM users WHERE user_id=$listen";
			$vremenna=mysqli_query($db,$sql);
			$row_vremenna=@mysqli_fetch_array($vremenna);
			$name_friend=$row_vremenna['first_name'].' '.$row_vremenna['last_name'];
			if ($row['id'] == $_GET['dialog']) {
				echo '<p class="selected_user_dialog"><a href="/index.php/communication?dialog='.$dialog.'">'.$name_friend.'</a> | ';
				echo '<a href="/index.php/communication?del_dialog='.$dialog.'"><img class="del_dialog" src="/images/bomb.gif" alt="Удалить диалог" title="Удалить диалог"></a>';
				echo '</p>';
			}
			else {
				if ($new_msg) {
					$new_msg_user=new_msg($listen);
					if ($new_msg_user) {
						echo '<p><a href="/index.php/communication?dialog='.$dialog.'">'.$name_friend.'<sup class="msg_send"> +'.$new_msg_user.'</sup></a> | ';
						echo '<a href="/index.php/communication?del_dialog='.$dialog.'"><img class="del_dialog" src="/images/bomb.gif" alt="Удалить диалог" title="Удалить диалог"></a>';
						echo '</p>';						
					}
					else {
						echo '<p><a href="/index.php/communication?dialog='.$dialog.'">'.$name_friend.'</a> | ';
						echo '<a href="/index.php/communication?del_dialog='.$dialog.'"><img class="del_dialog" src="/images/bomb.gif" alt="Удалить диалог" title="Удалить диалог"></a>';
						echo '</p>';
					}
				}
				else {
					echo '<p><a href="/index.php/communication?dialog='.$dialog.'">'.$name_friend.'</a> | ';
					echo '<a href="/index.php/communication?del_dialog='.$dialog.'"><img class="del_dialog" src="/images/bomb.gif" alt="Удалить диалог" title="Удалить диалог"></a>';
					echo '</p>';
				}
			}
		}
		mysqli_close($db);
	} //function echo_dialog()

	//Выводим переписку диалога
	function echo_dialog() {
		//Выводим переписку диалога (номер диалога получаем из GET)
		//В поисковом запросе проверяем участвует ли текущий пользователь в переписке
		if ($dialog = $_GET['dialog']){
			$user_id=$_SESSION['user_id'];
			$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			$sql="SELECT * FROM dialogs WHERE ((id=$dialog) AND ((user1=$user_id) OR (user2=$user_id)))";
			$data=mysqli_query($db,$sql);
			if (mysqli_num_rows($data) == 1) {
				$row=mysqli_fetch_array($data);
				$sender = $_SESSION['user_id'];
				if ($row['user1'] == $user_id) {$listen=$row['user2'];} else  {$listen=$row['user1'];};

				//Отмечаем все сообщения текущего диалога как прочитанные
				$sql="UPDATE communication SET read_msg=1 WHERE listen=$sender AND dialog_id=$dialog";
				$data=mysqli_query($db,$sql);
				$data=mysqli_query($db,'COMMIT;');

				$sql="SELECT * FROM communication WHERE dialog_id=$dialog";
				$data=mysqli_query($db,$sql);
				mysqli_close($db);
				$pred=0;
				$no_msg=1;
				while ($row=@mysqli_fetch_array($data)) {
					$no_msg=0;
					if ($row['sender'] == $sender) {
						if ($row['vis_sender']) {
							if ($pred == $sender) {
								echo "<p>".$row['message']."</p>";
							}
							else {
								if ($pred != 0) {echo "</div>";}
								$pred = $sender; 
	
								echo '<div class="message1"><p>'.$row['message'].'</p>';
	
							}
						}
					}
					else {
						if ($row['vis_listen']) {
							if ($pred == $listen) {
								echo "<p>".$row['message']."</p>";
							}
							else {
								if ($pred != 0) {echo "</div>";}
								$pred = $listen; 
	
								echo '<div class="message2"><p>'.$row['message'].'</p>';
	
							}
						}
					}
				}
							if (!$no_msg) echo '</div>';
			}
		}		
	} //function echo_dialog()
?>