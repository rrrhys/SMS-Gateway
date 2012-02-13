<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billing extends MY_Controller {
	function __construct()
	{
		parent::__construct();
	$this->load->model("user_model");

	//billing reset code goes here
	$this->_check_quota_resets();

	}

	public function set_quota($user_id, $quota){
		
	}


}