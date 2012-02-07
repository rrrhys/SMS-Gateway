

<h1>Some SMS Gateway</h1>
<?

if(isset($_SESSION['login_name']) && $_SESSION['login_name'] != "")
{
?>Hi <?=$_SESSION['login_name']; ?><?
}
else
{
?><a id="register_link">Register</a> | Demo<?
}
?><br />
<input type="hidden" name="login_token" id="login_token" value="<?=$_SESSION['login_token']?>" />