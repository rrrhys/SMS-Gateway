<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class User_tests extends Toast
{
	public $data = array();
	public $ids = array();
	function User_tests()
	{
		parent::Toast(__FILE__);
		// Load any models, libraries etc. you need here
		$this->load->model('user_model');
		$this->config->config['unit_tests_running'] = true;
	}

	/**
	 * OPTIONAL; Anything in this function will be run before each test
	 * Good for doing cleanup: resetting sessions, renewing objects, etc.
	 */
	function _pre() {
		$this->data['fake_email'] = "foo@bar.com";
		$this->data['password'] = "insecure_pass";
		$this->data['timezone'] = "UP10";
		$this->ids = array();
	}

	/**
	 * OPTIONAL; Anything in this function will be run after each test
	 * I use it for setting $this->message = $this->My_model->getError();
	 */
	function _post() {
		$this->db->where('email_address',$this->data['fake_email']);
		$this->db->delete('users');
	}


	/* TESTS BELOW */
	function output($message){
		$this->message .= ($this->message ? "<br />"  : "") . $message;
	}
	function test_registration_can_occur(){
		
		$user_count = $this->user_model->count_users();
		$this->ids[] = $this->user_model->register($this->data['fake_email'], $this->data['password'], $this->data['timezone']);
			if(!$this->_assert_not_equals($user_count,$this->user_model->count_users())){
				$this->output("A good email address, password and timezone should register.");
			}
			else {
				$this->output("Good email address passed.");
			}
		$this->ids[] = $this->user_model->register($this->data['fake_email'], $this->data['password'], $this->data['timezone']);
			if(!$this->_assert_not_equals($user_count+1,$this->user_model->count_users())){
				$this->output("Same email address incorrectly registers twice.");
			}
			else {
				$this->output("Same email address correctly fails.");
			}
	}
	function test_junk_emails_fail(){
		$user_count = $this->user_model->count_users();

		$this->ids[] = $this->user_model->register("", "foo bar", $this->data['timezone']);
		$user = $this->user_model->get_user_by_email($this->data['fake_email']);
		if(!$this->_assert_equals($user_count, $this->user_model->count_users())){
			$this->output("Empty Email should fail registration");
		}
		else
		{
			$this->output("Empty Email fails correctly");
		}

		$this->ids[] = $this->user_model->register("a@b", "foo bar", $this->data['timezone']);
		$user = $this->user_model->get_user_by_email($this->data['fake_email']);
		if(!$this->_assert_equals($user_count, $this->user_model->count_users())){
			$this->output("No domain extension should fail");
		}
		else
		{
			$this->output("No domain extension fails correctly");
		}
		/*$this->ids[] = $this->user_model->register("rrrhys@gmail@a.com", "foo bar", $this->data['timezone']);
		$user = $this->user_model->get_user_by_email($this->data['fake_email']);
		if(!$this->_assert_equals($user_count, $this->user_model->count_users())){
			$this->output("Too many @'s should fail");
		}
		else
		{
			$this->output("Too many @'s fails correctly");
		}*/
		foreach($this->ids as $id){
			$this->db->where('id',$id)->delete('users');
		}
	}
	function test_empty_password_fails(){

		$this->ids[] = $this->user_model->register($this->data['fake_email'], "", $this->data['timezone']);
		$user = $this->user_model->get_user_by_email($this->data['fake_email']);
		if(!$this->_assert_empty($user)){
			$this->output("A no password should fail registration");
		}
		else
		{
			$this->output("No password fails correctly");
		}
		foreach($this->ids as $id){
			$this->db->where('id',$id)->delete('users');
		}
	}
	function test_short_password_fails(){

		$this->ids[] = $this->user_model->register($this->data['fake_email'], "a", $this->data['timezone']);
		$user = $this->user_model->get_user_by_email($this->data['fake_email']);
		if(!$this->_assert_empty($user)){
			$this->output("A short password should fail registration");
		}
		else
		{
			$this->output("Short password fails correctly");
		}
		foreach($this->ids as $id){
			$this->db->where('id',$id)->delete('users');
		}
	}
	function test_simple_addition()
	{
		$var = 2 + 2;
		$this->_assert_equals($var, 4);
	}


	function test_that_fails()
	{
		$a = true;
		$b = $a;

		// You can test multiple assertions / variables in one function:

		$this->_assert_true($a); // true
		$this->_assert_false($b); // false
		$this->_assert_equals($a, $b); // true

		// Since one of the assertions failed, this test case will fail
	}


	function test_or_operator()
	{
		$a = true;
		$b = false;
		$var = $a || $b;

		$this->_assert_true($var);

		// If you need to, you can pass a message /
		// description to the unit test results page:

		$this->message = '$a || $b';
	}

}

// End of file example_test.php */
// Location: ./system/application/controllers/test/example_test.php */