<?

class Queue_model extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	function queue_sms($application_id, $billing_id, $phone, $message_text, $schedule,$callback_page)
	{
	$this->load->helper('date');
		$retval = array('result'=>'fail','sms_id'=>'','error'=>array('code'=>0,'message'=>''));
		if(!$this->accept_sms_from_client($billing_id))
		{
			$retval['error']['code'] = -1;
			$retval['error']['message'] = "Billing ID not authorised to queue sms.";
			return $retval;
		}
		if(!$this->accept_sms_from_application($application_id))
		{
			$retval['error']['code'] = -1;
			$retval['error']['message'] = "Application ID not authorised to queue sms.";
			return $retval;		
		}
		
		//schedule manipulation.
		$new_sms_id = get_uuid();
		$new_sms = array(	'queued_time'=>date ("Y-m-d H:i:s",time()),
							'phone'=>$phone,
							'message_text'=>$message_text,
							'schedule'=>$schedule,
							'callback_page'=>$callback_page,
							'billing_id'=>$billing_id,
							'application_id'=>$application_id,
							'id'=>$new_sms_id);
		$q = $this->db->insert('primary_queue',$new_sms);					
		if($q == true)
		{
			$retval['result'] = "success";
			$retval['sms_id'] = $new_sms_id;
		}
		return $retval;
	}
	
	function accept_sms_from_client($billing_id)
	{
		$this->db->where(array('accept_sms'=>1,'id'=>$billing_id));
		$q = $this->db->get('clients')->result_array();
		return count($q) == 1;
	}
	function accept_sms_from_application($application_id)
	{
		$this->db->where(array('accept_sms'=>1,'id'=>$application_id));
		$q = $this->db->get('permitted_applications')->result_array();
		return count($q) == 1;
	}

}