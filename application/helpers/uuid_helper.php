<?if ( ! defined('PHP_EOL'))
{
	define('PHP_EOL', (DIRECTORY_SEPARATOR == '/') ? "\n" : "\r\n");
} 
if ( ! function_exists('get_uuid'))
{
	function get_uuid()
	{
		return uniqid("",true);
	}
	
}