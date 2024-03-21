<html>
<body>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/z.scripts/root.php';
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
			$parameters = array('name'=>$name, 'users.id'=>$_SESSION['userID']);

			if(db::add('accounts', $parameters) === 0) {
//				alerts::echo_success();

				// redirecting to pages
				redirect::to_page($root_Pages_HTML);
			} else {
				alerts::echo_alert('something went wrong');
				redirect::to_page($root_Pages_HTML);
			}
		} else {
			errors::echo_error('fieldNotGiven', 'Name');
		}
		break;
	case 'accounts_groups':
		if(isset($_GET['name'])) {
			$name = cleanInput($_GET['name']);
			$parameters = array('name'=>$name, 'users.id'=>$_SESSION['userID']);

			if(db::add('accounts_groups', $parameters) === 0) {
//				alerts::echo_success();

				// redirecting to pages
				redirect::to_page($root_Pages_HTML);
			} else {
				alerts::echo_alert('something went wrong');
				redirect::to_page($root_Pages_HTML);
			}
		} else {
			errors::echo_error('fieldNotGiven', 'Name');
		}
		break;
	case 'account_x_group':
		if(isset($_GET['accID']) && isset($_GET['agID'])) {
			$accID = cleanInput($_GET['accID']);
			$agID = cleanInput($_GET['agID']);
			$parameters = array('accounts.id'=>$accID, 'accounts_groups.id'=>$agID);

			if(db::add('account_x_group', $parameters) === 0) {
//				alerts::echo_success();

				// redirecting to pages
				redirect::to_page($root_Pages_HTML);
			} else {
				alerts::echo_alert('something went wrong');
				redirect::to_page($root_Pages_HTML);
			}
		} else {
			errors::echo_error('fieldNotGiven', 'accID or agID');
		}
		break;



	// - - Transactions - -
	case 'transactions':
		/*
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
			} else {
				alerts::echo_alert('something went wrong');
				redirect::to_page($root_Pages_HTML);
			}
		} 
		 */
		
		/*
		 * In v.1.1.0 1 transaction alters multiple accounts, parameters must be given as
		 * transaction('timestamp'=>timestamp, 'note'=>"description of tr")
		 * ta(
		 *   acc1('accID' => accID, 'exit0orenter1' => 0/1, 'amount' => amount)
		 *   accn(...)
		 *   ...
		 * )
		 */
		if(isset($_GET['transaction']) && isset($_GET['ta'])) {
//			echo "<br> transaction: <br>"; print_r($_GET['transaction']);
//			echo "<br> ta : <br><pre>"; print_r($_GET['ta']);

			// first create transaction
			$timestamp = cleanInput($_GET['transaction']['timestamp']);
			$note = cleanInput($_GET['transaction']['note']);
			$parameters = array('timestamp'=>$timestamp, 'note' => $note);
			$result = db::add('transactions', $parameters);
			if($result !== 0) {
//				echo "<br> result = <pre> "; print_r($result);
				die('error in creating transaction from db_public');
			}

			// get id of newly created transaction
			// ! ! ! BUG ! ! ! DOes not work if in the parameters there is the note
			unset($parameters['note']);
			$tID = db::getID('transactions', $parameters);

			// then add tr_acc_involved for each involved account
			foreach($_GET['ta'] as $ta){
//				echo "<br> ta : <br><pre>"; print_r($ta);
				/*
				 *  ! ! ! possible problems in the future ! !  !
				 *  if the user has sent a NULL account, let's just skip it:
				 *  (but at least send an alert)
				 */
				if(isset($ta['accID']) && isset($ta['exit0orenter1']) && isset($ta['amount'])) {
					if(empty($ta['accID'])) {
						echo "<br> null parameter accID = {$ta['accID']} <br>";
						alerts::echo_alert('accID is null, no worries, just make sure the transaction was added correctly');
					}
					else if(($ta['exit0orenter1'])=='') {
						echo "<br> null parameter exit0orenter1= {$ta['exit0orenter1']} <br>";
						alerts::echo_alert('exit0orenter1 is null, no worries, just make sure the transaction was added correctly');
					}
					else if(empty($ta['amount'])) {
						echo "<br> null parameter amount = {$ta['amount']} <br>";
						alerts::echo_alert('amount is null, no worries, just make sure the transaction was added correctly');
					}
					else {
						$accID = cleanInput($ta['accID']);
						$exit0orenter1 = cleanInput($ta['exit0orenter1']);
						$amount = number_format(cleanInput($ta['amount']), 2, ".", "");
						$parameters = array('transactions.id'=>$tID, 'accounts.id'=>$accID, 'exit0orenter1'=> $exit0orenter1, 'amount'=>$amount);
						$result = db::add('transaction_accounts_involved', $parameters);
						if($result !== 0) {
//							echo "<br> Result = <pre> "; print_r($result);
							die('error in creating transaction_accounts_involved from db_public');
						}
					}
				} else {
					echo "<br> null account <br>";
					alerts::echo_alert('there was a null account given, no worries, just make sure the transaction was added correctly');
				}
			}

			// if everything is ok:
			//alerts::echo_success();
			$_SESSION['transactionAdd']['accountsExitInvolved'] = 1;
			$_SESSION['transactionAdd']['accountsEnterInvolved'] = 1;
			redirect::to_page($root_Pages_HTML);
		} else {
			errors::echo_error('fieldNotGiven', 'some fields in db_public/add transactions');
		}

		break;


	// - - Reports - -
	case 'reports':
		if(isset($_GET['parameters'])) {
			$a = filter_input_arraY(INPUT_GET);
			$parameters = cleanInputArr($a['parameters']);
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
			if(db::add($tableTMP, $parameters) === 0) {
//				alerts::echo_success();
				if($tableTMP == 'reports') {
					$reportID = db::getID($tableTMP, $parameters);
				}
				redirect::to_page($root_reportPersonalized_HTML."?reportID=$reportID");
			} else {
				alerts::echo_alert('something went wrong');
				redirect::to_page($root_Pages_HTML);
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
