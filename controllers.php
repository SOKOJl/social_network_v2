<?php

	function login() {
		require_once 'model/md_login.php';
		require_once 'template/tm_header.php';
		require_once 'template/tm_login.php';
		require_once 'template/tm_footer.php';
	}

	function communication() {
		require_once 'template/tm_header.php';
		require_once 'model/md_communication.php';
		require_once 'template/tm_communication.php';
		require_once 'template/tm_footer.php';
	}

	function friends($a) {
		require_once 'template/tm_header.php';
		require_once 'template/tm_friends_main_menu.php';
		require_once 'model/md_friends.php';
		switch ($a) {
			case 1: myfriends(); break;
			case 2: friendonline(); break;
			case 3: friendsend(); break;
		}
		require_once 'template/tm_footer.php';
	}

	function page() {
		require_once 'model/md_page.php';
		require_once 'template/tm_header.php';
		if (($row['moderation'])||($_SESSION['type_user'] == 'admin')) {
			require_once 'template/tm_page.php';
		}
		else {
			echo 'Пользователь не одобрен';
		}
		require_once 'template/tm_footer.php';
	}

	function page_edit() {
		require_once 'template/tm_header.php';
		require_once 'model/md_page_edit.php';
		if($_SESSION['type_user'] == 'admin'){
			require_once 'template/tm_page_edit.php';
		}
		else
		if($_SESSION['user_id'] == $_GET['page_id']){
			$row = select_user();
			if (!$row['moderation']) {
				echo '<p id="msg_errors">Пользователь не одобрен!!!</p>';
			} else {
				require_once 'template/tm_page_edit.php';
			}
		}
		else
			echo'<p id="msg_errors">Простите, но Вы можете редактировать только свою страничку</p>';
		require_once 'template/tm_footer.php';
	}

	function registration() {
		require_once 'model/md_registration.php';
		require_once 'template/tm_header.php';
		require_once 'template/tm_registration.php';
		require_once 'template/tm_footer.php';
	}

	function search() {
		require_once 'model/md_search.php';
		require_once 'template/tm_header.php';
		require_once 'template/tm_search.php';
		require_once 'template/tm_footer.php';
	}



?>