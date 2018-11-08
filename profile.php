<?php 


require_once  "header.php";

if(!$user->logged_in()) {

	redirect::to('login.php');
}


	if(input::get('user_id')) {


			//if user id is not the same as person in session
			//redirect to view user profile

			if(session::get('user') != input::get('user_id')) {

					redirect::to("view_user.php?user_id=".input::get('user_id'));
			}


	}


		

	if(!$user->exist()) {

		session::flash('error', "person does not exist in database");
		redirect::to('members.php');
	}
 
	//personal details
	$user_id = $user->data()->id;
	$name = $user->data()->first_name." ".$user->data()->last_name;
	$profile = $user->data()->profile_pic;


	$profile_path = "uploads/".$profile;



	$profile_pic = (file_exists($profile_path)) ? $profile_path : "uploads/default.jpg";




?>


<div class="container">

	<h1 class="title">Profile Page</h1>

	<?php 

		if(input::exist('post', 'profile_submit')) {

				$user_file = input::get('file');

				//var_dump($file);

				$filename = $user_file['name'];
				if(!empty($filename)) {

					
						$file  = new File();

						$check = $file->check($user_file);

						if($check->passed()) {

							$upload = $file->upload($user_file);

							if($upload) {

								redirect::to('profile.php');
							}
						} else {

							foreach($check->errors() as $error) {

								?>

			<p class="alert alert-danger text-center"><?php echo $error; ?></p>
								<?php 
							}
						}




				} else {


					//display error;


					?>
			
			<div class="row">
				
				<div class="col-md-6 offset-md-3">
					
					<p class="alert alert-danger text-center">You need to select a file</p>
				</div>
			</div>

					<?php 


				}
		}

	 ?>

	<div class="row">

		<div class="col-md-2 offset-md-1 face-unit">

			<div class="face" style="background-image: url('<?php echo $profile_pic; ?>')"></div>

			<form action="" method='post' enctype="multipart/form-data">
				
				<div class="form-group">
					<input type="file" class="form-control-file" name='file'>


				</div>

				<button class="btn btn-primary" type="submit" name="profile_submit">Change</button>

			</form>
		</div>

		<div class="col-md-6">


					<?php 


						if(input::exist("post", "save_submit")) {

							$validation = new Validation;
						}

					 ?>

				<form action="" method='post'>
					
					<div class="form-group">
						<label for="first_name"><strong>First Name</strong></label>

						<input type="text" class="form-control" name="first_name" value="<?php echo $user->data()->first_name; ?>">
					</div>


					<div class="form-group">
						
				<label for="last_name"><strong>Last Name</strong></label>

				<input type="text" class="form-control" name="last_name" value="<?php echo $user->data()->last_name; ?>">

					</div>


					<button class="btn btn-primary" type="submit" name="save_submit">Save Changes</button>
					
				</form>
				

		</div>

	</div>

</div>