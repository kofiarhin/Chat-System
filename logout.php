<?php 

	require_once "core/init.php";


	$user = new  User;


	$user->logout();


	redirect::to("login.php");

 ?>