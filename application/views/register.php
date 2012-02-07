	<form action="/welcome/register/" method="post" id="register_form">
	<div id="validation_fail"></div>
	<div id="register">
		<table>
			<tr>
				<td>Email Address</td>
				<td><input type="text" name="email_addressr" id="email_addressr" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" name="passwordr" id="passwordr" /></td>
			</tr>
			<tr>
				<td>Confirm Password</td>
				<td><input type="password" name="passwordconfirmr" id="passwordconfirmr" /></td>
			</tr>
			<tr>
				<td>Mobile Phone Number</td>
				<td><input type="text" name="phoner" id="phoner" /></td>
			</tr>
			<tr>
				<td>Time Zone</td>
				<td><p>The timezone you select affects the time your messages are sent, so please choose carefully!</p>
<?=timezone_menu()?></td>
			</tr>
			<tr>
				<td></td>
				<td><div class="buttons">
					<a href="#" class="button save-big" id="submit_create_account">Create Account</a>
				</div></td>
			</tr>
			<tr>
			<td></td>
			<td></td>
			</tr>
		</table>

		</div>
	</form>
	
		<script type="text/javascript">

		$(function(){
			$("#submit_create_account").click(function(){
			$("#validation_fail").html("");
			var validation_fail = false;
				//rough inline validation
				if($("#passwordconfirmr").val() != $("#passwordr").val())
				{
					$("#validation_fail").append("<div class=\"error_box\">The passwords supplied do not match!</div>");
					validation_fail = true;
				}
				//submit form
				if(!validation_fail){
				document.forms["register_form"].submit();
				}
			});
		});
		</script>