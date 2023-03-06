<?php
require_once $root_DB_main;
require_once $root_Errors_main;

$conn = db::connect();
$sql = "SELECT id, name, surname FROM users";

$q=$conn->prepare($sql);
$rows = $q->execute();
$rows = $q->fetchAll(PDO::FETCH_ASSOC);

// print results in table
echo "
<table id='usersTable' class='mainTable'>
<tr><th> 
</th><th> Name 
</th><th> Surname 
</th><th> 
</th></tr>
";

// add row to create user
echo "
<form id='addUserForm' action='$root_DB_add_HTML' method='get'>
<input form='addUserForm' type='hidden' name='table' value='users'>
<tr><td class='addButton'> 
<button form='addUserForm' type='submit' class='addButton'> ADD </button> 
</td><td> <input form='addUserForm' class='addInput' name='name' type='text' required> 
</td><td> <input form='addUserForm' class='addInput' name='surname' type='text' required> 
</td><td>
</td></tr>
</form>
";


// print values in table users
foreach($rows as $row) {
	if(isset($_SESSION['usrSelected'])) {
		if($_SESSION['usrSelected'] == $row['id']) {
			echo "<tr class='selected'>";
		}
	} else {
		echo "<tr>";
	}
	echo "
	<td class='selectButton'> 
	<a onclick=\"window.location.search += '&SESSION_usrSelected={$row['id']}'; \"> <button class='selectButton'> SELECT </button> </a>
	</td><td> {$row['name']} 
	</td><td> {$row['surname']} 
	</td><td class='rmButton'>
	";

// add button to delete USER
echo "
<form id='rmUserForm{$row['id']}' action='$root_DB_rm_HTML' method='get'>
<input form='rmUserForm{$row['id']}' type='hidden' name='table' value='users'>
<input form='rmUserForm{$row['id']}' type='hidden' name='id' value='{$row['id']}'>
<button form='rmUserForm{$row['id']}' type='submit' class='rmButton'> REMOVE </button> 
</form>
";
	echo "
	</td></tr>
	";
}
echo "</table>";
?>
