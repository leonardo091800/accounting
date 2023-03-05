<?php
require_once $root_Scripts_cleanInput;

if(isset($_GET['SESSION_usrSelected'])) {
	$_SESSION['usrSelected'] = cleanInput($_GET['SESSION_usrSelected']);
}
?>

