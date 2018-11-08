<?php 

require_once "header.php";


if(!$user->logged_in()) {

	redirect::to('login.php');


}



$conversation =  new Conversation;

$con_id = input::get('con_id');
$message_id = input::get('message_id');


//update conversation last_seen


$last_seen_update = $conversation->update_last_seen($con_id, session::get('user'));

//if message id and sender is is not user in session update the message

$sender_message = db::get_instance()->get('messages', array('id', '=', $message_id));

if($sender_message->count()) {

	$sender_id = $sender_message->first()->sender_id;

	if($sender_id  != session::get('user')) {

			//update

			$sql = "update messages set checked = ?  where receiver_id = ? and  conversation_id = ?";

			$fields = array(

				'checked' => 1,
				'receiver_id' => (int) session::get('user'),
				'conversation_id' => $con_id
			);


			$update = db::get_instance()->query($sql, $fields);

			if($update) {

				echo "pass";
			} else {

				echo "error";
			}

			//update table

			///$update = db::get_instance()->update('messages', $fields, array('id', '=', $message_id));

	}
}




$messages = $conversation->get_con_messages($con_id);

$con_member = $conversation->get_con_members($con_id);

$user_one  = $con_member->user_one;

$user_two = $con_member->user_two;



if(session::get('user') != $user_one) {

	$person_id = $user_one;
}


else if(session::get('user') != $user_two) {


	$person_id = $user_two;
}


$member = new User($person_id);


if(!$member->exist()) {

	session::flash("error", "person does not exist");

	redirect::to("messages.php");
}


$person_pic = $member->data()->profile_pic;


?>

<input type="hidden" name="message_id" id="conversation_id" value="<?php echo $con_id; ?>">


<div class="container">


	<div class="con-head" style="background-image: url(uploads/<?php echo $person_pic; ?>)"></div>

	<div class="row" id="result">

		<?php 

		if($messages) {

			foreach($messages as $message) {

				//var_dump($messages);
				
				$message_id = $message->id;
				$content = $message->content;
				$con_id = $message->conversation_id;

				$sender_id = $message->sender_id;

				$sender = new User($sender_id);

				if($sender->exist()) {

					$sender_pic = $sender->data()->profile_pic;
				} else {

					$sender_pic = "default.jpg";
				}

				?>

				<div class="col-md-6 offset-md-3">


					<div class="conversation-unit">

						<div class="con-face" style="background-image: url(uploads/<?php echo $sender_pic; ?>)"></div>
						<div class="con-content">
							<p class="message"><?php echo $content; ?></p>



						</div>

						<a href="delete_message.php?con_id=<?php echo $con_id; ?>&message_id=<?php echo $message_id; ?>" class="link btn btn-danger">delete</a>

					</div>
				</div>


				<?php 

			}
		}

		?>

	</div>


	<h1 class="sub-title text-center">Add Reply</h1>
	<div class="row">
		
			<div class="col-md-6 offset-md-3">


				<?php 

					if(input::exist('post', 'reply_submit')) {

								$validation = new Validation;

								$fields = array(

									'content' => array(

										'required' => true
									)

								);


								$check = $validation->check($_POST, $fields);

								if($check->passed()) {

								

										$fields = array(

											'conversation_id' => (int) input::get('conversation_id'),
											'sender_id' => (int) input::get('sender_id'),
											'receiver_id' => (int) input::get('receiver_id'),
											'content' => input::get("content"),
											'created_on' => (new DateTime)->getTimestamp(),
											'checked' => 0


										);

				

										$add_reply = $conversation->add_reply($fields);

										if($add_reply) {

											redirect::to("view_conversation.php?con_id=".$con_id);
										}


								} else {


									foreach($check->errors() as $error) {


										?>

	<p class="alert alert-danger"><?php echo $error; ?></p>

										<?php 
									}
								}






					}


				 ?>
				
				<form action="" method='post'>
					
						
					<div class="form-group">
						<textarea name="content" id="" cols="30" rows="4" class="form-control"></textarea>
					</div>	


					<!--====  hidden elelments=======-->

					<input type="hidden" name="sender_id"  value="<?php echo session::get('user'); ?>">
					<input type="hidden" name="receiver_id"  value="<?php echo $person_id; ?>">
					<input type="hidden" name="conversation_id"  value="<?php echo $con_id; ?>">


					<button class="btn btn-primary" type="submit" name="reply_submit">Add Reply</button>		


				</form>
			</div>


	</div>



</div>