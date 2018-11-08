<?php 

		

		require_once "header.php";

		$member_id = input::get("user_id");


		$member = new User($member_id);

		if($member->exist()) {


			var_dump($member->data());
		}


 ?>