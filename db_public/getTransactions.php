<?php
require_once $root_DB_main;
$conn = db::connect();

// get accounts
$sql = "SELECT * FROM transactions ORDER BY timestamp";

try {
	$q=$conn->prepare($sql);
	$accounts = $q->execute();
	$_SESSION['transactions'] = $q->fetchAll(PDO::FETCH_ASSOC);
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
