<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html>
	<head><title><?=$title?></title>
	
	<!-- link href='http://fonts.googleapis.com/css?family=Maven+Pro|Nunito:700&subset=latin&v2' rel='stylesheet' type='text/css' -->
	<link rel="stylesheet" href="/css/bootstrap.css" />
	<link rel="stylesheet" href="/css/bootstrap_annotation.css" />
	<link rel="stylesheet" href="/css/iphone.css" />
	<link rel="stylesheet" href="/css/timePicker.css" />
	<script type="text/javascript" src="/scripts/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="/scripts/site.js"></script>
	<script type="text/javascript" src="/scripts/jquery.timePicker.js"></script>
	<?if($notifications){?>
	<script type="text/javascript" src="/scripts/notification.js"></script>
	<?}?>
	
	</head>

	<body>
		<div class="navbar">
			<div class="navbar-inner">
				<div class="container">
				<a class="brand" href="#">
					SMS Gateway
				</a>
				<ul class="nav">
					<li><a href="/user/create_sms"><span class="nav_link">New SMS</span></a></li>
					
					<li><a href="/user/templates"><span class="nav_link">Templates</span></a></li>

					<li><a href="/user/api_reference"><span class="nav_link">API Reference</span></a></li>

					<li></li>
					<?if(!$logged_in)
					{
					?>
					<li><a href="/user/dashboard"><span class="nav_link">Dashboard</span></a></li>
					<li><a href="/welcome/register"><span class="nav_link right">Register</span></a></li>
					<li><a href="/welcome/login"><span class="nav_link right">Login</span></a></li>
					

					<?}
					else
					{?>
					<li><a href="/user/preferences" /><span class="nav_link right">Preferences</span></a></li>
				<li><a href="/welcome/logout" /><span class="nav_link right">Logout</span></a></li>
					<?}?>
				</ul>
				</div>
			</div>
		</div>
		<div class="container">
<?
if($error_flash != "")
{
?><div class="alert"><a class="close">x</a><?=$error_flash;?></div><?
}
?>
<?
if($flash != "")
{
?><div class="alert"><a class="close">x</a><?=$flash;?></div><?
}
?>