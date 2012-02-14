<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billing extends MY_Controller {
	function __construct()
	{
		parent::__construct();
	$this->load->model("user_model");
	$this->load->model('billing_model');
	}

	public function summary(){
		$data = $this->_base_data();
		$data['title'] = "Billing Summary";
		$data['billing_summary'] = $this->billing_model->get_billing_breakdown($this->session->userdata('id'));
		$this->load->view('header',$data);
		$this->load->view('billing_summary',$data);
		$this->load->view('footer',$data);
	}

}