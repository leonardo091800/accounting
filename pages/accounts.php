<?php
require_once $root_DB_main;
require_once $root_Errors_main;

$conn = db::connect();
$sql = "SELECT id, name FROM accounts WHERE `users.id`={$_SESSION['usrSelected']}";

$q=$conn->prepare($sql);
$rows = $q->execute();
$rows = $q->fetchAll(PDO::FETCH_ASSOC);

// print results in table
echo "
<table id='accountTable' class='mainTable'>
<tr><th> 
</th><th> Name 
</th><th> 
</th></tr>
";

// add row to create Account
echo "
<form id='addAccountForm' action='$root_DB_add_HTML' method='get'>
<input form='addAccountForm' type='hidden' name='table' value='users'>
<tr><td class='addButton'> 
<button form='addAccountForm' type='submit' class='addButton'> ADD </button> 
</td><td> <input form='addAccountForm' class='addInput' name='name' type='text' required> 
</td><td>
</td></tr>
</form>
";

// print values in table Accounts
foreach($rows as $row) {
	if($_SESSION['usrSelected'] == $row['id']) {
		echo "<tr class='selected'>";
	} else {
		echo "<tr>";
	}
	echo "
	<td class='selectButton'> 
	<a onclick=\"window.location.search += '&SESSION_accountSelected={$row['id']}'; \"> <button class='selectButton'> SELECT </button> </a>
	</td><td> {$row['name']} 
	</td><td class='rmButton'>
	";

/*
// add button to delete Account
echo "
<form id='rmAccountForm{$row['id']}' action='$root_DB_rm_HTML' method='get'>
<input form='rmAccountForm{$row['id']}' type='hidden' name='table' value='users'>
<input form='rmAccountForm{$row['id']}' type='hidden' name='id' value='{$row['id']}'>
<button form='rmAccountForm{$row['id']}' type='submit' class='rmButton'> REMOVE </button> 
</form>
";
 */
	echo "
	</td></tr>
	";
}
echo "</table>";
?>
