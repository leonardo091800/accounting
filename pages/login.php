<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_cleanInput;
require_once $root_Errors_main;

// just a small variable that get set to false when user logs in
$print_login = true;

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
			header("Location: $root_Pages_HTML?loginResponse=USername Not Found or Wrong Password");
		}
	} else {
//		print_r($result);
		echo "<br> logged in successfully as {$result[0]['name']} {$result[0]['surname']} <br> redirecting in 2 seconds...";
		$_SESSION['userID'] = $result[0]['id'];
		$_SESSION['authenticated'] = true;

		// since v.1.1.0 transactionAdd needs a variable to say default accounts involved:
		$_SESSION['transactionAdd']['accountsExitInvolved'] = 1;
		$_SESSION['transactionAdd']['accountsEnterInvolved'] = 1;

		redirect::to_page($root_Pages_HTML, '2000');

		// if succesfully logged in, there's no need to print the login page again
		$print_login = false;
	}
} 
if($print_login!=false) {
	echo "
	<div id='login' class='table'>
	<div class='caption'> login form: </div>
	<form id='loginForm' action='$root_login_HTML' method='POST'>
	<div class='tr'> <input type='text' name='mail' value='' placeholder='yourMail'> </div>
	<div class='tr'> <input type='password' name='psw' value='' placeholder='yourPassword'> </div>
	<div class='tr'> <input type='submit' value='submit'> </div>
	</form>
	</div> <!-- /login -->
	";
}
?>
