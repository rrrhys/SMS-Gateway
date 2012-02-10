<?

class User_model extends CI_Model 
{
	public $errors = array();
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('email_model');
	}
	public function is_email_valid($email_address){
		

		  if(preg_match("^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$^",$email_address)){
		    //list($username,$domain)=str_split('@',$email_address);
		    /*if(!checkdnsrr($domain,'MX')) {
		      return false;
		    }*/
		    return true;
		  }
		  return false;
	}
	public function register($email_address, $password, $timezone)
	{
		$new_id = get_uuid();
		$insert = array();
		$insert['id'] = $new_id;
		$insert['email_address'] = $email_address;
		if(strlen($password) < $this->config->config['password_minimum_length']){
			$errors[] = "Password too short.";
			return false;
		}
		if(!$this->is_email_valid($email_address)){ //a@b.c
			$errors[] = "Email address is invalid.";
			return false;
		}
		$insert['password']= $this->_hash_password($password);
		$insert['timezone'] = $timezone;
		$insert['activation_key'] = get_uuid();
		
		//tell the SMS Server that there is a new customer
		$insert['secret_key'] = $new_id;//$this->_register_with_smsserver($insert['email_address'],1,0);		
		log_message('debug', "Attempting to add new user: " . json_encode($insert));	
		if(!$this->config->config['unit_tests_running']){
			$this->_send_activation_email($new_id);
		}


		if($this->db->insert('users',$insert)){
			return $insert['id'];
		}
		else  {
			return false;
		}

		
	}
	public function get_user_by_email($email_address){
		$this->db->where('email_address',$email_address);
		$q = $this->db->get('users')->row_array();
		return $q;
	}
	public function count_users(){
		$q = $this->db->get('users')->num_rows();
		return $q;
	}
	public function _hash_password($password){
		$shapassword = sha1($password . $this->config->config['hash']);
		return $shapassword;
	}
	public function _send_activation_email($user_id){
		log_message('error', "ACTIVATION EMAIL NOT IMPLEMENTED.");	
		$data = array();
		$data['user_id'] = $user_id;
		$this->email_model->send_email("activation_email",$data);
	}

}