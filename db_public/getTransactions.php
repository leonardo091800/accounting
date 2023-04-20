<?php
require_once $root_DB_main;
$conn = db::connect();

// get transactions
// $sql = "SELECT t.id, t.`accounts.in.id`, t.`accounts.out.id`, t.timestamp, t.amount, t.note,  a.id as accID, a.`users.id`
/*
$sqlOLD = "SELECT DISTINCT t.id , t.`accounts.in.id`, t.`accounts.out.id`, t.timestamp, t.amount, t.note
	FROM transactions t
        INNER JOIN accounts a
       	ON a.id=t.`accounts.in.id` OR a.id=t.`accounts.out.id`
	WHERE a.`users.id`={$_SESSION['userID']} 
	ORDER BY timestamp";
 */

$sql = "SELECT DISTINCT t.`id` , t.`timestamp`, t.`note`, ta.`id` as taID , ta.`accounts.id`, ta.`exit0orenter1`, ta.`amount`
	FROM transaction_accounts_involved ta

	INNER JOIN transactions t
	ON t.id=ta.`transactions.id`
	
	INNER JOIN accounts a
	ON ta.`accounts.id`=a.id

	WHERE a.`users.id`={$_SESSION['userID']} 
	ORDER BY timestamp";

//	echo "sql = $sql <br>";

try {
	$q=$conn->prepare($sql);
	$transactions = $q->execute();
	$_SESSION['transactions'] = $q->fetchAll(PDO::FETCH_ASSOC);

	echo "<br><pre>"; print_r($_SESSION['transactions']); echo"</pre>";

} catch(Exception $e) {
	echo "<br> <pre>"; var_dump($e->errorInfo);
	// if error of 1146-> table not found, probably due to upgrade to v.1.1.0
	// let's run the patch
	if($e->errorInfo[1] == 1146 && $e->errorInfo[2] == "Table 'accounting_db.transaction_accounts_involved' doesn't exist") {
		require_once $root_DB_setup;
		if(db_setup::patchv110($conn) == 0) {
			echo "successfully run patch v.1.1.0, redirecting in 5 seconds";
			redirect::to_page($root_Pages_HTML, 5000);
			exit;
		} else {
			die('some errors in patch v.1.1.0, written from getTransactions');
		}
	}

	die('error in getTransactions: '.$e);
}

/*
$_SESSION['transactions'] = array();

foreach($accounts as $account){
	$_SESSION['accounts'][$account['id']] = $account['name'];
}
 */
?>
