<?php 


require_once "header.php";

$user = new User;


if(!$user->logged_in()) {

	redirect::to("login.php");
}

	//var_dump($user);

$members = 	$user->get_users();

$total_members = count($members);




?>


<section id="mebers">


	<div class="container">

		<h1 class="title">Members (<?php echo $total_members; ?>)</h1>


		<div class="row">




			<?php 

			if($members) {


				foreach($members as $member) {


							//var_dump($member);
					$member_name = $member->first_name." ".$member->last_name;
					$member_pic = $member->profile_pic;
					$member_id = $member->id;

							//echo $member_pic;



					?>
					<div class="col-md-3 member-unit">

						<div class="member-face" style="background-image: url(uploads/<?php echo $member_pic; ?>);">

						</div>

						<div class="content">
							<p class="name"><?php echo $member_name; ?></p>

						</div>

						<a href="profile.php?user_id=<?php echo $member_id; ?>" class="view">View</a>

						<a href="create_message.php?receiver_id=<?php echo $member_id; ?>" class='link'>Send Message</a>


					</div>



					<?php 
				} 
			}


			?>


			

		</div>


	</div>

</section>