<?if ( ! defined('PHP_EOL'))
{
	define('PHP_EOL', (DIRECTORY_SEPARATOR == '/') ? "\n" : "\r\n");
} 
if ( ! function_exists('get_env'))
{
	function get_host(){
		$base_url = strtolower($_SERVER['HTTP_HOST']);
		$host = substr($base_url,strpos($base_url,".")+1);

		//strpos ( string $haystack , mixed $needle [, int $offset = 0 ] )
	}
	function get_env()
	{
		$environment = "PROD"; //assume production
		$base_url = strtolower($_SERVER['HTTP_HOST']);
		if($base_url == "test.smsgateway.dev"){
			$environment = "DEV";
		}
		else
		{
			$environment = "PROD";
		}
		return $environment;
	}	
}