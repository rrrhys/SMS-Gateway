<?

class Email_model extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function send_email($email_template_name,$data)
	{
	$this->load->library('email');
	$email_id = get_uuid();
	log_message('debug', "_send_email handler: $email_template_name, ID: $email_id");	
	

		switch($email_template_name){
			case("activation_email"):
				$this->db->where('id',$data['user_id']);
				$q = $this->db->get('users')->result_array();
				$q=  $q[0];
				$data['activation_key'] = $q['activation_key'];
				$data['from_name'] = $this->config->config['product_name'];
				$data['from_email'] = $this->config->config['generic_email'];
				$data['bcc_email'] = $this->config->config['carbon_copy_email'];
				$data['to_email'] = $q['email_address'];
				$data['server'] = $_SERVER['SERVER_NAME'];
				$email = $this->load->view("emails/".$email_template_name,$data,true);
			break;
		}
		log_message('debug', "email data:: . " . json_encode($data),true);	
			$this->load->library('email');
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = "html";

			$this->email->initialize($config);
			$this->email->from($data['from_email'], $data['from_name']);
			$this->email->to($data['to_email']); 
			$this->email->bcc($data['bcc_email']); 

			$this->email->subject("Activate your new account");
			$this->email->message($email);	

			$this->email->send();

			return $this->email->print_debugger();
		
		
	}

}