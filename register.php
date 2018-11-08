<?php 

require_once "header.php";

?>


<section id="register">

	<div class="container">


		<h1 class="title text-center">Join us!</h1>


		<div class="row">

			<div class="col-md-4 offset-md-4">


				<?php 

				if(input::exist('post', 'submit')) {

					$validation = new Validation();

					$fields = array(

						'first_name' =>  array(


							'required' => true,
							'min' => 2,
							'max' => 50
						),

						'last_name' => array(

							'required' => true,
							'min' => 2,
							'max' => 50
						),

						'username' => array(

							'required' => true,
							'unique' => 'users'
						),

						'password' => array(

							'required' => true
						)

					);

					$check = $validation->check($_POST, $fields);

					if($check->passed()) {


						$user = new User();


						$fields = array(

							'first_name' => input::get('first_name'),
							'last_name' => input::get('last_name'),
							'username' => input::get('username'),
							'password' => input::get('password'),
							'profile_pic' => "default.jpg", 
							'created_on'=> date("Y-m-d H:i:s")

						);

						$account  = $user->create($fields);

						if($account) {

							redirect::to('login.php');
						} else {

							?>
	<p class="alert alert-danger">There was a problem creating account!</p>

							<?php 
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

				<form action="" method="post">

					<div class="form-group">

						<div class="row">

							<div class="col">

								<label for="first_name">First Name</label>
								<input type="text" class="form-control" placeholder="Eg. John" name='first_name' value="<?php echo input::get('first_name'); ?>">
							</div>

							<div class="col">

								<label for="last_name">Last Name</label>
								<input type="text" class="form-control" placeholder="Eg. Doe" name="last_name" value="<?php echo input::get('last_name'); ?>">
							</div>

						</div>

					</div>


					<div class="form-group">
						
						<label for="username">Username</label>

						<input type="text" name="username" class="form-control" placeholder="Enter Username" value="<?php echo input::get('username'); ?>">

					</div>


					<div class="form-group">
						
						<label for="password">Password</label>

						<input type="password" name="password" class="form-control" placeholder="Enter Password" value="<?php echo input::get('password'); ?>">

					</div>


					<button class="btn btn-primary" type="submit" name='submit'>Join</button> <span>or</span> <a href="login.php">Already a Member?</a>

				</form>


			</div>

		</div>


	</div>



</section>