<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms extends MY_Controller {
	function __construct()
	{
		parent::__construct();
	$this->load->model("sms_model");

	}
	public function api_reference(){
		$data = $this->_base_data();
		$data['title'] = "API Reference";
		$this->load->view('header',$data);
		$this->load->view('api_reference',$data);
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

	public function create_sms(){
		$data = $this->_base_data();
		$data['title'] = "Create a new SMS with SMS Gateway";
		$this->load->view('header',$data);
		$this->load->view('create_sms',$data);
		$this->load->view('footer',$data);
	}

	public function get_templates_json()
	{
		$templates = $this->sms_model->get_templates_by_user_id($this->session->userdata('id'));
		echo json_encode(array('templates'=>$templates));
		//echo json_encode($q);
	}
	public function save_template()
	{
		$fields_required = explode("|",$this->input->post("fields_required"));
		$this->sms_model->save_template(	$this->session->userdata('id'),
																			$this->input->post("name"),
																			$this->input->post("text"),
																			$fields_required);
	}
	public function add_template()
	{
		$data = $this->_base_data();
		$data['title'] = "Templates - Create a new SMS Template";
		$this->load->view('header',$data);
		$this->load->view('create_template',$data);
		$this->load->view('footer',$data);	
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
			$retval = array('result'=>'fail','error_message'=>'');
			if($this->input->post("action") == "send" || $this->input->post("action") == "")
			{
			//ID of sms gateway front end
			
			$application_id = $this->config->item("smss_application_id");
			//ID of sms gateway 'company'
			$secret_key = $this->session->userdata("secret_key");
			if(!$secret_key){
				$secret_key = $this->input->post("secret_key");
			}
			$sms_server_url = $this->config->item("smss_sms_server_url");
			$schedule = $this->input->post("schedule");
			if($schedule == "now")
			{
				$schedule = date ("Y-m-d H:i:s",now());
			}
			$callback_page = $this->input->post("callback_page");
			if($callback_page == ""){
				$callback_page = "http://" .$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			}

			//auth here - is this a legit secret key?
			//if so get email address too.
			$email_address = $this->session->userdata('email_address');
			if($email_address == ""){
				//get email address corresponding with secret key.
				$email_address = $this->user->get_email_from_secret_key($secret_key);
			}
			if($email_address == ""){
				$retval['error_message'] = "SMS Gateway: Secret Key Invalid.";
				echo $retval;
				die();
			}
			//
			$billing_id = $this->config->item("smss_billing_id");

				//check that user token is ok....
			//set POST variables
			$url = $sms_server_url . "queue/queue_sms/";
			$fields = array(
						'application_id'=>urlencode($application_id),
						'billing_id'=>urlencode($billing_id),
						'phone'=>urlencode($this->input->post('phone')),
						'message_text'=>urlencode($this->input->post('message_text')),
						'schedule'=>urlencode($schedule),
						'callback_page'=>urlencode($callback_page)
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
					'secret_key'=>$secret_key,
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

}