<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Toast
 * 
 * JUnit-style unit testing in CodeIgniter. Requires PHP 5 (AFAIK). Subclass
 * this class to create your own tests. See the README file or go to
 * http://jensroland.com/projects/toast/ for usage and examples.
 * 
 * RESERVED TEST FUNCTION NAMES: test_index, test_show_results, test__[*]
 * 
 * @package			CodeIgniter
 * @subpackage	Controllers
 * @category		Unit Testing
 * @based on		Brilliant original code by user t'mo from the CI forums
 * @based on		Assert functions by user 'redguy' from the CI forums
 * @license			Creative Commons Attribution 3.0 (cc) 2009 Jens Roland
 * @author			Jens Roland (mail@jensroland.com)
 * 
 */


abstract class Toast extends CI_Controller
{
	// The folder INSIDE /controllers/ where the test classes are located
	// TODO: autoset
	var $test_dir = '/test/';

	var $modelname;
	var $modelname_short;
	var $message;
	var $messages;
	var $asserts;

	function Toast($name)
	{
		parent::__construct();
		$this->load->library('unit_test');
		$this->modelname = $name;
		$this->modelname_short = basename($name, '.php');
		$this->messages = array();
		$this->config->config['unit_tests_running'] = true;
	}

	function index()
	{
		$this->_show_all();
	}

	function show_results()
	{
		$this->_run_all();
		$data['modelname'] = $this->modelname;
		$data['results'] = $this->unit->result();
		$data['messages'] = $this->messages;
		$this->load->view('test/results', $data);
	}	
	function show_results_json()
	{
		$this->_run_all();
		$data['modelname'] = $this->modelname;
		$data['results'] = $this->unit->result();
		$data['messages'] = $this->messages;
		echo json_encode($data);
	}

	function _show_all()
	{
		$this->_run_all();
		$data['modelname'] = $this->modelname;
		$data['results'] = $this->unit->result();
		$data['messages'] = $this->messages;

		$this->load->view('test/header');
		$this->load->view('test/results', $data);
		$this->load->view('test/footer');
	}

	function _show($method)
	{
		$this->_run($method);
		$data['modelname'] = $this->modelname;
		$data['results'] = $this->unit->result();
		$data['messages'] = $this->messages;

		$this->load->view('test/header');
		$this->load->view('test/results', $data);
		$this->load->view('test/footer');
	}

	function _run_all()
	{
		foreach ($this->_get_test_methods() as $method)
		{
			$this->_run($method);
		}
	}

	function _run($method)
	{
		// Reset message from test
		$this->message = '';

		// Reset asserts
		$this->asserts = TRUE;

		// Run cleanup method _pre
		$this->_pre();

		// Run test case (result will be in $this->asserts)
		$this->$method();

		// Run cleanup method _post
		$this->_post();

		// Set test description to "model name -> method name" with links
		$this->load->helper('url');
		$test_class_segments = $this->test_dir . strtolower($this->modelname_short);
		$test_method_segments = $test_class_segments . '/' . substr($method, 5);
		$desc = anchor($test_class_segments, $this->modelname_short) . ' -> ' . anchor($test_method_segments, substr($method, 5));

		$this->messages[] = $this->message;

		// Pass the test case to CodeIgniter
		$this->unit->run($this->asserts, TRUE, $desc);
	}

	function _get_test_methods()
	{
		$methods = get_class_methods($this);
		$testMethods = array();
		foreach ($methods as $method) {
			if (substr(strtolower($method), 0, 5) == 'test_') {
				$testMethods[] = $method;
			}
		}
		return $testMethods;
	}

	/**
	 * Remap function (CI magic function)
	 * 
	 * Reroutes any request that matches a test function in the subclass
	 * to the _show() function.
	 * 
	 * This makes it possible to request /my_test_class/my_test_function
	 * to test just that single function, and /my_test_class to test all the
	 * functions in the class.
	 * 
	 */
	function _remap($method)
	{
		$test_name = 'test_' . $method;
		if (method_exists($this, $test_name))
		{
			$this->_show($test_name);
		}
		else
		{
			$this->$method();
		}
	}


	/**
	 * Cleanup function that is run before each test case
	 * Override this method in test classes!
	 */
	function _pre() { }

	/**
	 * Cleanup function that is run after each test case
	 * Override this method in test classes!
	 */
	function _post() { }


	function _fail($message = null) {
		$this->asserts = FALSE;
		if ($message != null) {
			$this->message = $message;
		}
		return FALSE;
	}
	
	function _assert_true($assertion) {
		if($assertion) {
			return TRUE;
		} else {
			$this->asserts = FALSE;
			return FALSE;
		}
	}
	
	function _assert_false($assertion) {
		if($assertion) {
			$this->asserts = FALSE;
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function _assert_true_strict($assertion) {
		if($assertion === TRUE) {
			return TRUE;
		} else {
			$this->asserts = FALSE;
			return FALSE;
		}
	}
	
	function _assert_false_strict($assertion) {
		if($assertion === FALSE) {
			return TRUE;
		} else {
			$this->asserts = FALSE;
			return FALSE;
		}
	}
	
	function _assert_equals($base, $check) {
		if($base == $check) {
			return TRUE;
		} else {
			$this->asserts = FALSE;
			return FALSE;
		}
	}
	
	function _assert_not_equals($base, $check) {
		if($base != $check) {
			return TRUE;
		} else {
			$this->asserts = FALSE;
			return FALSE;
		}
	}

	function _assert_equals_strict($base, $check) {
		if($base === $check) {
			return TRUE;
		} else {
			$this->asserts = FALSE;
			return FALSE;
		}
	}

	function _assert_not_equals_strict($base, $check) {
		if($base !== $check) {
			return TRUE;
		} else {
			$this->asserts = FALSE;
			return FALSE;
		}
	}

	function _assert_empty($assertion) {
		if(empty($assertion)) {
			return TRUE;
		} else {
			$this->asserts = FALSE;
			return FALSE;
		}
	}
	
	function _assert_not_empty($assertion) {
		if(!empty($assertion)) {
			return TRUE;
		} else {
			$this->asserts = FALSE;
			return FALSE;
		}
	}
		function curl_get($url,$kv_array){
					//open connection
					$url = $_SERVER['SERVER_NAME'] . $url;
			$fields_string = "";
			foreach($kv_array as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string,'&');

			$ch = curl_init();
			if(get_env() == "DEV"){
			curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888'); 
			}
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,count($kv_array));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			//execute post
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
	}
	

}

// End of file Toast.php */
// Location: ./system/application/controllers/test/Toast.php */ 
