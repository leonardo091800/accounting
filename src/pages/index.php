<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_Scripts_checkSessionVariables;
require_once $root_Scripts_style;
?>

<!DOCTYPE html>
<html lang='en'>
<head>
<title> Accounting for moms </title>
<?php 
require_once $root_Style_main;
require_once $root_js_toggleDisplay;
require_once $root_js_setStyle;
require_once $root_js_jquery;
?>
<?php
echo "
<script>
$(document).ready(function(){
";
if(isset($_SESSION['menuSelected'])) {
	$menuSelected = $_SESSION['menuSelected'];
} else {
	$menuSelected = '';
}
/*
 * what if I just require what I want, instead of requiring everything and then hiding?
switch($menuSelected) {
case 'generalLedger':
	echo "
	setStyle('id', 'generalLedger', 'display', '');
	setStyle('id', 'reports', 'display', 'none');
	";
	break;
case 'reports':
	echo "
	setStyle('id', 'generalLedger', 'display', 'none');
	setStyle('id', 'reports', 'display', '');
	";
	break;
default:
	echo "
	setStyle('id', 'generalLedger', 'display', 'none');
	setStyle('id', 'reports', 'display', 'none');
	";
	break;
}
*/

echo "
});
</script>
";
?>

</head>
<body>
<?php
require_once $root_checkUpdate;



// if there's no user, then user is not logged in
if(!isset($_SESSION['userID']) || !isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
	if(isset($_GET['loginResponse'])) {
		echo "<span class='error'> ".$_GET['loginResponse']." </span>";
	}
	alerts::echo_advice("please login or signup");
	echo "<div class='table'>";
	require_once $root_login;
	require_once $root_signup;
	echo "</div>";
	exit;
}

// even if there is user, it must be authenticated
/*
} elseif () {
	echo "<br> Wrong credentials, please try again <br>";
	require_once $root_login;
	exit;
}
 */

// - - - Session variables - - -
// I know I should do it properly with a function etc. but dont have time and for now it works:
require_once $root_getAccounts;
//require_once $root_getAccountsGroups;
require_once $root_getTransactions;

$_SESSION['sums'] = array();
// if I don't do this, it warns me that 'undefined offset'
if(isset($_SESSION['accounts'])) {
	foreach($_SESSION['accounts'] as $acc) {
		$accID = $acc['id'];
		$_SESSION['sums'][$accID] = 0;
	}
} else {
	header($root_Pages_HTML);
}
// - - - /session variables - - -



// - - - menu - - - 
require_once $root_Pages_menu;
// - - - /menu - - - 


// - - - generalLedger - - - 
if($menuSelected == 'generalLedger') {
	// if there are no accounts, hide the add-transaction page
	if(count($_SESSION['accounts']) <= 1 ) {
		alerts::echo_advice("You need to create at least 2 accounts first!");
	} else {
		require_once $root_transactionAdd;
	}
	require_once $root_generalLedger;
}
// - - - /menu - - - 


// - - - reports - - - 
if($menuSelected == 'reports') {
	require_once $root_reports;
}
// - - - /reports - - - 


// - - - profile - - - 
if($menuSelected == 'profile') {
	require_once $root_profile;
}
// - - - /profile - - - 



?>
</body>
</html>
