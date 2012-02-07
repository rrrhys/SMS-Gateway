<form method="post" action="/user/change_password">
<h3>Change Password</h3>
<p>Changing your password requires to you enter your old password and a new password.
<input type="hidden" name="password_submit" id="password_submit" value="yes" />
<table>
	<tr>
		<th>Old Password</th>
		<td><input type="password" name="old_password" id="old_password" /></td>
	</tr>
	<tr>
		<th>New Password</th>
		<td><input type="password" name="new_password" id="new_password" /></td>
	</tr>
	<tr>
		<th>Confirm New Password</th>
		<td><input type="password" name="new_password_2" id="new_password_2" /></td>
	</tr>
	<tr>
		<th>Save</th>
		<td><input type="submit" value="Save new Password" /></td>
	</tr>
</table>
</form>