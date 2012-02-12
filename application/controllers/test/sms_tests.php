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
		$this->data['user']['fake_email'] = "foo@bar.com";
		$this->data['user']['password'] = "insecure_pass";
		$this->data['user']['timezone'] = "UP10";
		$this->ids[] = $this->user_model->register(	$this->data['user']['fake_email'], 
													$this->data['user']['password'], 
													$this->data['user']['timezone']
												);
		$this->data['template']['name'] = "Test Template";
		$this->data['template']['text'] = "Template Text";
		$this->data['template']['fields_required'] = array('field_a','field_b');
		$this->ids[] = $this->sms_model->save_template(
					$this->ids[0],
					$this->data['template']['name'],
					$this->data['template']['text'],
					$this->data['template']['fields_required']
			);
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
	function count_template(){
		return $this->db->get('templates')->num_rows();

	}
	function test_add_good_template_saves(){
		$count_templates = $this->count_template();
		$new_id = $this->sms_model->save_template(
					$this->ids[0],
					$this->data['template']['name'],
					$this->data['template']['text'],
					$this->data['template']['fields_required']
			);
		$this->ids[] = $new_id;
		if($this->_assert_equals($count_templates+1,$this->count_template())){
			$this->output("Good template saved.");
		}
		else {
			$this->output("Good template incorrectly fails.");
		}
	}
	function test_list_templates_works(){
		$templates = $this->sms_model->get_templates_by_user_id($this->ids[0]);
		if($this->_assert_equals($templates[0]['name'],"Test Template")){
			$this->output("Templates List correctly.");
		}
		else {
			$this->output("Templates list incorrectly.");
		}
	}

	function test_add_bad_templates_fail(){
		$count_templates = $this->count_template();
		$new_id = $this->sms_model->save_template(
					$this->ids[0],
					"",
					$this->data['template']['text'],
					$this->data['template']['fields_required']
			);
		$this->ids[] = $new_id;
		if($this->_assert_equals($count_templates,$this->count_template())){
			$this->output("Bad template (no name) correctly fails.");
		}
		else {
			$this->output("Bad template (no name) incorrectly saves.");
		}

		$count_templates = $this->count_template();
		$new_id = $this->sms_model->save_template(
					$this->ids[0],
					$this->data['template']['name'],
					"",
					$this->data['template']['fields_required']
			);
		$this->ids[] = $new_id;
		if($this->_assert_equals($count_templates,$this->count_template())){
			$this->output("Bad template (no text) correctly fails.");
		}
		else {
			$this->output("Bad template (no text) incorrectly saves.");
		}
	}

}

// End of file example_test.php */
// Location: ./system/application/controllers/test/example_test.php */