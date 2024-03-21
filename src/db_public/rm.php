<html>
<body>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_Scripts_cleanInput;
require_once $root_DB_main;
require_once $root_Errors_main;

if(isset($_GET['table'])) {
	if(isset($_GET['id'])) {
	/* 
	 * rm(table, id)
	 * if there are children, it is impossibile to rm (for safety, such as in accounts)
	 * BUT, we should have an exception for transaction)
	 */
		$table = cleanInput($_GET['table']);
		$id = cleanInput($_GET['id']);
//		echo "<br> t.id = $id <br>";

		// if user wants to rm transaction: we must first rm all related transaction_accounts_involved
		if($table == 'transactions') {
			$taName = 'transaction_accounts_involved';

			// get all the children
			$taChildren = db::get($taName, array('transactions.id'=>$id));	
//			echo "<br> tachilrend = <br>"; print_r($taChildren);

			// delete all the children
			foreach($taChildren as $i=>$ta) {
//				echo "<br> deleting ta with id {$ta['id']} <br>";
				if(db::rm($taName, $ta['id']) != '0') {
					errors::echo_error('generic', "cannot delete ta_chilren with id = {$ta['id']} from transaction $id");
				}
			}

			// now proceed to delete the transaction
		}

		// extra for redirecting
		if(isset($_GET['redirectTO'])) {
			$redirectTO = cleanInput($_GET['redirectTO']);
		} else {
			$redirectTO = $root_Pages_HTML;
		}

		// if id is of a transaction, let's delete the references of that transaction first

		if(db::rm($table, $id) == '0') {
			alerts::echo_success();
			redirect::to_page($redirectTO);
		}
 	} 
	else {
		errors::echo_error('fieldNotGiven', 'id');
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
