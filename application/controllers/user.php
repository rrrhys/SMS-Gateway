<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('uuid');
		$this->load->library('session');
		$this->load->database();
	

	}
	function _old_pass_correct($sha_password,$email_address)
	{
		$this->db->where('password',$sha_password);
		$this->db->where('email_address',$email_address);
		$q = $this->db->get('users')->result_array();

		return count($q) == 1;
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
		if(!$this->logged_in())
		{
		redirect("welcome");
		}
		else
		{
		redirect("/user/create_sms");
		}

	}
	public function _get_preferences()
	{
	$this->db->where('email_address',$this->session->userdata('email_address'));
	$q = $this->db->get('users')->result_array();
	$q = $q[0];
	return $q;
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
	public function api_reference(){
		$data = $this->_base_data();
		$data['title'] = "API Reference";
		$this->load->view('header',$data);
		$this->load->view('api_reference',$data);
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
	public function create_sms(){
		$data = $this->_base_data();
		$data['title'] = "Create a new SMS with SMS Gateway";
		$this->load->view('header',$data);
		$this->load->view('create_sms',$data);
		$this->load->view('footer',$data);
	}
	public function get_templates_json()
	{
		$this->db->where('owner_id',$this->session->userdata('id'));
		$q = $this->db->get('templates')->result_array();
		$templates =array();
		foreach($q as $row)
		{
			$this->db->where('template_id',$row['id']);
			$fields = $this->db->get('template_fields')->result_array();
			$row['fields_required'] = array();
			foreach($fields as $field)
			{
			$row['fields_required'][] = $field['name'];
			}
			$templates[] = ($row);
		}
		echo json_encode(array('templates'=>$templates));
		//echo json_encode($q);
	}
	public function save_template()
	{
		$fields_required = explode("|",$this->input->post("fields_required"));
		var_dump($fields_required);
		var_dump($_POST);
		$new_template_id = get_uuid();
		$template = array();
		$template['name'] = $this->input->post("name");
		$template['text'] = $this->input->post("text");
		$template['owner_id'] = $this->session->userdata('id');
		$template['id'] = $new_template_id;
		
		foreach($fields_required as $field_required)
		{
			$new_id = get_uuid();
			$insert = array();
			$insert['template_id'] = $new_template_id;
			$insert['id'] = $new_id;
			$insert['name'] = $field_required;
			$this->db->insert('template_fields',$insert);
		}
		$this->db->insert('templates',$template);
	}
	
	public function dashboard()
	{
		$data = $this->_base_data();
		$data['title'] = "Dashboard - SMS Sent and Queued";
		$this->load->view('header',$data);
		$this->load->view('dashboard',$data);
		$this->load->view('footer',$data);
	}
	
	public function templates()
	{
		$data = $this->_base_data();
		$data['title'] = "Templates - Set up ready to go SMS messages";
		$this->load->view('header',$data);
		$this->load->view('templates',$data);
		$this->load->view('footer',$data);
	}
	public function add_template()
	{
		$data = $this->_base_data();
		$data['title'] = "Templates - Create a new SMS Template";
		$this->load->view('header',$data);
		$this->load->view('create_template',$data);
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
	public function load_dashboard_sent_json()
	{
		$this->db->where('email_address',$this->session->userdata('email_address'));
		$this->db->where("time_sent is not null",null,false);
		$this->db->order_by("time_sent","desc");
		
		$q = $this->db->get('sms')->result_array();
		foreach($q as &$r)
		{
		$r['time_sent'] = convert_from_gmt($r['time_sent'],$this->session->userdata('timezone'));
		}
		unset($r);
		echo json_encode(array('sms_sent'=>$q));
	}
	public function load_dashboard_queued_json()
	{
		$this->db->where('email_address',$this->session->userdata('email_address'));
		$this->db->where("time_sent is null",null,false);
		$this->db->order_by("schedule","desc");
		
		$q = $this->db->get('sms')->result_array();
		foreach($q as &$r)
		{
		$r['schedule'] = convert_from_gmt($r['schedule'],$this->session->userdata('timezone'));
		}
		unset($r);
		echo json_encode(array('sms_queued'=>$q));
	}
	public function queue_sms()
	{

			if($this->input->post("action") == "send" || $this->input->post("action") == "")
			{
			//ID of sms gateway front end
			
			$application_id = $this->config->item("smss_application_id");
			//ID of sms gateway 'company'
			$billing_id = $this->session->userdata("billing_id");
			if(!$billing_id){
				$billing_id = $this->input->post("billing_id");
			}
			$sms_server_url = $this->config->item("smss_sms_server_url");
			$schedule = $this->input->post("schedule");
			if($schedule == "now")
			{
				$schedule = date ("Y-m-d H:i:s",now());
			}
				//check that user token is ok....
			//set POST variables
			$url = $sms_server_url . "queue/queue_sms/";
			$fields = array(
						'application_id'=>urlencode($application_id),
						'billing_id'=>urlencode($billing_id),
						'phone'=>urlencode($this->input->post('phone')),
						'message_text'=>urlencode($this->input->post('message_text')),
						'schedule'=>urlencode($schedule),
						'callback_page'=>"http://" .$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']
					);

			//url-ify the data for the POST
			$fields_string = "";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string,'&');

			//open connection
			$ch = curl_init();
			if(get_env() == "DEV"){
			curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888'); 
			}
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			//execute post
			$result = curl_exec($ch);
			$return_object = json_decode($result);
			
			$retval = array('result'=>'fail','error_message'=>'');
			log_message('debug', "Queue URL: $url");	
			log_message('debug', "Queue data: $fields_string");	
			log_message('debug', "Return Object: $result");	

			if($return_object && $return_object->result == "success")
			{
				//sms was queued successfully - add it to transaction log.
				$insert = array(
					'id'=>$return_object->sms_id,
					'phone'=>$this->input->post('phone'),
					'message_text'=>$this->input->post('message_text'),
					'schedule'=>$schedule,
					'billing_id'=>$billing_id,
					'email_address'=>$this->session->userdata('email_address'),
					'time_queued'=>date ("Y-m-d H:i:s",now()),
					'company_id'=>'');
					$this->db->insert('sms',$insert);
				$retval['result'] = "success";
				$retval['error_message'] = "";
			}
			else
			{
				//something went wrong? echo a fail object.
				if($return_object)
				{
				$retval['error_message'] = $return_object->error->message;
				}
				else
				{
				$retval['error_message'] = "An internal error occurred.";
				}
			}
			//close connection
			curl_close($ch);
				if($retval['result'] == "success"){
					$this->session->set_flashdata("flash","Message to ".$this->input->post('phone') ." queued successfully");
				}
				echo json_encode($retval);
			}
			if($this->input->post("action") == "notify")
			{
			log_message('debug', "Server notification arrived");	
			log_message('debug', "Sms posted time was " . $this->input->post("sms_posted"));	
				//server is telling us about the sms sent.
				$time_sent = $this->input->post("sms_posted");
				$time_notified = date ("Y-m-d H:i:s",now());
				$id = $this->input->post("id");
				$this->db->where('id',$id);
				$this->db->update('sms',array('id'=>$id,'time_sent'=>$time_sent,'time_notified'=>$time_notified));
				echo "OK";
			}
	}
	public function logged_in()
	{
		return $this->session->userdata('email_address') == true;
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