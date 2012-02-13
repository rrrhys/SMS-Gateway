<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class Basic_ui_tests extends Toast
{
	function Basic_ui_tests()
	{
		parent::Toast(__FILE__);
		// Load any models, libraries etc. you need here
		$this->config->config['unit_tests_running'] = true;
	}

	/**
	 * OPTIONAL; Anything in this function will be run before each test
	 * Good for doing cleanup: resetting sessions, renewing objects, etc.
	 */
	function _pre() {

	}

	/**
	 * OPTIONAL; Anything in this function will be run after each test
	 * I use it for setting $this->message = $this->My_model->getError();
	 */
	function _post() {

	}

	function test_visit_register_page_loads(){
		$page = $this->curl_get("/user/register");
		$this->_assert_true(strpos($page,"<legend>Register</legend>")>1);
	}
	function test_visit_login_page_loads(){
		$page = $this->curl_get("/user/login");
		$this->_assert_true(strpos($page,"<legend>Log-in</legend>")>1);
	}

	function curl_get($url){
					//open connection
					$url = $_SERVER['SERVER_NAME'] . $url;
			$ch = curl_init();
			if(get_env() == "DEV"){
			curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888'); 
			}
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$url);
			//curl_setopt($ch,CURLOPT_POST,count($fields));
			//curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			//execute post
			$result = curl_exec($ch);
			return $result;
	}
	/* TESTS BELOW */
	function output($message){
		$this->message .= ($this->message ? "<br />"  : "") . $message;
	}

}

// End of file example_test.php */
// Location: ./system/application/controllers/test/example_test.php */