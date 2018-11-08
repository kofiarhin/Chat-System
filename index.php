<?php 

require_once "header.php";
	



?>



<?php 


		if(!$user->logged_in()) {

			require_once "login.php";
		} else {


			require_once "members.php";
		}



 ?>





</body>
</html>

