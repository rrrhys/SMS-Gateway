<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class Sms_tests extends Toast
{
	public $data = array();
	public $ids = array();
	function Sms_tests()
	{
		parent::Toast(__FILE__);
		// Load any models, libraries etc. you need here
		$this->load->model('user_model');
		$this->load->model('sms_model');
		$this->config->config['unit_tests_running'] = true;
	}

	/**
	 * OPTIONAL; Anything in this function will be run before each test
	 * Good for doing cleanup: resetting sessions, renewing objects, etc.
	 */
	function _pre() {
		$this->load->model('user_model');
		$this->data['fake_email'] = "foo@bar.com";
		$this->data['password'] = "insecure_pass";
		$this->data['timezone'] = "UP10";
		$this->ids[] = $this->user_model->register($this->data['fake_email'], $this->data['password'], $this->data['timezone']);
	}

	/**
	 * OPTIONAL; Anything in this function will be run after each test
	 * I use it for setting $this->message = $this->My_model->getError();
	 */
	function _post() {
		foreach($this->ids as $id){
		$this->db->where('id',$id);
		$this->db->delete('templates');
		$this->db->where('template_id',$id);
		$this->db->delete('template_fields');
		$this->db->where('id',$id);
		$this->db->delete('users');
		}
	}


	/* TESTS BELOW */
	function output($message){
		$this->message .= ($this->message ? "<br />"  : "") . $message;
	}
	function test_good_template_saves(){
		
		$new_id = $this->sms_model->save_template(
					$this->ids[0],
					"Test Template",
					"Template text",
					array('field_a','field_b')
			);
		$this->ids[] = $new_id;
		if(!$this->_assert_true($new_id)){
			$this->output("Good template should save.");
		}
		else {
			$this->output("Good template incorrectly fails.");
		}
	}

	function test_bad_templates_fail(){
		$new_id = $this->sms_model->save_template(
					$this->ids[0],
					"",
					"Template text",
					array('field_a','field_b')
			);
		$this->ids[] = $new_id;
		if(!$this->_assert_false($new_id)){
			$this->output("Bad template should fail.");
		}
		else {
			$this->output("Bad template incorrectly saves.");
		}
		

		

	}

}

// End of file example_test.php */
// Location: ./system/application/controllers/test/example_test.php */