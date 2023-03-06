<html>
<body>
<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_DB_main;
require_once $root_Scripts_cleanInput;
require_once $root_Errors_main;

if(isset($_GET['table'])) {
	$table = cleanInput($_GET['table']);

	switch ($table) {
	case 'users':
		if(isset($_GET['name']) && isset($_GET['surname'])) {
			$name = cleanInput($_GET['name']);
			$surname = cleanInput($_GET['surname']);
			$parameters = array('name'=>$name, 'surname'=>$surname,);

			if(db::add('users', $parameters) == 0) {
				alerts::echo_success();
				redirect::to_page($root_Pages_HTML);
			}
		} else {
			errors::echo_error('fieldNotGiven', 'Name or Surname');
		}
		break;
	case 'accounts':
		if(isset($_GET['name']) && isset($_GET['account_types_id'])) {
			$name = cleanInput($_GET['name']);
			$account_types_id = cleanInput($_GET['account_types_id']);
			$parameters = array('name'=>$name, 'account_types.id'=>$account_types_id, 'users.id'=>$_SESSION['usrSelected']);

			if(db::add('accounts', $parameters) == 0) {
				alerts::echo_success();
				redirect::to_page($root_Pages_HTML);
			}
		} else {
			errors::echo_error('fieldNotGiven', 'Name or account type');
		}
		break;
	default:
      		errors::echo_error('tableNotExist', "$table");
	} 
}
else {
  errors::echo_error('fieldNotGiven', 'table');
}
/*
 */
?>
</body>
</html>
