<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billing extends MY_Controller {
	function __construct()
	{
		parent::__construct();
	$this->load->model("user_model");

	//billing reset code goes here
	$this->_check_quota_resets();

	}
	public function bill_sms($owner_id,$sms_id){
		$data = $this->_base_data();
		//lower the users avaialble SMS by one and return true.
		//if there is none available or no data, return false.	
	}
	public function set_quota($user_id, $quota){
		
	}

	public function _check_quota_resets(){
		$users = $this->user_model->get_users();
		foreach($users as $user){
			$this->db->where('ower_id',$user['id']);
			$q = $this->db->get('billing')->row_array();
			if($q){
				//is the date higher than the billing end date?
				//roll it over if so.
				$period_end_date = strtotime($q['period_end']);
				if($period_end_date < date()){
					//reset this users quota.
					$this->db->where('owner_id',$q['owner_id']);
					$this->db->update('billing',array('active'=>false));
					$this->reset_quota($q['owner_id']);
				}
			}
			else{
				$this->reset_quota($q['owner_id']);
			}
		}
	}
}