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

		$this->db->insert('users',$insert);

		if(!$this->config->config['unit_tests_running']){
			$this->_send_activation_email($insert['id'],$insert['activation_key']);
		}


		return $insert['id'];
		
	}
	public function get_user_by_email($email_address){
		$this->db->where('email_address',$email_address);
		$q = $this->db->get('users')->row_array();
		return $q;
	}
	public function get_users(){
		$q = $this->db->get('users')->result_array();
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
	public function _send_activation_email($user_id,$activation_key){
		log_message('error', "ACTIVATION EMAIL NOT IMPLEMENTED.");	
		$data = array();
		$data['user_id'] = $user_id;
		$data['activation_key'] = $activation_key;
		$this->email_model->send_email("activation_email",$data);
	}
	public function _activate($activation_key){
		$this->db->where('activation_key',$activation_key);
		$update['active'] = '1';
		$this->db->update('users',$update);
		$this->db->where('activation_key',$activation_key);
		$q = $this->db->get('users')->num_rows();
		return $q;
	}
	public function get_email_from_secret_key($secret_key){
		$this->db->where('secret_key',$secret_key);
		$q = $this->db->get('users')->row_array();
		return $q['email_address'];
	}
	 public function _login($email_address, $password){

	$shapassword = $this->_hash_password($password);
	$this->db->where(array(
		'email_address'=>$email_address,
		'password'=>$shapassword,
		'active'=>1));
		$q = $this->db->get('users')->row_array();
		if($q)
		{
		
		$this->session->set_flashdata('flash',"Login successful!");
		$this->session->set_userdata(array(
		'email_address'=>$q['email_address'],
		'id'=>$q['id'],
		'company_id'=>$q['company_id'],
		'secret_key'=>$q['secret_key'],
		'company_admin'=>$q['company_admin'],
		'timezone'=>$q['timezone'],
		'notifications'=>$q['use_popup_notifications']));
		return true;
		
		}
		else
		{
		return false;

		}
	}
	public function email_exists($email_address)
	{
		$this->db->where('email_address',$email_address);
		$q = $this->db->get('users')->num_rows();
		return $q;
	}
}