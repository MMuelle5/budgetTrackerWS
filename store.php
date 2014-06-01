<?php
	session_start();
	$_SESSION['access_token'] = $_GET['access_token'];
	$_SESSION['id'] = $_GET['id'];
	
	echo 'durch';
?>