<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class Sms_controller_tests extends Toast
{
	public $data = array();
	public $ids = array();
	function Sms_controller_tests()
	{
		parent::Toast(__FILE__);
		// Load any models, libraries etc. you need here
		$this->load->model('user_model');
	}

	function test_queue_over_curl(){
		$user = $this->user_model->get_user_by_email($this->data['user']['fake_email']);
		$secret_key = $user['secret_key'];
		$test_message = "THIS IS A TEST";

		$result =  json_decode($this->curl_get("/sms/queue_sms/",array(
				'action'=>'send',
				'phone'=>'0404 123 300',
				'message_text'=>$test_message,
				'schedule'=>'now',
				'secret_key'=>$secret_key
			)));
			if($this->_assert_equals($result->result,"success")){
				$this->output("Added to queue successfully");
			}

			$result =  $this->curl_get("/sms/get_dashboard_queued_json/" . $secret_key,array()
			);
			//echo $result;
			$obj = json_decode($result);
			if($this->_assert_equals($obj->sms_queued[0]->message_text,$test_message)){
				$this->output("Queue returns successfully.");
			}
		}
	
	function test_activation_login_dashboard(){
		//log user in over POST.



		//get user details so I have key.
		$user = $this->user_model->get_user_by_email($this->data['user']['fake_email']);
		if($this->_assert_equals($user['active'],0)){
			$this->output("New user is (correctly) initially inactive");
		}
		else{$this->output("New user incorrectly initially inactive");}
		//activate this guy
		$result = $this->curl_get("/user/activate",array(
			'activation_key'=>$user['activation_key']));
		$user = $this->user_model->get_user_by_email($this->data['user']['fake_email']);
		if($this->_assert_equals($user['active'],1)){
			$this->output("New user activated correctly! (Over Curl)");
		}
		else{$this->output("New user failed activation! (Over Curl)");}

		$secret_key = $user['secret_key'];

		$result = $this->curl_get("/user/dashboard",array(
			'login_secret_key'=>$user['secret_key'],
			'login_email_address'=>$user['email_address']));
			$match_string = "<title>Dashboard - SMS Sent and Queued</title>";
		if($this->_assert_true(strpos($result, $match_string))){
				$this->output("New user dashboard loads correctly!");
			}else{
				$this->output("New user dashboard does not load correctly. Could not find " . strip_tags($match_string));
			}
	}

	function curl_get($url,$kv_array){
					//open connection
					$url = $_SERVER['SERVER_NAME'] . $url;
			$fields_string = "";
			foreach($kv_array as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string,'&');

			$ch = curl_init();
			if(get_env() == "DEV"){
			curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888'); 
			}
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,count($kv_array));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			//execute post
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
	}
	/**
	 * OPTIONAL; Anything in this function will be run before each test
	 * Good for doing cleanup: resetting sessions, renewing objects, etc.
	 */
	function _pre() {
		$this->load->model('user_model');
		$this->data['user']['fake_email'] = "foo@bar.com";
		$this->data['user']['password'] = "insecure_pass";
		$this->data['user']['timezone'] = "UP10";
		$this->ids[] = $this->user_model->register(	$this->data['user']['fake_email'], 
													$this->data['user']['password'], 
													$this->data['user']['timezone']
												);

	}

	/**
	 * OPTIONAL; Anything in this function will be run after each test
	 * I use it for setting $this->message = $this->My_model->getError();
	 */
	function _post() {
		foreach($this->ids as $id){
		$this->db->where('id',$id);
		$this->db->delete('users');
		$this->db->where('owner_id',$id);
		$this->db->delete('sms');
		}
	}


	/* TESTS BELOW */
	function output($message){
		$this->message .= ($this->message ? "<br />"  : "") . $message;
	}

}

// End of file example_test.php */
// Location: ./system/application/controllers/test/example_test.php */