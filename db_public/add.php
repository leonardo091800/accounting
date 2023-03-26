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
	// - - Accounts - -
	case 'accounts':
		if(isset($_GET['name'])) {
			$name = cleanInput($_GET['name']);
			$parameters = array('name'=>$name);

			if(db::add('accounts', $parameters) == 0) {
//				alerts::echo_success();

				// resetting the session parameters:

				// redirecting to pages
				redirect::to_page($root_Pages_HTML);
			}
		} else {
			errors::echo_error('fieldNotGiven', 'Name or account type');
		}
		break;



	// - - Transactions - -
	case 'transactions':
		if(isset($_GET['amount']) && isset($_GET['timestamp']) && isset($_GET['accounts_in_id']) && isset($_GET['accounts_out_id']) && isset($_GET['note'])) {
			$amount = number_format(cleanInput($_GET['amount']), 2, ".", "");
			$timestamp = cleanInput($_GET['timestamp']);
			$accounts_in_id = cleanInput($_GET['accounts_in_id']);
			$accounts_out_id = cleanInput($_GET['accounts_out_id']);
			$note = cleanInput($_GET['note']);

			$parameters = array('amount'=>$amount, 'timestamp'=>$timestamp, 'accounts.in.id'=>$accounts_in_id, 'accounts.out.id'=>$accounts_out_id, 
			'note' => $note);

			if(db::add('transactions', $parameters) == 0) {
//				alerts::echo_success();
				redirect::to_page($root_Pages_HTML);
			}
		} else {
			errors::echo_error('fieldNotGiven', 'some fields in db_public/add transactions');
		}
		break;


	// - - Reports - -
	case 'reports':
		if(isset($_GET['parameters'])) {
			$parameters = cleanInputArr($_GET['parameters'])['parameters'];
			print_r($parameters);
			if(!isset($parameters['realTable'])) {
				$tableTMP = 'reports';
			} else {
				$tableTMP = $parameters['realTable'];
				unset($parameters['realTable']);
				$reportID = $parameters['reportID'];
				unset($parameters['reportID']);
				if ($parameters['name'] == '') {
					$parameters['name'] = date("Y-m-d-H-i-s");
					// why am I doing this horrible shit?
					// cause in db::add I have a function that check if a similar type with same name already exist
					// I do not want to change that function so if just create a unique name in this shitty way
				}
			}
			if(db::add($tableTMP, $parameters) == 0) {
//				alerts::echo_success();
				if($tableTMP == 'reports') {
					$reportID = db::getID($tableTMP, $parameters);
				}
				redirect::to_page($root_reportPersonalized_HTML."?reportID=$reportID");
			}
		} else {
			errors::echo_error('fieldNotGiven', 'some fields in db_public/add report');
		}
		break;

	// - - Default - -
	default:
      		errors::echo_error('tableNotExist', "$table in db_public/add");
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
