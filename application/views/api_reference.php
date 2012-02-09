<h1 id=""><?=$title?></h1>
queue_sms()
vars:
billing_id = (your billing ID)
schedule = time to send message in YYYY-MM-DD HH:MM:SS format
2012-01-20 07:50:33
phone = phone number

Variables req
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
			<td>No</td>
		</tr>
		
		<tr>
			<td>phone</td>
			<td>0404 123 300</td>
			<td>The "To" phone number of the SMS</td>
			<td><i class="icon-ok"></i></td>
		</tr>
		
		<tr>
			<td>billing_id</td>
			<td>4f1917e4559160.42676707</td>
			<td>Your billing ID as seen in your Account Settings.<br />Used to bill you correctly for your SMS usage</td>
			<td><i class="icon-ok"></i></td>
		</tr>
	</tbody>
</table>
<p><br />Page rendered in {elapsed_time} seconds</p>
