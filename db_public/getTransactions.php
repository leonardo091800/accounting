<?php
require_once $root_DB_main;
$conn = db::connect();

// get transactions
// $sql = "SELECT t.id, t.`accounts.in.id`, t.`accounts.out.id`, t.timestamp, t.amount, t.note,  a.id as accID, a.`users.id`
$sql = "SELECT DISTINCT t.id, t.`accounts.in.id`, t.`accounts.out.id`, t.timestamp, t.amount, t.note
	FROM transactions t
        INNER JOIN accounts a
       	ON a.id=t.`accounts.in.id` OR a.id=t.`accounts.out.id`
	WHERE a.`users.id`={$_SESSION['userID']} 
	ORDER BY timestamp";

	echo "sql = $sql <br>";

try {
	$q=$conn->prepare($sql);
	$transactions = $q->execute();
	$_SESSION['transactions'] = $q->fetchAll(PDO::FETCH_ASSOC);

	echo "<br><pre>"; print_r($_SESSION['transactions']); echo"</pre>";

} catch(Exception $e) {
	die('error in getTransactions: '.$e);
}

/*
$_SESSION['transactions'] = array();

foreach($accounts as $account){
	$_SESSION['accounts'][$account['id']] = $account['name'];
}
 */
?>
