<?php 

		require_once "core/init.php";
		
		$conversation = new Conversation;

		$con_id = input::get('con_id');

		$conversation = new Conversation;

		$messages = $conversation->get_con_messages($con_id);

		if($messages) {


				echo count($messages);

		}


 ?>