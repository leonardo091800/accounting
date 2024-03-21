<?php
require_once $root_DB_main;

$conn = db::connect();

try {
	$v = $conn->query("SELECT `accounting_version` FROM `technicals`;");
	$_SESSION['accountingVersion'] = $v->fetch(PDO::FETCH_ASSOC)['accounting_version'];
//	echo "<br> version = {$_SESSION['accountingVersion']} <br>";
} catch (PDOException $e) {
//	echo "<br> Error in checkUpdate: <pre>"; print_r($e);
//	echo "<br> Error info: <pre>"; print_r($e->errorInfo);
	
	// if table does not exist than v=1.1.1
	if($e->errorInfo[1] == '1146') {
		require_once $root_updates.'patchv112.php';
		patchv112($conn);
	}
}
