<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billing extends MY_Controller {
	function __construct()
	{
		parent::__construct();
	$this->load->model("user_model");
	$this->load->model('billing_model');
	}

	public function summary(){
		echo json_encode($this->billing_model->get_billing_breakdown($this->session->userdata('id')));
	}

}