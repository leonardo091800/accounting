<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
?>

<!DOCTYPE html PUBLIC>
<html>
<head>
</head>
<body>
<?php
require_once $root_Scripts_checkSessionVariables;

if(isset($_GET['SESSION_usrSelected'])) {
	if(checkSessionVariable('usrSelected', $_GET['SESSION_usrSelected']) == 0) {
		$_SESSION['accountSelected'] = "";
		header("Location: $root_HTML");
	} else {
		die("something wrong in storeSessionVariables, call the admin");
	}
}
if(isset($_GET['SESSION_accountSelected'])) {
	if(checkSessionVariable('accountSelected', $_GET['SESSION_accountSelected']) == 0) {
		header("Location: $root_HTML");
	} else {
		die("something wrong in storeSessionVariables, call the admin");
	}
}
?>

</body>
</html>
