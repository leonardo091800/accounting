<?php
require_once $root_DB_main;
require_once $root_Errors_main;

$tableTMP = "transactions";

$sql = "SELECT id, `transaction_types.id`, `accounts.in.id`, `accounts.out.id`, timestamp, amount 
	FROM $tableTMP
	WHERE `accounts.in.id`={$_SESSION['accountSelected']} 
		OR `accounts.out.id`={$_SESSION['accountSelected']}";
echo $sql."<br>";

$q=$conn->prepare($sql);
$rows = $q->execute();
$rows = $q->fetchAll(PDO::FETCH_ASSOC);

// get transaction_types...
$sql = "SELECT id, name FROM transaction_types";
$q=$conn->prepare($sql);
$transaction_types_arr = $q->execute();
$transaction_types_arr = $q->fetchAll(PDO::FETCH_ASSOC);
// .. into a single easy array
$transactionTypesArr = array();
foreach($transaction_types_arr as $transaction_type_arr){
	$transactionTypesArr[$transaction_type_arr['id']] = $transaction_type_arr['name'];
}

// print results in table
echo "
<table id='transactionsTable' class='mainTable'>
<caption> Transactions </caption>
<tr><th> 
</th><th> amount 
</th><th> datetime of transaction
</th><th> From account
</th><th> &rarr;
</th><th> To account
</th><th> type of transaction
</th><th> 
</th></tr>
";

// add row to create transaction 
echo "
<form id='add{$tableTMP}Form' action='$root_DB_add_HTML' method='get'>
<input form='add{$tableTMP}Form' type='hidden' name='table' value='{$tableTMP}'>
<tr><td class='addButton'> 
<button form='add{$tableTMP}Form' type='submit' class='addButton'> ADD </button> 
</td><td> <input form='add{$tableTMP}Form' type='number' name='amount' class='addInput' required>
</td><td> <input form='add{$tableTMP}Form' type='datetime-local' name='timestamp' value='".date('Y-m-d\TH:i:s')."' class='addInput' required>
</td><td> <select form='add{$tableTMP}Form' class='addInput' name='accounts_in_id' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($accounts_id_name_arr as $account_id=>$account_name) {
		echo "<option value='$account_id'>$account_name</option>";
	}
echo "
</td><td> &rarr;
</td><td> <select form='add{$tableTMP}Form' class='addInput' name='accounts_out_id' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($accounts_id_name_arr as $account_id=>$account_name) {
		echo "<option value='$account_id'>$account_name</option>";
	}
echo "
</td><td> <select form='add{$tableTMP}Form' class='addInput' name='transaction_types_id' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($transactionTypesArr as $type_id=>$type_name) {
		echo "<option value='$type_id'>$type_name</option>";
	}
echo "
</td><td>
</td></tr>
</form>
";


// print values in table transactions 
foreach($rows as $row) {
	if(isset($_SESSION['transactionSelected'])) {
		if($_SESSION['transactionSelected'] == $row['id']) {
			echo "<tr class='selected'>";
		}
	} else {
		echo "<tr>";
	}
	echo "
	<td class='selectButton'> 
	<a onclick=\"window.location.href = '$root_Pages_storeSessionVariables_HTML?SESSION_what=tableSelected&SESSION_value={$row['id']}'; \"> <button class='selectButton'> SELECT </button> </a>
	</td><td> {$row['name']} 
	</td><td> {$row['surname']} 
	</td><td class='rmButton'>
	";
/*
// add button to delete USER
echo "
<form id='rmUserForm{$row['id']}' action='$root_DB_rm_HTML' method='get'>
<input form='rmUserForm{$row['id']}' type='hidden' name='table' value='users'>
<input form='rmUserForm{$row['id']}' type='hidden' name='id' value='{$row['id']}'>
<button form='rmUserForm{$row['id']}' type='submit' class='rmButton'> REMOVE </button> 
</form>
";
 */
	echo "
	</td></tr>
	";
}
echo "</table>";
?>
