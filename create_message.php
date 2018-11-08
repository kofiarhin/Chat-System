<?php 

	require_once "header.php";


	if(!$user->logged_in()) {

		redirect::to('login.php');
	}


	$sender_id = session::get('user');


	$receiver_id = input::get('receiver_id');


	$conversation = new Conversation;

	$check = $conversation->check($sender_id, $receiver_id);

	if($check) {

		redirect::to("view_conversation.php?con_id=".$check);
	}

	if(!$receiver_id) {

		redirect::to("members.php");
	}

	$member = new User($receiver_id);

	if(!$member->exist()) {

		session::flash("error", "Person does not exist");
	}


	$member_pic = $member->data()->profile_pic;

	//echo $member_pic ;


 ?>


 <div class="container">
 	
	<h1 class="title text-center">Send Message</h1>



	<div class="row">
		
			<div class="col-sm-4 col-md-2 offset-md-1">
					
					<div class="face" style="background-image: url(uploads/<?php echo $member_pic; ?>)"></div>
			</div>

			<div class=" col-sm-8 col-md-8">


					<?php 

							if(input::exist("post", 'submit')) {

								$validation = new Validation();

								$fields = array(


									'content' => array('required' => true)

								);


								$check = $validation->check($_POST,  $fields);

								if($check->passed()) {

									$conversation = new Conversation;

									$user_ids = array(

										'sender_id' => input::get('sender_id'),
										'receiver_id' => input::get('receiver_id')

									);

									$create = $conversation->create($user_ids, input::get('content'));

									if($create) {

										redirect::to('messages.php');
									}
								} else {


									foreach($validation->errors() as  $error) {


										?>

	<p class="alert alert-danger"><?php echo $error; ?></p>
										<?php 
									}
								}
							}

					 ?>
				
					<form action="" method="post">
						
						<div class="form-group">
							
							<textarea name="content" id="" cols="30" rows="10" class="form-control"></textarea>

						</div>


						<!--====  hidden elememts=======-->

						<input type="hidden" name="sender_id" value="<?php echo $user->data()->id; ?>">
						<input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">


						<button class="btn btn-primary" type="submit" name="submit">Send message</button>

					</form>
			</div>


	</div>
 </div>