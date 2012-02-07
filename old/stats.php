<?require_once("site_required.php");?>
<html>
	<head><title></title></head>
	<link href='http://fonts.googleapis.com/css?family=Maven+Pro|Nunito:700&subset=latin&v2' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/css.css" />
	
	<body>
	
		<div id="container">
				<? include("header.php"); ?>
			<table cellspacing=0 cellpadding=0 class="bordered">
				<tr>
					<th>To Number</th>
					<th>Send Date</th>
					<th>Contents</th>
					<th>$</th>
				</tr>
				<tr>
					<td colspan="4" class="table_group_heading">Queued</td>
				</tr>

				<tbody id="queued_sms_list">
					<tr class="queued_sms">
						<td>0404 123 300</td>
						<td>17/7 5pm</td>
						<td>Dear Rhys, your car will be ready ...</td>
						<td>0.25</td>
					</tr>
				</tbody>
				<tr class="footer">
					<td>Total</td>
					<td></td>
					<td></td>
					<td>0.25</td>
				</tr>
				<tr>
					<td colspan="4" class="table_group_heading">Sent</td>
				</tr>
					<tr class="queued_sms">
						<td>0404 123 300</td>
						<td>17/7 5pm</td>
						<td>Dear Rhys, your car will be ready ...</td>
						<td>0.25</td>
					</tr>
				<tr class="footer">
					<td>Total</td>
					<td></td>
					<td></td>
					<td>0.25</td>
				</tr>
			</table>
		</div>
		
	</body>
	<script type="text/javascript" src="scripts/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="scripts/site.js"></script>
	<script type="text/javascript">
	$(function(){
	
	});
	</script>
</html>