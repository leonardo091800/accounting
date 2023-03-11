<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_Scripts_checkSessionVariables;
?>

<!DOCTYPE html PUBLIC>
<html>
<head>
<?php 
require_once $root_Style_main;
?>
</head>
<body>
Ciao

<?php
// including the users pages
require_once $root_Pages_users;

// including the accounts pages
if(isset($_SESSION['usrSelected'])) {
	if($_SESSION['usrSelected'] != '') {
		require_once $root_Pages_accounts;
	}
}

// including the transactions page
if(isset($_SESSION['usrSelected']) && isset($_SESSION['accountSelected'])) {
	require_once $root_Pages_transactions;
}
?>

</body>
</html>
