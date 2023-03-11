<html>
<body>
<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_DB_main;
require_once $root_DB_defaults;
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
				alerts::echo_alert('user created, now creating default accounts...');

				// creating deafult accounts..
				$idTMP =  db::getID('users', $parameters);
				db_defaults::createDefaultAccounts($idTMP);

				// if created correctly (didnt die) then..
				alerts::echo_success();
				$_SESSION['usrSelected'] = $idTMP;
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
	case 'transactions':
		if(isset($_GET['amount']) && isset($_GET['timestamp']) && isset($_GET['accounts_in_id']) && isset($_GET['accounts_out_id']) && isset($_GET['transaction_types_id'])) {
			$amount = cleanInput($_GET['amount']);
			$timestamp = cleanInput($_GET['timestamp']);
			$accounts_in_id = cleanInput($_GET['accounts_in_id']);
			$accounts_out_id = cleanInput($_GET['accounts_out_id']);
			$transaction_types_id = cleanInput($_GET['transaction_types_id']);
			$parameters = array('amount'=>$amount, 'timestamp'=>$timestamp, 'accounts_in.id'=>$accounts_in_id, 'accounts_out.id'=>$accounts_out_id, 'transaction_type.id'=>$transaction_types_id);

			if(db::add('transactions', $parameters) == 0) {
				alerts::echo_success();
				redirect::to_page($root_Pages_HTML);
			}
		} else {
			errors::echo_error('fieldNotGiven', 'some fields in db_public/add transactions');
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
