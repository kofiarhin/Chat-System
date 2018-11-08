<?php 


require_once "header.php";


if(!$user->logged_in()) {

	redirect::to('login.php');
}


$conversation = new Conversation();


?>


<h1 class="title text-center">Messages</h1>

<div class="container">


	<div class="row">

		<div class="col-md-6 offset-md-3">


			<?php 

			if($conversation->exist()) {




				$datas = $conversation->data();


				foreach($datas as $data) {


					//message info
					$con_id = (int) $data->con_id;
					$message_id = $data->message_id;
					$created_on = $data->created_on;


					//$last_message = $data->last_message;


					//get last seen of user
					$user_id = (int) session::get('user');

					$last_seen =  $conversation->get_last_seen($con_id, $user_id);

					//check if message is unread

					$message_status = ($created_on > $last_seen) ? "unread" : "read";



					$new_message = "";

					$user_message = $conversation->get_message($message_id);

					if($user_message) {

							$new_message =  $user_message->content;
							$sender_id = $user_message->sender_id;
							$checked = $user_message->checked;

					}	


					//get the other person details

					$con_members = $conversation->get_con_members($con_id);


					$user_one = $con_members->user_one;
					$user_two = $con_members->user_two;


					if($user_one != session::get('user')) {

						$member_id = $user_one;

					} else if($user_two != session::get('user')) {

						$member_id = $user_two;
					}


					$member = new User($member_id);


					if($member->exist()) {

						$member_pic = $member->data()->profile_pic;


						?>


						<a href="view_conversation.php?con_id=<?php echo $con_id; ?>&message_id=<?php echo $message_id; ?>" class="conversation-unit <?php echo $message_status; ?>">

							<div class="con-face" style="background-image: url(uploads/<?php echo $member_pic; ?>)"></div>
							<div class="content">

								<p class="message <?php if(!$checked) {echo "not-checked";} ?>"><?php echo $new_message; ?></p>


								<?php if(session::get('user') == $sender_id) { 

									echo "you";


								} else {



									$sender  = new User($sender_id);

									if($sender->exist()) {


										$sender_name = $sender->data()->first_name." ".$sender->data()->last_name;

										?>
			<p class="text"><?php echo $sender_name; ?></p>

										<?php 


									}
								} 



									?>

							</div>

						</a>

						<?php 


					}






				}



			}

			?>




			


			
		</div>


	</div>



</div>


