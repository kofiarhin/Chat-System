$(function(){

	//console.log("javascipt passed");


   //var check = setInterval(check_message, 100);


   function check_message() {


   		$con_id = $('#conversation_id').val();

   		$.post('get_messages', {

   			con_id: $con_id
   		}, function(data){


   				$("#result").html(data);

   		});

   }


   var unread = setInterval(unread_messages, 100);

   function unread_messages() {

      $.get("unread_messages.php", function(data){

            $("#unread").html(data);
      });
   }

})