<?php
require_once $root_Scripts_cleanInput;

function checkSessionVariable($what, $var) {
	switch($what) {
	case 'usrSelected':
		$_SESSION['usrSelected'] = cleanInput($var);
		return 0;
		break;
	case 'accountSelected':
		$_SESSION['accountSelected'] = cleanInput($var);
		return 0;
		break;
	default:
		die("something wrong in checkSessionVariables, ask admin");
	}
}
?>

