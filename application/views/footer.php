			
			<div id="debug"></div>
			<div id="notification_shell" style="position: absolute;bottom: 0px; right: 0px; width: 250px;">
	<!--<div style="position: absolute; bottom: 0px; background-color: #fff; border: 1px solid #ccc; padding: 10px; margin: 10px;">
	<strong>Notification Received</strong><br />
	Your SMS to 0414 111 222 has been posted successfully.
	</div-->
	</div>
</div>
<div id="footer">
<?if($logged_in){?>
<ul>
	<li><a href="/user/create_sms">New SMS</a></li>
	<li><a href="/user/dashboard">Dashboard</a></li>
	<li><a href="/user/templates">Templates</a></li>
	<li><a href="/user/preferences">Preferences</a></li>
	<li><a href="/user/logout">Logout</a></li>
</ul>
<?}else{?>
<ul>
	<li><a href="/user/login">Login</a></li>
	<li><a href="/user/register">Register</a></li>
	<li><a href="/user/about">About</a></li>
	<li><a href="/user/contact">Contact</a></li>
</ul>
<?}?>
</div>
	</body>

	<script type="text/javascript">
	
	
	</script>
</html>