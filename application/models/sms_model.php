<?

class Sms_model extends CI_Model 
{
	public $errors = array();
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('email_model');
	}

	function save_template($owner_id, $template_name, $template_text, $fields_required){
		$new_template_id = get_uuid();
		$template['owner_id'] = $owner_id;
		if($template_text == ""){
			$this->errors[] = "Template text must be entered.";
			return false;
		}
		if($template_name == ""){
			$this->errors[] = "Template name must be entered.";
			return false;
		}
		$template['id'] = $new_template_id;
		$template['name'] = $template_name;
		$template['text'] = $template_text;
		
		foreach($fields_required as $field_required)
		{
			$insert = array();
			$insert['template_id'] = $new_template_id;
			$insert['id'] = get_uuid();
			$insert['name'] = $field_required;
			$this->db->insert('template_fields',$insert);
		}
		$this->db->insert('templates',$template);


		return $new_template_id;
	}
	function get_templates_by_user_id($id){

		$this->db->where('owner_id',$id);
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
		return $templates;
		//echo json_encode($q);
	}
}