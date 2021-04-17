<?php
	/*
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	//if (isset($_REQUEST[session_name()])) session_start();
	*/
	session_start();
	require_once 'config.php';
	require_once 'function.php';
	require_once 'authorization.php';
	require_once 'controllers.php';
	$uri = $_SERVER['REQUEST_URI'];
	$a = strpos($uri,'?');
	if (!empty($a)) {
		$uri = substr($uri,0,strpos($uri,'?'));
	}
	if (!isset($_SESSION['user_id'])) {		
		if (($uri == '/')||($uri == '/index.php')) {
			login();
		} elseif ($uri == '/index.php/registration') {
			registration();
		} else {
			header('Status: 404 Not Found');
			echo '<html><body><h1>Page Not Found</h1></body></html>';
		}
	} else {
		if (($uri == '/')||($uri == '/index.php')||($uri == '/index.php/page')) {
			page();
		} elseif ($uri == '/index.php/page_edit') {
			page_edit();
		} elseif ($uri == '/index.php/friends/myfriends') {echo '1';
			friends(1); 
		} elseif ($uri == '/index.php/friends/friendonline') {echo '2';
			friends(2); 
		} elseif ($uri == '/index.php/friends/friendsend') {echo '3';
			friends(3); 
		} elseif ($uri == '/index.php/communication') {
			communication();
		} elseif ($uri == '/index.php/search') {
			search();
		} elseif ($uri == '/index.php/logout') {
			logout();
		} else {
			header('Status: 404 Not Found');
			echo '<html><body><h1>Page Not Found</h1></body></html>';			
		}
	}
?>