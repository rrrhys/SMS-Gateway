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
			$this->check_quota_and_reset($user['id']);
		}
	}
	public function check_quota_and_reset($owner_id){
		$user = $this->user_model->get_user_by_id($owner_id);
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
	public function get_quota($owner_id){
		
		$this->db->where('active',1);
		$this->db->where('owner_id',$owner_id);
		$q = $this->db->get('billing')->row_array();
		return $q;
	}
	public function get_billing_breakdown($owner_id){
		$this->check_quota_and_reset($owner_id);
		$this->db->select('billing.period_start,billing.period_end,active,billing_detail.*,sms.phone, sms.message_text');
		$this->db->from('billing');
		$this->db->join('billing_detail','billing.id = billing_detail.billing_id');
		$this->db->join('sms','sms.id = billing_detail.sms_id');
		$this->db->where('billing.owner_id',$owner_id);

		$details = $this->db->get()->result_array();
		$summary = $this->get_quota($owner_id);
		return array('details'=>$details,'summary'=>$summary);
	}
	public function use_credit($owner_id,$credit_count,$transaction_id){
		$this->check_quota_and_reset($owner_id);
		$account = $this->get_quota($owner_id);
		$sms_available = $account['sms_available'];
		$sms_available -= $credit_count;
		if($sms_available<0){
			return false;
		}
		$insert = array('id'=>get_uuid(),
						'sms_id'=>$transaction_id,
						'owner_id'=>$owner_id,
						'billing_id'=>$account['id'],
						'credit_used'=>$credit_count);
		$this->db->insert('billing_detail',$insert);
		$this->db->where('owner_id',$owner_id);
		$this->db->update('billing',array('sms_available'=>$sms_available));

		return true;
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