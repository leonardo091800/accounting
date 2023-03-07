<?php
require_once $root_DB_main;
require_once $root_Errors_main;

$conn = db::connect();

// get accounts
$sql = "SELECT id, name, `account_types.id` FROM accounts WHERE `users.id`={$_SESSION['usrSelected']}";
$q=$conn->prepare($sql);
$accounts = $q->execute();
$accounts = $q->fetchAll(PDO::FETCH_ASSOC);

// short array version of accounts:
$accounts_id_name_arr = array();
foreach($accounts as $account){
	$accounts_id_name_arr[$account['id']] = $account['name'];
}

// get account_types...
$sql = "SELECT id, name FROM account_types";
$q=$conn->prepare($sql);
$account_types_arr = $q->execute();
$account_types_arr = $q->fetchAll(PDO::FETCH_ASSOC);
print_r($account_types_arr);
// .. into a single easy array
$accountTypesArr = array();
foreach($account_types_arr as $account_type_arr){
	$accountTypesArr[$account_type_arr['id']] = $account_type_arr['name'];
}

// print results in table
echo "
<table id='accountTable' class='mainTable'>
<tr><th> 
</th><th> Name 
</th><th> Type
</th><th> 
</th></tr>
";

// add row to create Account
echo "
<form id='addAccountForm' action='$root_DB_add_HTML' method='get'>
<input form='addAccountForm' type='hidden' name='table' value='accounts'>
<tr><td class='addButton'> 
<button form='addAccountForm' type='submit' class='addButton'> ADD </button> 
</td><td> <input form='addAccountForm' class='addInput' name='name' type='text' required> 
</td><td> <select form='addAccountForm' class='addInput' name='account_types_id' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($accountTypesArr as $type_id=>$type_name) {
		echo "<option value='$type_id'>$type_name</option>";
	}
echo "
</td><td>
</td></tr>
</form>
";

// print values in table Accounts
foreach($accounts as $row) {
	if(isset($_SESSION['accountSelected'])) {
		if($_SESSION['accountSelected'] == $row['id']) {
			echo "<tr class='selected'>";
		}
	} else {
		echo "<tr>";
	}

	$current_account_type_id = $row['account_types.id'];
	echo "
	<td class='selectButton'> 
	<a onclick=\"window.location.href = '$root_Pages_storeSessionVariables_HTML?SESSION_what=accountSelected&SESSION_value={$row['id']}'; \"> <button class='selectButton'> SELECT </button> </a>
	</td><td> {$row['name']} 
	</td><td> {$accountTypesArr[$current_account_type_id]} 
	</td><td class='rmButton'>
	";

// add button to delete Account
echo "
<form id='rmAccountForm{$row['id']}' action='$root_DB_rm_HTML' method='get'>
<input form='rmAccountForm{$row['id']}' type='hidden' name='table' value='accounts'>
<input form='rmAccountForm{$row['id']}' type='hidden' name='id' value='{$row['id']}'>
<button form='rmAccountForm{$row['id']}' type='submit' class='rmButton'> REMOVE </button> 
</form>
";
	echo "
	</td></tr>
	";
}
echo "</table>";
?>
