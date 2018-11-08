<?php 

require_once "header.php";

?>


<Section id="login">


	<div class="container">
		
		<h1 class="title text-center">Let Get Talking</h1>


		<div class="row">
			
				<div class="col-md-4 offset-md-4">
				

				<?php 

						if(input::exist('post', 'submit')) {


							$validation  = new  Validation;


							$fields =array (

								'username' => array(

									'required' =>  true

								),


								'password' => array(

									'required' => true
								)


							);

							$check = $validation->check($_POST, $fields);

							if($check->passed()) {

								
										$user = new User;



										$login = $user->login(input::get('username'), input::get('password'));


										if($login) {

											redirect::to('members.php');
										} else {

											?>
		<p class="alert alert-danger">Invalid Username/Password Combination</p>

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
							
							<label for="username">Username</label>
							<input type="text" class="form-control" placeholder="Enter Username" name="username">
						</div>

						<div class="form-group">
							
							<label for="password">Password</label>
							<input type="password" class="form-control" name="password" placeholder="Enter Password">
						</div>

						<button class="btn btn-primary" type="submit" name="submit">Login</button> <span>or</span><a href="register.php">Register</a>

					</form>
				</div>


		</div>
		
	</div>


</Section>


</body>
</html>

