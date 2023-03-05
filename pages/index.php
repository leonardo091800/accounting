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
require_once $root_Pages_users;

if(isset($_SESSION['usrSelected'])) {
	if($_SESSION['usrSelected'] != '') {
		require_once $root_Pages_accounts;
	}
}

// require_once $rootPages.'transactions.php';
?>

</body>
</html>
