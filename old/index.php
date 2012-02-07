<?require_once("site_required.php");?>
<html>
	<head><title></title></head>
	<link href='http://fonts.googleapis.com/css?family=Maven+Pro|Nunito:700&subset=latin&v2' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/css.css" />
	
	<body>
		<div id="container">
		<? include("header.php"); ?>
		<?if(!isset($_SESSION['login_name']) || $_SESSION['login_name'] == "")
		{?>
		<div id="login">
		<table>
			<tr>
				<td>Email Address</td>
				<td><input type="text" name="emailaddress" id="emailaddress" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" name="password" id="password" /></td>
			</tr>
			<tr>
				<td colspan="2" id="login_errors"></td>
			</tr>
			<tr>
			<td></td>
			<td><input type="submit" value="Log In" id="submit_login" /></td>
			</tr>
		</table>
		</div>
		<div id="register">
		<table>
			<tr>
				<td>Email Address</td>
				<td><input type="text" name="emailaddressr" id="" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" name="passwordr" id="" /></td>
			</tr>
			<tr>
				<td>Mobile Phone Number</td>
				<td><input type="text" name="phoner" id="" /></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Create Account" id="submit_create_account" /></td>
			</tr>
			<tr>
			<td></td>
			<td></td>
			</tr>
		</table>
		</div>
		<div id="wait_activation">
		Your account has been created and is now awaiting activation.<br />
		Please check your email.
		</div>
		<?}
		else
		{?>
		<div id="send_sms">
		<table>
		<tr>
			<td>Template Name:</td>
			<td>
				<select name="templates" id="templates">
					
				</select>
			</td>
		</tr>
			<tr>
				<td>Send an SMS to:</td>
				<td><input type="text" name="sms_phone_number" id="sms_phone_number" /></td>
			</tr>
			<tbody id="template_header">
			<tr><td colspan=2>There are additional fields required for this template.</td></tr>
			</tbody>
		<tbody id="template_fields">
		
		</tbody>
			<tr>
				<td>Saying:</td>
				<td>
				<input type="hidden" id="base_sms_text" value="" />
				<textarea name="sms_text" id="sms_text" cols="30" rows="10"></textarea></td>
			</tr>
			<tr>
				<td>When:</td>
				<td>
				<input type="hidden" id="queue_type" value="" />
					<span id="now_button">Now</span> | <span id="specify_button">Specify</span><br />
					<span id="specify_time_wrapper">
						<select name="specify_date" id="specify_date">
							<option value="1 July 2011">1 July 2011</option>
							<option value="2 July 2011">2 July 2011</option>
							<option value="3 July 2011">3 July 2011</option>
							<option value="4 July 2011">4 July 2011</option>
							<option value="5 July 2011">5 July 2011</option>
							<option value="6 July 2011">6 July 2011</option>
							<option value="7 July 2011">7 July 2011</option>
							<option value="8 July 2011">8 July 2011</option>
							<option value="9 July 2011">9 July 2011</option>
							<option value="10 July 2011">10 July 2011</option>
						</select>
						<select name="specify_time" id="specify_time">
							<option value="8:00">8am</option>
							<option value="9:00">9am</option>
							<option value="10:00">10am</option>
							<option value="11:00">11am</option>
							<option value="12:00">12am</option>
							<option value="113:00">1pm</option>
							<option value="14:00">2pm</option>
							<option value="15:00">3pm</option>
							<option value="16:00">4pm</option>
							<option value="17:00">5pm</option>
						</select>		
					</span>
				</td>
			</tr>
			<tr>
				<td>OK?</td>
				<td><input type="submit" value="Send" id="send_sms_submit" /><input type="button" value="Preview" id="preview_sms" /></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</table>
		</div>
		
		</div>
		<?}?>
		<div id="debug"></div>
	</body>
	<script type="text/javascript" src="scripts/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="scripts/site.js"></script>
	<script type="text/javascript" src="scripts/send_sms_interface.js"></script>
	<script type="text/javascript">
	
	
	</script>
</html>