<?php 


class Conversation {


	private $db = null,
	$conversation_id,
	$data = array(),
	$session_name = null;



	public function __construct($user = null) {

		$this->db = db::get_instance();
		$this->session_name = config::get("session/session_name");


		if(!$user) {

			if(session::exist($this->session_name)) {

				$user = session::get($this->session_name);

				$this->data = $this->get_conversation($user);
			}
		}
	}


	public function update_last_seen($con_id, $user_id) {

			$sql = "update conversation_members
		
				set last_seen = ? where conversation_id = ? and user_id = ?

			";

			$fields = array(

				'last_seen' => (new DateTime)->getTimestamp(),
				'conversation_id' => $con_id,
				'user_id' => $user_id
			);

			$query = $this->db->query($sql, $fields);


			if($query) {

				//echo "last seen updated";

				return true;
			}

			return false;
	}


	public function get_unread($user_id) {


			$sql = "select * from messages where receiver_id = ? and checked = ?";

			$fields = array(

				'receiver_id' => $user_id,
				'checked' => 0
			);

			$query = $this->db->query($sql, $fields);

			if($query->count()) {

				return count($query->result());
			}


			return false;

	}

	public function get_last_seen($con_id, $user_id) {

		//echo $con_id," ",$user_id;

		$sql = "select last_seen from conversation_members

				where  conversation_id = ? and user_id =  ?		
		";

		$fields  = array(

			'con_id' => $con_id,
			'user_id' =>  $user_id 
		);

		$query = $this->db->query($sql,$fields);

		if($query->count()) {

			return( (int) $query->first()->last_seen);
		}


		return null;
	}
	



	public function delete_message($id) {

		$delete = $this->db->delete('messages', array('id', '=', $id));

		if($delete) {

			echo "deleted";

			return true;
		}


		return false;
	}

	public function get_conversation($user = null) {



		$sql = "select 

		conversations.id as con_id,


		max(messages.id) as message_id,
		max(messages.created_on) as created_on

		from conversations


		inner join messages

		on conversations.id = messages.conversation_id


		inner join conversation_members

		on conversations.id = conversation_members.conversation_id

		where conversations.user_one = ? or conversations.user_two = ?

		group by conversations.id
		";

		$fields = array(

			'user_one' => $user,
			'user_two' => $user
		);



		$query = $this->db->query($sql, $fields);

		if($query->count()) {




			return($query->result());

		}



		return false;




	}
	


	public function get_message($id) {


			if($id) {

					$sql = "select * from messages where id = ?";

					$fields = array(

						'id' =>  $id
					);

					$query = $this->db->query($sql,$fields);

					if($query->count()) {

						return($query->first());
					} 



			}

			return false;

			



	}



	public function check($sender_id, $receiver_id) {


					//sender having conversation with receiver

		$sender_check = $this->having_conversation($sender_id, $receiver_id);

		$receiver_check = $this->having_conversation($receiver_id, $sender_id);

		if($sender_check or $receiver_check) {

				if($sender_check) {

					$con_id = $sender_check;
				} else if($receiver_check) {

					$con_id = $receiver_check;
				}


				return $con_id;
			
		}


		return false;


	}


	public function exist() {

		return(!empty($this->data)) ? true : false;
	}


	public function add_reply($fields) {

		$con_insert = $this->db->insert("messages", $fields);

		if($con_insert) {
			session::flash("success", "Message Sent!");
			return true; 

		}


		return false; 


	}

	public function get_con_members($con_id) {


		$sql = "select user_one, user_two from conversations 

		where id = ?
		";

		$fields = array(

			'id' => $con_id
		);


					//$members = $this->db->get('conversations', array('id', '=', $con_id));

		$members = $this->db->query($sql, $fields);
		if($members->count()) {

			return($members->first());
		}

		return false;
	}

	public function data() {

		return $this->data;
	}


	public function having_conversation($user_one, $user_two) {


		$sql = "select * 
		from conversations 
		where user_one = ? and user_two = ?";

		$fields = array(

			'user_one' => $user_one, 
			'user_two' => $user_two

		);

		$query = $this->db->query($sql, $fields);

		if($query->count()) {

			return($query->first()->id);
		}


		return false;

	}


	public function get_con_messages($con_id) {


		$messages = $this->db->get('messages', array('conversation_id', '=', $con_id));

		if($messages->count()) {

			return($messages->result());
		}


		return false;


	}





	public function create($user_ids, $content) {


					//insert into conversations

		$sender_id = (int) $user_ids['sender_id'];
		$receiver_id = (int) $user_ids['receiver_id'];

					//conversation fields

		$con_fields = array(


			'user_one' => $sender_id,
			'user_two'  => $receiver_id

		);


		//insert into conversation

		$con_insert = $this->db->insert('conversations', $con_fields);

		if($con_insert) {

			$con_id = $con_insert;

			//insert into conversation members
			
			foreach($user_ids as $user_id) {


					$fields = array(

						'conversation_id' =>  $con_id,
						'user_id' => $user_id,
						'last_seen' => 0


					);


					$insert = $this->db->insert('conversation_members', $fields);

					if($insert) {

						echo "members craeted";
					}


					//update the current user last seen



			}



			if(session::exist($this->session_name)) {

				//update the members table of the last seen

				$sql = "update conversation_members 

						set last_seen = ?


						where conversation_id = ? and user_id = ?
				";


				$fields = array(

					'last_seen' => (new DateTime)->getTimestamp(),
					'conversation_id' => $con_id,
					'user_id' => session::get('user')
				);


				$update = $this->db->query($sql, $fields);

				if($update) {

					echo "last seen updated";
				}

			}
			


			//insert into messages
			$message_fields = array(
				'conversation_id' => $con_id,
				'sender_id' => $sender_id,
				'receiver_id' => $receiver_id,
				'content' => $content,
				'created_on' => (new DateTime)->getTimestamp(),
				'checked' => 0

			);




			//var_dump($message_fields);

			$message_insert = $this->db->insert('messages', $message_fields);

			if($message_insert) {

				session::flash('success', 'Message sent!');

				return true;

			}

		}


		return false;

	}

}