<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
	function __construct()
	{
		parent::__construct();
	
		//let user log in by specifying their secret key.
		if($this->input->post("login_secret_key") && $this->input->post("login_email_address")){
			$login_secret_key = $this->input->post("login_secret_key");
			$login_email_address = $this->input->post("login_email_address");
			$messages = $this->user_model->_login($login_email_address,"",$login_secret_key);
		}
	}
	function _old_pass_correct($sha_password,$email_address)
	{
		$this->db->where('password',$sha_password);
		$this->db->where('email_address',$email_address);
		$q = $this->db->get('users')->result_array();

		return count($q) == 1;
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		redirect("/user/dashboard/");
	}
	public function _get_preferences()
	{
	$this->db->where('email_address',$this->session->userdata('email_address'));
	$q = $this->db->get('users')->result_array();
	$q = $q[0];
	return $q;
	}
	public function activate($activation_key = "")
	{
		if(!$activation_key){
			$activation_key = $this->input->post("activation_key");
		}
		if($this->user_model->_activate($activation_key))
		{
		$this->session->set_flashdata('flash',"Your account has been activated successfully! <br />Please log-in to use SMS Gateway.");
				
		}
		else
		{
		$this->session->set_flashdata('error_flash',"Your activation was unsuccessful. Please try copy and pasting the link in the email into your browser address bar.");
		}
		redirect("/");
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
				redirect("/user/register");			
			}
			if($this->user_model->email_exists($this->input->post('email_addressr')))
			{
			log_message('debug', "Email exists: " . $this->input->post('email_addressr'));	
				$this->session->set_flashdata('error_flash',"This email address exists.");
				redirect("/user/register");			
			}
			if($this->input->post("passwordr") =="" || strlen($this->input->post("passwordr")) < 6)
			{
			log_message('debug', "Register failed - password too short");	
				$this->session->set_flashdata('error_flash',"The password is too short.");
				redirect("/user/register");			
			}
			if($this->input->post("passwordr") != $this->input->post("passwordconfirmr"))
			{
			log_message('debug', "Register failed - password doesn't match");
				$this->session->set_flashdata('error_flash',"The supplied passwords did not match.");
				redirect("/user/register");			
			}
			if(!phone_number_valid($this->input->post("phoner")))
			{
			log_message('debug', "Register failed - phone number too short - " . $this->input->post("phoner"));
				$this->session->set_flashdata('error_flash',"The phone number is invalid.");
				redirect("/user/register");				
			}
		
			if($this->user_model->register($this->input->post('email_addressr'),$this->input->post('passwordr'),$this->input->post("timezones")))
			{
			log_message('debug', "Register Successful");
			$this->session->set_flashdata('flash',"Your account has been created! Please check your email for further instructions.");
				redirect("/");
			}
			else
			{
			log_message('debug', "Register failed from model.");
				$this->session->set_flashdata('error_flash',"Registration was not successful.");
				redirect("/user/register");
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
			if($this->user_model->_login($this->input->post('email_address'),$this->input->post('password')))
			{
			$this->session->set_flashdata('flash',"Login was successful.");
				redirect("/sms/create_sms");
			}
			else
			{
				$this->session->set_flashdata('error_flash',"Login unsuccessful.");
				redirect("/user/login");
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
			redirect("/");
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
	public function change_password(){
		if($this->input->post("password_submit"))
		{
			if($this->input->post("new_password") != $this->input->post("new_password_2"))
			{
				$this->session->set_flashdata('error_flash','The passwords supplied did not match.');
				redirect("/user/change_password");
			}
			$old_pass_sha = sha1($this->input->post("old_password") . "4e12f4496e6712.41343661");
			if($this->_old_pass_correct($old_pass_sha,$this->session->userdata('email_address')))
			{	
				$update = array();
				$update['password'] = sha1($this->input->post("new_password") . "4e12f4496e6712.41343661");
				$this->db->where('email_address',$this->session->userdata('email_address'));
				$this->db->where('password',sha1($this->input->post("old_password") . "4e12f4496e6712.41343661"));
				$this->db->update('users',$update);
				$this->session->set_flashdata('flash',"Your new password has been saved successfully!");
			}
			else
			{
			$this->session->set_flashdata('error_flash',"Your old password was incorrect.");
			}
			
			redirect("/user/change_password");
		}
		$data = $this->_base_data();
		$data['title'] = "Change Password";
		$this->load->view('header',$data);
		$this->load->view('change_password',$data);
		$this->load->view('footer',$data);	
	}

		public function todo(){
		$data = $this->_base_data();
		$data['title'] = "To do";
		$this->load->view('header',$data);
		$this->load->view('todo',$data);
		$this->load->view('footer',$data);		
	}
	public function about(){
		$data = $this->_base_data();
		$data['title'] = "About SMS Gateway";
		$this->load->view('header',$data);
		$this->load->view('not_implemented',$data);
		$this->load->view('footer',$data);		
	}
	public function contact(){
		$data = $this->_base_data();
		$data['title'] = "Contact Information";
		$this->load->view('header',$data);
		$this->load->view('not_implemented',$data);
		$this->load->view('footer',$data);		
	}
	public function preferences(){
		if($this->input->post("preferences_submit"))
		{
			$update = array();
			$update['notify_on_reply'] = $this->input->post("notify_on_reply") == "on";
			$update['use_popup_notifications'] = $this->input->post("use_popup_notifications") == "on";
			$update['budget_cap'] = $this->input->post('budget_cap');
			$update['timezone'] = $this->input->post('timezones');
			$this->db->where('email_address',$this->session->userdata('email_address'));
			$this->db->update('users',$update);
			$this->session->set_flashdata('flash',"Your preferences have been saved successfully!");
			
			redirect("/user/preferences");
		}

		$preferences = $this->_get_preferences();
		$data = $this->_base_data();
		$data['title'] = "User Preferences";
		$data['preferences'] = $preferences;
		$this->load->view('header',$data);
		$this->load->view('preferences',$data);
		$this->load->view('footer',$data);	
	}


	
	public function my_account()
	{
		if(!$this->logged_in()){
			redirect("/");
		}
		$data = $this->_base_data();
		$data['title'] = "My Account";
		$this->load->view('header',$data);
		$this->load->view('my_account',$data);
		$this->load->view('footer',$data);
	}	
	public function dashboard()
	{
		if(!$this->logged_in()){
			echo "redir";
			redirect("/");
		}
		$data = $this->_base_data();
		$data['title'] = "Dashboard - SMS Sent and Queued";
		$this->load->view('header',$data);
		$this->load->view('dashboard',$data);
		$this->load->view('footer',$data);
	}
	

	public function get_notifications_json()
	{
		$this->db->where("time_notified < ", date ('Y-m-d H:i:s',now()));
		$this->db->where("popup_notified",0);
		
		$this->db->where("email_address",$this->session->userdata("email_address"));
		$q = $this->db->get('sms')->result_array();
		foreach($q as $r)
		{
			$this->db->where('id',$r['id']);
			$this->db->update('sms',array('popup_notified'=>1));
		}
		echo json_encode(array('notifications'=>$q));
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */