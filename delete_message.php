<?php 

		require_once  "core/init.php";


		$conversation = new Conversation;

		$con_id = input::get('con_id');

		$delete = $conversation->delete_message(input::get('message_id'));


		if($delete) {

			redirect::to('messages.php');
		}



 ?>