<?require_once("site_required.php");?>
<?require_once("db.php");?>
<?

/*login_result 0 or 1
login_token null or token
login_name null or name
login_errors null or errors. */
$login_token = "some_login_token";
$login_name = "RHYS";

$emailaddress = $_POST['emailaddress'];
$password = $_POST['password'];
$login_result = login($emailaddress, $password);
$_SESSION['login_token'] = $login_token;
$_SESSION['login_name'] = $login_name;
echo json_encode(array('login_result'=>1, 'login_token'=>$login_token,'login_name'=>$login_name));