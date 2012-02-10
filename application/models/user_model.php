<?

class User_model extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function register($email_address, $password, $timezone)
	{
		$new_id = get_uuid();
		$insert = array();
		$insert['id'] = $new_id;
		$insert['email_address'] = $email_address;
		$insert['password']= $this->_hash_password($password);
		$insert['timezone'] = $timezone;
		$insert['activation_key'] = get_uuid();
		
		//tell the SMS Server that there is a new customer
		$insert['secret_key'] = $new_id;//$this->_register_with_smsserver($insert['email_address'],1,0);		
		log_message('debug', "Attempting to add new user: " . json_encode($insert));	
		$this->db->insert('users',$insert);
		$this->_send_activation_email($new_id);
		return true;
	}
	public function _hash_password($password){
		$shapassword = sha1($password . $this->config->config['hash']);
		return $shapassword;
	}
	public function _send_activation_email($user_id){
		log_message('error', "ACTIVATION EMAIL NOT IMPLEMENTED.");	
		$data = array();
		$data['user_id'] = $user_id;
		$this->_send_email("activation_email",$data);
	}

}