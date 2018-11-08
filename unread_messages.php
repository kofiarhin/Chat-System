<?php 
	

		require_once "core/init.php";

		if(session::exist('user')) {


			$conversation = new Conversation();

			$user_id = session::get('user');

			$unread = $conversation->get_unread($user_id);

			if($unread) {

				echo  $unread;
			}
		}