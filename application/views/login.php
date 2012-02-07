<div id="login">
<form method="post" id="login_form">
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
			<td>
				<div class="buttons">
					<a href="#" class="button save-big" onClick="document.forms['login_form'].submit();">Login</a>
				</div></td>
			</tr>
		</table>
</form>
</div>