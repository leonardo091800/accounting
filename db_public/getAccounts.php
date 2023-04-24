<?php
require_once $root_DB_main;
$conn = db::connect();

// get accounts
$sql = "SELECT a.id as id, a.name as name, axg.`accounts_groups.id` as accGroupID, ag.name as accGroupNames, axg.id as axgID
	FROM accounts a

	LEFT JOIN account_x_group axg
	ON axg.`accounts.id` = a.id

	LEFT JOIN accounts_groups ag
	ON ag.id = axg.`accounts_groups.id`

	WHERE a.`users.id` = {$_SESSION['userID']}
	";

try {
	$q=$conn->prepare($sql);
	$accounts = $q->execute();
	$_SESSION['accounts'] = $q->fetchAll(PDO::FETCH_ASSOC);
//	echo "<br> Accounts  = <pre> "; print_r($_SESSION['accounts']); echo "</pre>";
} catch(Exception $e) {
	die('error in getAccounts: '.$e);
}
?>
