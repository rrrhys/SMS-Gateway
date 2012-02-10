<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	/*public function _register_with_smsserver($client_name,$accept_sms,$send_sms)
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

	}*/

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
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */