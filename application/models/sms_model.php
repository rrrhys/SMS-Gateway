<?

class Sms_model extends CI_Model 
{
	public $errors = array();
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('email_model');
		$this->load->model('billing_model');
	}
	function queue_sms(	$application_id,$billing_id,$phone,$message_text,$schedule,$callback_page,$secret_key){
		$user = $this->user_model->get_user_from_secret_key($secret_key);
		$retval = array('result'=>'fail','error_message'=>'');
		$sms_server_url = $this->config->item("smss_sms_server_url");
		$url = $sms_server_url . "queue/queue_sms/";
			$fields = array(
						'application_id'=>urlencode($application_id),
						'billing_id'=>urlencode($billing_id),
						'phone'=>urlencode($phone),
						'message_text'=>urlencode($message_text),
						'schedule'=>urlencode($schedule),
						'callback_page'=>urlencode($callback_page)
					);

			//url-ify the data for the POST
			$fields_string = "";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string,'&');

			//open connection
			$ch = curl_init();
			if(get_env() == "DEV"){
			curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888'); 
			}
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			//execute post
			$result = curl_exec($ch);
			$return_object = json_decode($result);
			
			
			log_message('debug', "Queue URL: $url");	
			log_message('debug', "Queue data: $fields_string");	
			log_message('debug', "Return Object: $result");	

			if($return_object && $return_object->result == "success")
			{
				//sms was queued successfully - add it to transaction log.
				$insert = array(
					'id'=>$return_object->sms_id,
					'phone'=>$phone,
					'message_text'=>$message_text,
					'schedule'=>$schedule,
					'secret_key'=>$secret_key,
					'owner_id'=>$user['id'],
					'time_queued'=>date ("Y-m-d H:i:s",now()),
					'company_id'=>'');
					$this->db->insert('sms',$insert);
				$this->billing_model->use_credit($user['id'],1,$return_object->sms_id);
				$retval['result'] = "success";
				$retval['error_message'] = "";
			}
			else
			{
				//something went wrong? echo a fail object.
				if($return_object)
				{
				$retval['error_message'] = $return_object->error->message;
				}
				else
				{
				$retval['error_message'] = "An internal error occurred.";
				}
			}
			//close connection
			curl_close($ch);
				if($retval['result'] == "success"){
					$this->session->set_flashdata("flash","Message to ".$this->input->post('phone') ." queued successfully");
				}
				return $retval;
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