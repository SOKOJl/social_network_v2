<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="/site.css" media="screen">
<link rel="stylesheet" href="/mobile.css" media="handheld,only screen and (max-device-width:480px)">
<link rel="stylesheet" href="/bootstrap.min.css">
<meta name=viewport content="width=400">
<title>Печеньки</title>
</head>
<body>
<div class="logo">
	<h1><b><a href="/index.php">Pechenki.ru</a></b></h1>
</div>
<div class="main_menu">
	<nav class="cl-effect-1">
	<?php nav_menu(); ?>
	</nav>
</div>
<div class="errors">
	<?php msg_error($msg);	?>
</div>
<div>