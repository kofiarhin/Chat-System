<?php 



	class User {


		
		private $db = null,
				$session_name = null,
				$logged_in = false,
				$data = array(); 



		public function __construct($user = false) {

			$this->db = db::get_instance();

			$this->session_name = config::get('session/session_name');

			//echo  "let create the login system";

			if(!$user) {

				if(session::exist($this->session_name)) {
					$user = session::get($this->session_name);

					if($this->find($user)) {


						$this->logged_in = true;
					}
				}
			} else {

				$this->find($user);
			}


		}


		public function  create($fields) {





				$account = $this->db->insert('users', $fields);

				if($account) {

					session::flash("success", "your account ".input::get("username")." was successfully created");

					return true;
				}


				return false;


		}


		public function find($user) {

			$field = (is_numeric($user)) ? 'id':'username';

			$check = $this->db->get('users', array($field, '=', $user));

			if($check->count()) {

				$this->data = $check->first();

				return true;
			}

			return false;
		}


		public function login($username, $password) {


				$user  = $this->find($username);

				if($user) {

					if($this->data()->password == $password) {

						session::put($this->session_name, $this->data()->id);

						return true;

					}

					return false;

				}


		}


		public function get_users() {


				if(session::exist($this->session_name)) {

					//get users without the user

					$user = session::get($this->session_name);

					$sql = "select * from users where  id != ?";


					$fields = array(


						'id' => $user
					);

					$query = $this->db->query($sql, $fields);

					if($query->count()) {

							return ($query->result());

					}
 				}

 				return false;

		}



		public function logout() {

			session::delete($this->session_name);
		}


		public function data() {

			return $this->data;
		}


		public function logged_in() {

			return $this->logged_in;
		}


		public function exist() {
			
			return (!empty($this->data)) ? true : false;
		}
	}