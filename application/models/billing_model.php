<?

class Billing_model extends CI_Model 
{
	public $errors = array();
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('email_model');
	}
	public function reset_quota($user_id,$quota){
		$insert = array('id'=>get_uuid(),
						'owner_id'=>$user_id,
						'sms_available'=>10,
						'period_start'=>date( 'Y-m-d H:i:s', date()),
						'period_end'=>date( 'Y-m-d H:i:s', strtotime(date() . " + 1month"))
						)
	}
	public function bill_sms($owner_id,$sms_id){
		$data = $this->_base_data();
		//lower the users avaialble SMS by one and return true.
		//if there is none available or no data, return false.	
	}
}