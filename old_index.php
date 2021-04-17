<?php
	session_start();
	require_once 'config.php';
	require_once 'function.php';
	require_once 'authorization.php';
	$uri = $_SERVER['REQUEST_URI'];
	$a = strpos($uri,'?');
	if (!empty($a)) {
		$uri = substr($uri,0,strpos($uri,'?'));
	}
	if (!isset($_SESSION['user_id'])) {		
		if (($uri == '/')||($uri == '/index.php')) {
	    	require_once('/login.php');
		} elseif ($uri == '/index.php/registration') {
			require_once('/registration.php');
		} else {
		    header('Status: 404 Not Found');
		    echo '<html><body><h1>Page Not Found</h1></body></html>';
		}
	} else {
		if (($uri == '/')||($uri == '/index.php')) {
			require_once('/page.php');
		} elseif ($uri == '/index.php/page') {
			require_once('/page.php');
		} elseif ($uri == '/index.php/page_edit') {
			require_once('/page_edit.php');
		} elseif ($uri == '/index.php/friends') {
			require_once('/friends.php');
		} elseif ($uri == '/index.php/communication') {
			require_once('/communication.php');
		} elseif ($uri == '/index.php/search') {
			require_once('/search.php');
		} elseif ($uri == '/index.php/logout') {
			require_once('/logout.php');
		} else {
		    header('Status: 404 Not Found');
		    echo '<html><body><h1>Page Not Found</h1></body></html>';			
		}
	}
?>