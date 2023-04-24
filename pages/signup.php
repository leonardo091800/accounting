<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_cleanInput;
require_once $root_Scripts_style;

// just a small variable that get set to false when user logs in
$print_signup = true;

if(isset($_POST['mail']) && isset($_POST['psw']) && isset($_POST['name']) && isset($_POST['surname'])) {
	$mail = cleanInput($_POST['mail']);
	$p = cleanInput($_POST['psw']);
	$name = cleanInput($_POST['name']);
	$surname = cleanInput($_POST['surname']);
	$p = hash('sha512', $p);

	require_once $root_DB_main;

	// connect to db and check u & p
	$parameters = array('mail' => $mail, 'psw' => $p);
	$result = db::get('users', $parameters);
//	echo "<br> Result:"; print_r($result);

 	// if result is empty no user with that psw and email found, so continue with registration
	if(isset($result['error'])) {
		if($result['type'] == 'isset') {
			$parameters = array('mail' => $mail, 'psw' => $p, 'name' => $name, 'surname' => $surname);
			if(db::add('users', $parameters) == 0) {
				alerts::echo_advice("<br> user $name $surname with email $mail added successfully!");
				require_once $root_login;
				$print_signup = false;
			}
		}
	} else {
		header("Location: $root_Pages_HTML?loginResponse=generic error found... maybe username already exists?");
	}
}
if($print_signup != false) {
	echo "
	<div id='singup' class='table'>
	<div class='caption'> Signup form: </div>
	<form id='signupForm' action='$root_signup_HTML' method='POST'>
	<div class='tr'><input type='text' name='name' value='' placeholder='Mario'> </div>
	<div class='tr'><input type='text' name='surname' value='' placeholder='Rossi'> </div>
	<div class='tr'><input type='text' name='mail' value='' placeholder='mario.rossi@gmail.com'> </div>
	<div class='tr'><input type='password' name='psw' value='' placeholder='yourVeryStrongP4ssw0rd#?'> </div>
	<div class='tr'><input type='submit' value='submit'> </div>
	</form>
	</div> <!-- /signup-->
	";
}
?>

