<?php 

require_once "core/init.php";

$user = new User();


if($user->logged_in()) {


$profile_pic = $user->data()->profile_pic;
	
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Chat System</title>
	
	<meta name="viewport" content="width=device-width">

	<!--====  bootstrap=======-->
	<link rel="stylesheet" href="css/bootstrap.min.css">


	<!--====  custom css=======-->
	<link rel="stylesheet" href="css/styles.css">


	<!--====  jquery=======-->
	<script src='js/jquery.js'></script>
	<script src='js/main.js'></script>



</head>
<body>


	<header>
		
		<div class="header-wrapper">
			
			<h1 class="logo"><a href="index.php">Logo</a></h1>

			<nav>
				<?php 

				if($user->logged_in()) {

					$user_id = $user->data()->id;

					$username = $user->data()->username;


					$conversation = new Conversation;



					?>
					<a href="messages.php">Chats <span id="unread"></span></a>
					<a href="members.php">Members</a>
					<a href="profile.php?user_id=<?php echo $user_id; ?>" style="text-transform: capitalize;"><?php echo $username; ?></a>
					<a href="logout.php">Logout</a>
					<a href="profile.php?user_id=<?php echo session::get('user'); ?>" class="user-face" style="background-image: url(uploads/<?php echo $profile_pic; ?>)"></a>

					<?php 
				} else {

					?>
					<a href="login.php">Login</a>
					<a href="register.php">Register</a>

					<?php 

				}

				?>
			</nav>
		</div>
	</header>

	<?php 

	include("info_messages.php");

	?>


