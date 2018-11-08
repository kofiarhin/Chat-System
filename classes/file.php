<?php 


		class File {

			private $db = null,
					$file_new_name,
					//$passed = false,
					$session_name,
					$errors = array();



			public function __construct() {

					$this->db = db::get_instance();

					$this->session_name = config::get('session/session_name');


			}


			public function upload($file) {

				//var_dump($file);
				$file_name = $file['name'];
				$file_tmp_name = $file['tmp_name'];
				$file_error = $file['error'];
				$file_size = $file['size'];



				$file_ext = explode('.', $file_name);

				$file_act_ext = strtolower(end($file_ext));
				
	
				if($file_act_ext != 'jpg') {

					return false;
				}
				


				$file_new_name = md5(uniqid()).".".$file_act_ext;

				$file_destionation = "uploads/".$file_new_name;


				if(move_uploaded_file($file_tmp_name, $file_destionation)) {

					//echo "file uploaded";



					if(session::exist($this->session_name)) {


						$user = session::get($this->session_name);


						//get old file and delete it


						$user_check = $this->db->get('users', array('id','=', $user));


						if($user_check->count()) {

							 $old_profile = $user_check->first()->profile_pic;

							 $file_name = "uploads/".$old_profile;

							if(file_exists($file_name) && $old_profile != "default.jpg") {


								ulink($file_name);

							}

						}




						$fields = array(

							'profile_pic' => $file_new_name

						);

						$update = $this->db->update('users', $fields, array('id', '=', $user));

						if($update) {


							session::flash('success', 'Profile successfully changed');

							return true;
						}


					}


				}



				return false;
				


				

			}


			public function check($file) {

				$filename = $file['name'];
				$file_tmpname = $file['tmp_name'];
				$file_error = $file['error'];
				$file_size = $file['size'];

				if($file_error) {

					$this->add_error("File Error");
				}

				if($file_size > 1000000) {

					$this->add_error("File Size Too Huge");


				}

				return $this;
			}


			public function add_error($error) {

				$this->errors[] = $error;
			}


			public function passed() {

				return (empty($this->errors)) ?  true : false;
			}


			public  function errors() {

					return $this->errors;

			}
		}



 ?>