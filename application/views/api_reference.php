<h1 id=""><?=$title?></h1>



<h3>Queueing an SMS</h3>
Post your URL Encoded values to <code>http://<?=$_SERVER['SERVER_NAME']?></code>. A cURL request is below for your information, and a detailed break-up of variables used<br />
<pre class="prettyprint
     linenums">
curl http://test.smsgateway.dev/user/queue_sms \
   -d action=send \
   -d "phone=0404 123 300" \
   -d "message_text=Hi Mr Tester" \
   -d "schedule=now" \
   -d "secret_key=11111111111111.1111111"
</pre>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Post Variable Name</th>
			<th>Example Data</th>
			<th>Explanation</th>
			<th>Required?</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>action</td>
			<td>"send" or ""</td>
			<td></td>
			<td><i class="icon-remove"></i></td>
		</tr>
		
		<tr>
			<td>phone</td>
			<td>0404 123 300</td>
			<td>The "To" phone number of the SMS</td>
			<td><i class="icon-ok"></i></td>
		</tr>
			<tr>
			<td>message_text</td>
			<td>Hi there Sam, please call us on 0400 123 123</td>
			<td>The contents of your SMS message</td>
			<td><i class="icon-ok"></i></td>
		</tr>	
		<tr>
			<td>schedule</td>
			<td>now <strong>or</strong> 2012-06-06 14:32:00</td>
			<td>Schedule is the time and date you want your SMS delivered. You can specify a time in <strong>YYYY-MM-DD hh:mm:ss</strong> format or <strong>now</strong> to send immediately</td>
			<td><i class="icon-ok"></i></td>
		</tr>
		<tr>
			<td>secret_key</td>
			<td>11111111111111.22222222</td>
			<td>Your Secret Key as seen in your Account Settings.<br />Used to bill you correctly for your SMS usage and return account information</td>
			<td><i class="icon-ok"></i></td>
		</tr>
		</tr>
			<tr>
			<td>callback_page</td>
			<td>http://www.myserver.com/my_callback_page.php</td>
			<td>A page on your server that SMS Gateway can post information about your SMS to - like a delivery confirmation.</td>
			<td><i class="icon-remove"></i></td>
		</tr>	
	</tbody>
</table>
<h3>List Templates</h3>
<pre class="prettyprint
     linenums">
curl http://test.smsgateway.dev/user/get_templates_json
</pre>
<p><br />Page rendered in {elapsed_time} seconds</p>
