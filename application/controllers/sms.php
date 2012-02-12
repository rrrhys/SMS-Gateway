<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms extends MY_Controller {
	function __construct()
	{
		parent::__construct();
	$this->load->model("sms_model");

	}
	public function api_reference(){
		$data = $this->_base_data();
		$data['title'] = "API Reference";
		$this->load->view('header',$data);
		$this->load->view('api_reference',$data);
		$this->load->view('footer',$data);		
	}

	public function templates()
	{
		$data = $this->_base_data();
		$data['title'] = "Templates - Set up ready to go SMS messages";
		$this->load->view('header',$data);
		$this->load->view('templates',$data);
		$this->load->view('footer',$data);
	}

	public function create_sms(){
		$data = $this->_base_data();
		$data['title'] = "Create a new SMS with SMS Gateway";
		$this->load->view('header',$data);
		$this->load->view('create_sms',$data);
		$this->load->view('footer',$data);
	}

	public function get_templates_json()
	{
		$this->db->where('owner_id',$this->session->userdata('id'));
		$q = $this->db->get('templates')->result_array();
		$templates =array();
		foreach($q as $row)
		{
			$this->db->where('template_id',$row['id']);
			$fields = $this->db->get('template_fields')->result_array();
			$row['fields_required'] = array();
			foreach($fields as $field)
			{
			$row['fields_required'][] = $field['name'];
			}
			$templates[] = ($row);
		}
		echo json_encode(array('templates'=>$templates));
		//echo json_encode($q);
	}
	public function save_template()
	{
		$fields_required = explode("|",$this->input->post("fields_required"));
		$this->sms_model->save_template(	$this->session->userdata('id'),
																			$this->input->post("name"),
																			$this->input->post("text"),
																			$fields_required);

	}

}