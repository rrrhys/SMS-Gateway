<?

//ID of sms gateway front end
$application_id = '4e12c500660bc0.68748235';
//ID of sms gateway 'company'
$secret_key = "4e12ed4ac44201.92757863";
$sms_server_url = 'http://dev.smsserver.com.au/';
if($_POST["action"] == "send")
{
	//check that user token is ok....
	
//set POST variables
$url = $sms_server_url . "queue/queue_sms/";
$fields = array(
            'application_id'=>urlencode($application_id),
            'secret_key'=>urlencode($secret_key),
            'phone'=>urlencode($_POST['phone']),
            'message_text'=>urlencode($_POST['message_text']),
            'schedule'=>urlencode($_POST['schedule']),
            'callback_page'=>$_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']
        );

//url-ify the data for the POST
$fields_string = "";
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string,'&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

//execute post
$result = curl_exec($ch);

$return_object = json_decode($result);
//close connection
curl_close($ch);
	
}
if($_POST["action"] == "notify")
{
	//server is telling us about the sms sent.
}