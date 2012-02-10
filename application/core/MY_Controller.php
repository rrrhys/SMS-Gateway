<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('uuid');
		$this->load->helper('phone');
		$this->load->library('session');
		$this->load->model('user_model');
		$this->load->database();
	}
	function _base_data()
	{
		return array(
				'title'=>'SMS Gateway',
				'logged_in'=>$this->logged_in(),
				'flash'=>$this->session->flashdata('flash'),
				'error_flash'=>$this->session->flashdata('error_flash'),
				'notifications'=>$this->session->userdata('notifications'));
	}

	 public function uuid()
	 {
		for($i = 0; $i < 10; $i++)
		{
			echo get_uuid() . "<br />";
		}
	 }
	 public function logged_in(){
	 	return $this->session->userdata('email_address') == true;
	 }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */