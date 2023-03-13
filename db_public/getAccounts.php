<?php
require_once $root_DB_main;
$conn = db::connect();

// get accounts
$sql = "SELECT id, name FROM accounts";

try {
	$q=$conn->prepare($sql);
	$accounts = $q->execute();
	$_SESSION['accounts'] = $q->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
	die('error in getAccounts: '.$e);
}


/*
$_SESSION['accounts'] = array();

foreach($accounts as $account){
	$_SESSION['accounts'][$account['id']] = $account['name'];
}
 */
?>
