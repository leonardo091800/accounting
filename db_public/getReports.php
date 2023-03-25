<?php
require_once $root_DB_main;
$conn = db::connect();

// get accounts
$sql = "SELECT * FROM reports ORDER BY name";

try {
	$q=$conn->prepare($sql);
	$accounts = $q->execute();
	$_SESSION['reports'] = $q->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {

	echo "<br> <br>"; var_dump($e->errorInfo); echo "<br> <br>";
	// if table does not exist:
	if($e->errorInfo[1] == 1146) {
		require_once $root_DB_setup;
		db_setup::createReportTables($conn);
	}
}

?>
