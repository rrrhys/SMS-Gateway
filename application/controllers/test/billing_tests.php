<?php
require_once(APPPATH . '/controllers/test/Toast.php');

class Billing_tests extends Toast
{
	public $data = array();
	public $ids = array();
	public $date;// current date
	function Billing_tests()
	{
		parent::Toast(__FILE__);
		// Load any models, libraries etc. you need here
		$this->load->model('user_model');
		$this->load->model('sms_model');
		$this->load->model('billing_model');
		$this->config->config['unit_tests_running'] = true;
	}

	/**
	 * OPTIONAL; Anything in this function will be run before each test
	 * Good for doing cleanup: resetting sessions, renewing objects, etc.
	 */
	function _pre() {
		unset($this->ids);
		$this->ids = array();
		$this->date = date("Y-m-d");
		$this->load->model('user_model');
		$this->data['user']['fake_email'] = "foo@bar.com";
		$this->data['user']['password'] = "insecure_pass";
		$this->data['user']['timezone'] = "UP10";
		$this->ids[] = $this->user_model->register(	$this->data['user']['fake_email'], 
													$this->data['user']['password'], 
													$this->data['user']['timezone']
												);
		$insert_id = get_uuid();
		//gc
		$this->ids[] = $insert_id;
		$this->data['quota'] = array('id'=>$insert_id,
						'owner_id'=>$this->ids[0],
						'sms_available'=>10,
						'period_start'=>date( 'Y-m-d H:i:s', time()),
						'period_end'=>date("Y-m-d H:i:s", strtotime($this->date . " +1 month")),
						'active'=>1
						);
		
	}

	/**
	 * OPTIONAL; Anything in this function will be run after each test
	 * I use it for setting $this->message = $this->My_model->getError();
	 */
	function _post() {
		foreach($this->ids as $id){
			$this->db->where('id',$id);
			$this->db->delete('billing');
			$this->db->where('id',$id);
			$this->db->delete('users');
			$this->db->where('owner_id',$id);
			$this->db->delete('billing');
		}
	}
	function test_quota_returns_successfully(){
		
		$insert = $this->data['quota'];
		$this->db->insert('billing',$insert);
		$quota = $this->billing_model->get_quota($this->ids[0]);
		if($this->_assert_true(isset($quota['owner_id']))){
			$this->output("Correctly returns quota");
		}
		else{
			$this->output("Does not return any quota");
			$this->output(json_encode($quota));
		}		
		if($this->_assert_equals($quota['owner_id'],$this->ids[0])){
			$this->output("Correctly returns quota");
		}
		else{
			$this->output("Does not return correct quota");
			$this->output(json_encode($quota));
		}

	}

	function test_expired_quota_resets_successfully(){

		$insert = $this->data['quota'];
		//collect for GC.
		$insert['period_end']=date("Y-m-d H:i:s", strtotime($this->date . " - 5 minute"));
		$this->db->insert('billing',$insert);
		
		$this->billing_model->check_quotas_and_resets();
		$new_quota = $this->billing_model->get_quota($this->ids[0]);
		//collect for GC.
		$this->ids[] = $new_quota['id'];
		$old_quota = $this->billing_model->get_quota_by_id($insert['id']);
		$this->_assert_not_equals($new_quota['id'],$old_quota['id']);

	}
	function test_current_quota_does_not_reset(){

		$insert = $this->data['quota'];
		$insert['period_end']=date("Y-m-d H:i:s", strtotime($this->date . " + 5 minute"));
		$this->db->insert('billing',$insert);

		$this->billing_model->check_quotas_and_resets();
		$new_quota = $this->billing_model->get_quota($this->ids[0]);
		//GC
		$this->ids[] = $new_quota['id'];
		$old_quota = $this->billing_model->get_quota_by_id($insert['id']);
		$this->_assert_not_equals($new_quota['id'],$old_quota['id']);
	}
	function test_queue_sms_decreases_quota(){
		
		$this->_assert_true(false);
	}
	/*
	function test_quota_creates_if_not_exist(){
		$this->_assert_true(false);
	}*/

	/* TESTS BELOW */
	function output($message){
		$this->message .= ($this->message ? "<br />"  : "") . $message;
	}

}

// End of file example_test.php */
// Location: ./system/application/controllers/test/example_test.php */