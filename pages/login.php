<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_cleanInput;
require_once $root_Errors_main;

if(isset($_POST['mail']) && isset($_POST['psw'])) {
	$mail = cleanInput($_POST['mail']);
	$p = cleanInput($_POST['psw']);
	$p = hash('sha512', $p);

	require_once $root_DB_main;

	// connect to db and check u & p
	$parameters = array('mail' => $mail, 'psw' => $p);
	$result = db::get('users', $parameters);

//	echo "<br> Result:"; print_r($result);
 	// if result is empty no user with that psw and email found
	if(isset($result['error'])) {
		if($result['type'] == 'isset') {
			echo "username not found please signup:";
			require_once $root_signup;
		}
	} else {
		print_r($result);
		echo "logged in successfully as {$result[0]['name']} {$result[0]['surname']} <br> redirecting in 2 seconds...";
		$_SESSION['u'] = $result[0]['mail'];
		$_SESSION['authenticated'] = true;
		redirect::to_page($root_Pages_HTML, '2000');
	}
}

echo "
<div id='login'>
login form:
<form id='loginForm' action='$root_login_HTML' method='POST'>
<br> <input type='text' name='mail' value='' placeholder='yourMail'>
<br> <input type='password' name='psw' value='' placeholder='yourPassword'>
<br> <input type='submit' value='submit'>
</form>
</div> <!-- /login -->
";
?>
