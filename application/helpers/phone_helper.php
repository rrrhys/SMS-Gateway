<?if ( ! defined('PHP_EOL'))
{
	define('PHP_EOL', (DIRECTORY_SEPARATOR == '/') ? "\n" : "\r\n");
} 
if ( ! function_exists('phone_number_valid'))
{
	function phone_number_valid($phone)
	{
		$phone = properly_format_phone($phone);
		if(strlen($phone) != 10 || $phone == "")
		{
		log_message('debug', "Length of $phone is too short at " . strlen($phone));	
		return false;
		}
		else
		{
		return true;
		}
	}
	
}
if ( ! function_exists('properly_format_phone'))
{
	function properly_format_phone($phone)
	{
	$phone = urldecode($phone);
echo $phone;
echo "<br />";
$phone_new = "";
for($i=0;$i<strlen($phone);$i++)
{
	$char = substr($phone,$i,1);
	if(strpos("01234567890",$char) > -1)
	{
	$phone_new .= $char;
	}
}
$phone = $phone_new;
unset($phone_new);
		$phone = str_replace(" ","",$phone);
if(substr($phone,0,2) == "61")
{
$phone = "0" . substr($phone,2);
}
return $phone;
	}
	
}