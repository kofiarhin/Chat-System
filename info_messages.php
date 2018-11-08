<div class="container">
	

		<div class="row">
			
				<div class="col-md-6 offset-md-3">
					
					<?php 

							if(session::exist('success')) {


							?>
	<p class="alert alert-success text-center"><?php echo session::flash("success"); ?></p>

							<?php 

							}


							//check if there is an error message in session


							if(session::exist("error")) {


								?>
	<p class="alert alert-danger"><?php echo session::flash("error"); ?></p>


								<?php 
							}



					 ?>

				</div>


		</div>


</div>