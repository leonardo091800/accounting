<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_Scripts_cleanInput;
?>

<!DOCTYPE html PUBLIC>
<html>
<head>
</head>
<body>
<?php
if(isset($_GET['SESSION_what']) && isset($_GET['SESSION_value'])) {
	// cleaning the inputs...
	$SESSION_what = cleanInput($_GET['SESSION_what']);
	$SESSION_value = cleanInput($_GET['SESSION_value']);

	switch($SESSION_what) {
	case 'usrSelected':
		$_SESSION['usrSelected'] = $SESSION_value;
		$_SESSION['accountSelected'] = "";
		$_SESSION['transactionSelected'] = "";
		header("Location: $root_Pages_HTML");
		break;
	case 'accountSelected':
		$_SESSION['accountSelected'] = $SESSION_value;
		$_SESSION['transactionSelected'] = "";
		header("Location: $root_Pages_HTML");
		break;
	case 'transactionSelected':
		$_SESSION['transactionSelected'] = $SESSION_value;
		header("Location: $root_Pages_HTML");
		break;
	default:
		die('requested value not accepted by storeSessionVariables');
	}
}
?>

</body>
</html>
