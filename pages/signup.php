<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_cleanInput;

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
 	// if result is empty no user with that psw and email found
	if(isset($result['error'])) {
		if($result['type'] == 'isset') {
			$parameters = array('mail' => $mail, 'psw' => $p, 'name' => $name, 'surname' => $surname);
			if(db::add('users', $parameters) == 0) {
				echo "user $name $surname with email $mail added successfully, please log in to check credentials:";
				require_once $root_login;
			}
		}
	} else {
		echo "generic error found... maybe username already exists?";
		require_once $root_login;
	}
}

echo "
<div id='signup'>
Sign up form:
<form id='signupForm' action='$root_signup_HTML' method='POST'>
<br> <input type='text' name='name' value='' placeholder='Mario'>
<br> <input type='text' name='surname' value='' placeholder='Rossi'>
<br> <input type='text' name='mail' value='' placeholder='mario.rossi@gmail.com'>
<br> <input type='password' name='psw' value='' placeholder='yourVeryStrongP4ssw0rd#?'>
<br> <input type='submit' value='submit'>
</form>
</div> <!-- /signup-->
";
?>

