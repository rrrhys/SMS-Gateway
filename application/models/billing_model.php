<?

class Billing_model extends CI_Model 
{
	public $default_sms_amount = 10;
	public $errors = array();
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('email_model');
	}

	public function check_quotas_and_resets(){
		$users = $this->user_model->get_users();
		foreach($users as $user){
			$q = $this->get_quota($user['id']);
			if($q){
				//is the date higher than the billing end date?
				//roll it over if so.
				$period_end_date = strtotime($q['period_end']);
				if($period_end_date < time()){
					//reset this users quota.
					$this->db->where('owner_id',$q['owner_id']);
					$this->db->update('billing',array('active'=>false));
					$this->_reset_quota($q['owner_id']);
				}
			}
			else{
				$this->_reset_quota($user['id']);
			}
		}
	}
	public function get_quota($owner_id){
		$this->db->where('active',1);
		$this->db->where('owner_id',$owner_id);
		$q = $this->db->get('billing')->row_array();
		return $q;
	}
	public function get_quota_by_id($id){
		$this->db->where('id',$id);
		$q = $this->db->get('billing')->row_array();
		return $q;
	}
	public function _reset_quota($owner_id){
		$date = date("Y-m-d");// current date
		$insert = array('id'=>get_uuid(),
						'owner_id'=>$owner_id,
						'sms_available'=>$this->default_sms_amount,
						'period_start'=>date( 'Y-m-d H:i:s', time()),
						'period_end'=>date("Y-m-d H:i:s", strtotime(Date("Y-m-d H:i:s") . " +1 month")),
						'active'=>1
						);
		$this->db->where('owner_id',$owner_id);
		$this->db->update('billing',array('active'=>0));

		$this->db->insert('billing',$insert);
	}
	public function bill_sms($owner_id,$sms_id){
		$data = $this->_base_data();
		//lower the users avaialble SMS by one and return true.
		//if there is none available or no data, return false.	
	}
}