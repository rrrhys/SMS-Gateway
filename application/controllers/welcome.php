<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('uuid');
		$this->load->helper('phone');
		$this->load->library('session');
		$this->load->database();
	}
	
	public function _register_with_smsserver($client_name,$accept_sms,$send_sms)
	{
		$smsserver_url = $this->config->config['smss_sms_server_url'] ."api/add_client/";
		$fields = array('client_name'=>$client_name,
						'accept_sms'=>$accept_sms,
						'send_sms'=>$send_sms,
						'application_id'=>$this->config->config['smss_application_id']);
			$fields_string = "";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string,'&');
			log_message('debug', "attempting to register with SMS Server $smsserver_url " . $fields_string);
			//open connection
			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$smsserver_url);
			curl_setopt($ch,CURLOPT_POST,count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			//execute post
			$result = curl_exec($ch);
			$return_object = json_decode($result);
			log_message('debug', "return_object looks like $result");
			if($return_object)
			{
				return $return_object->secret_key;
			}

	}
	public function _email_exists($email_address)
	{
		$this->db->where('email_address',$email_address);
		$q = $this->db->get('users')->result_array();
		return count($q) == 1;
	}
	public function _register($email_address, $password, $timezone)
	{
		$new_id = get_uuid();
		$insert = array();
		$insert['id'] = $new_id;
		$insert['email_address'] = $email_address;
		$insert['password']= $this->_hash_password($password);
		$insert['timezone'] = $timezone;
		$insert['activation_key'] = get_uuid();
		
		//tell the SMS Server that there is a new customer
		$insert['secret_key'] = $this->_register_with_smsserver($insert['email_address'],1,0);		
		log_message('debug', "Attempting to add new user: " . json_encode($insert));	
		$this->db->insert('users',$insert);
		

		
		$this->_send_activation_email($new_id);
		return true;
	}
	public function _send_activation_email($user_id){
	log_message('error', "ACTIVATION EMAIL NOT IMPLEMENTED.");	
	$data = array();
	$data['user_id'] = $user_id;
	$this->_send_email("activation_email",$data);
	}
	function _send_email($email_template_name,$data)
	{
	$this->load->library('email');
	$email_id = get_uuid();
	log_message('debug', "_send_email handler: $email_template_name, ID: $email_id");	
	

		switch($email_template_name){
			case("activation_email"):
				$this->db->where('id',$data['user_id']);
				$q = $this->db->get('users')->result_array();
				$q=  $q[0];
				$data['activation_key'] = $q['activation_key'];
				$data['from_name'] = $this->config->config['product_name'];
				$data['from_email'] = $this->config->config['generic_email'];
				$data['bcc_email'] = $this->config->config['carbon_copy_email'];
				$data['to_email'] = $q['email_address'];
				$data['server'] = $_SERVER['SERVER_NAME'];
				$email = $this->load->view("emails/".$email_template_name,$data,true);
			break;
		}
		log_message('debug', "email data:: . " . json_encode($data),true);	
			$this->load->library('email');
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = "html";

			$this->email->initialize($config);
			$this->email->from($data['from_email'], $data['from_name']);
			$this->email->to($data['to_email']); 
			$this->email->bcc($data['bcc_email']); 

			$this->email->subject("Activate your new account");
			$this->email->message($email);	

			$this->email->send();

			echo $this->email->print_debugger();
		
		
	}
	function _base_data()
	{
		return array(
				'title'=>'SMS Gateway',
				'logged_in'=>$this->logged_in(),
				'flash'=>$this->session->flashdata('flash'),
				'error_flash'=>$this->session->flashdata('error_flash'),
				'notifications'=>$this->session->userdata('notifications'));
	}
	public function _activate($activation_key){
	$this->db->where('activation_key',$activation_key);
	$update['active'] = '1';
	$this->db->update('users',$update);
	$this->db->where('activation_key',$activation_key);
	$q = $this->db->get('users')->result_array();
	return count($q) == 1;
	}
	 public function _login($email_address, $password){
	
	$this->_rate_limit();
	$shapassword = $this->_hash_password($password);
	$this->db->where(array(
		'email_address'=>$email_address,
		'password'=>$shapassword,
		'active'=>1));
		$q = $this->db->get('users')->result_array();
		$q = $q[0];
		if(count($q) > 0)
		{
		
		$this->session->set_flashdata('flash',"Login successful!");
		$this->_reset_rate_limit();
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
	public function _hash_password($password){
	$shapassword = sha1($password . $this->config->config['hash']);
	return $shapassword;
	}
	 public function _reset_rate_limit()
	{
	$this->session->unset_userdata('attempts');
	}
	public function _rate_limit()
	{
	
		if(!$this->session->userdata('attempts'))
		{
			$this->session->set_userdata('attempts',0);
		}
		$attempts = $this->session->userdata('attempts');
		$this->session->set_userdata('attempts',$attempts+1);
		if($attempts >= 3)
		{
		//sleep(0.5 * $attempts * $attempts);
		}
	}

	 function test_phone($phone_no)
	 {
		$this->load->helper('phone');
		echo properly_format_phone($phone_no);
	 }

	 public function uuid()
	 {
		for($i = 0; $i < 10; $i++)
		{
			echo get_uuid() . "<br />";
		}
	 }
	public function index()
	{
	$data = $this->_base_data();
	$data['title'] = "SMS Postage Platform - SMS Gateway";
		$this->load->view('header',$data);
		$this->load->view('welcome_message',$data);
		$this->load->view('footer',$data);
	}
	public function activate($activation_key)
	{
		if($this->_activate($activation_key))
		{
		$this->session->set_flashdata('flash',"Your account has been activated successfully! <br />Please log-in to use SMS Gateway.");
				
		}
		else
		{
		$this->session->set_flashdata('error_flash',"Your activation was unsuccessful. Please try copy and pasting the link in the email into your browser address bar.");
		}
		redirect("/welcome/");
	}
	public function register()
	{

	
		if($this->input->post('email_addressr'))
		{
			$this->load->helper('email');
			if(!valid_email($this->input->post('email_addressr')))
			{
			log_message('debug', "Email not valid: " . $this->input->post('email_addressr'));	
				$this->session->set_flashdata('error_flash',"The email address supplied was invalid.");
				redirect("/welcome/register");			
			}
			if($this->_email_exists($this->input->post('email_addressr')))
			{
			log_message('debug', "Email exists: " . $this->input->post('email_addressr'));	
				$this->session->set_flashdata('error_flash',"This email address exists.");
				redirect("/welcome/register");			
			}
			if($this->input->post("passwordr") =="" || strlen($this->input->post("passwordr")) < 6)
			{
			log_message('debug', "Register failed - password too short");	
				$this->session->set_flashdata('error_flash',"The password is too short.");
				redirect("/welcome/register");			
			}
			if($this->input->post("passwordr") != $this->input->post("passwordconfirmr"))
			{
			log_message('debug', "Register failed - password doesn't match");
				$this->session->set_flashdata('error_flash',"The supplied passwords did not match.");
				redirect("/welcome/register");			
			}
			if(!phone_number_valid($this->input->post("phoner")))
			{
			log_message('debug', "Register failed - phone number too short - " . $this->input->post("phoner"));
				$this->session->set_flashdata('error_flash',"The phone number is invalid.");
				redirect("/welcome/register");				
			}
		
			if($this->_register($this->input->post('email_addressr'),$this->input->post('passwordr'),$this->input->post("timezones")))
			{
			log_message('debug', "Register Successful");
			$this->session->set_flashdata('flash',"Your account has been created! Please check your email for further instructions.");
				redirect("/welcome/");
			}
			else
			{
			log_message('debug', "Register failed from model.");
				$this->session->set_flashdata('error_flash',"Registration was not successful.");
				redirect("/welcome/register");
			}
		}
		else
		{
			$data = $this->_base_data();
			$this->load->view('header',$data);
			$this->load->view('register',$data);
			$this->load->view('footer',$data);	
		}	
	}
	public function login()
	{

	
		if($this->input->post('emailaddress'))
		{
			if($this->_login($this->input->post('email_address'),$this->input->post('password')))
			{
			$this->session->set_flashdata('flash',"Login was successful.");
				redirect("/user/create_sms");
			}
			else
			{
				$this->session->set_flashdata('error_flash',"Login unsuccessful.");
				redirect("/welcome/login");
			}
		}
		else
		{
			$data = $this->_base_data();
			$this->load->view('header',$data);
			$this->load->view('login',$data);
			$this->load->view('footer',$data);	
		}
	}
	public function logout()
	{
		if($this->input->post('areyousure') == "yes")
		{
			
			$this->session->unset_userdata('email_address');
			$this->session->set_flashdata('flash',"You have been logged out successfully.");
			redirect("/welcome");
		}
		else
		{
			$data = $this->_base_data();
			$data['title'] = "logout";
			$this->load->view('header',$data);
			$this->load->view('logout_areyousure',$data);
			$this->load->view('footer',$data);		
		}
	}

	public function logged_in()
	{
		return $this->session->userdata('email_address');
		
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */