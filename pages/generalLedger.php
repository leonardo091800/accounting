<?php
// I know I should do it properly with a function etc. but dont have time and for now it works:
require_once $root_getAccounts;
require_once $root_getTransactions;

echo "<div id='generalLedger'>";

// - - - DEBUGGING - - -
// echo "<br> accounts: "; print_r($_SESSION['accounts']);
// echo "<br> transactions: "; print_r($_SESSION['transactions']);
// - - - DEBUGGING - - -


// ------------------------------------------------------
// add transaction box:
$tableTMP = "transactions";
echo "
<table>
<caption> Add Transaction </caption>
<form id='add{$tableTMP}Form' action='$root_DB_add_HTML' method='get'>
<input form='add{$tableTMP}Form' type='hidden' name='table' value='{$tableTMP}'>
<tr><th> Amount &euro;
</th><th> Date & Time
</th><th> From Account
</th><th> &rarr;
</th><th> To Account
</th><th> Note

</tr><tr>

</td><td> <input form='add{$tableTMP}Form' type='number' name='amount' class='addInput amount' step='0.01' required>
</td><td> <input form='add{$tableTMP}Form' type='datetime-local' name='timestamp' value='".date('Y-m-d H:i:s')."' class='addInput' required>
</td><td> <select form='add{$tableTMP}Form' class='addInput' name='accounts_out_id' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($_SESSION['accounts'] as $acc) {
		echo "<option value='".$acc['id']."'>".$acc['name']."</option>";
	}
echo "
</td><td> &rarr;
</td><td> <select form='add{$tableTMP}Form' class='addInput' name='accounts_in_id' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($_SESSION['accounts'] as $acc) {
		echo "<option value='".$acc['id']."'>".$acc['name']."</option>";
	}
echo "
</td><td> <input form='add{$tableTMP}Form' type='text' name='note' class='addInput'>
</tr>
<tr><td colspan='100%'>
<button form='add{$tableTMP}Form' type='submit' class='addButton'> ADD Transaction </button> 
</td></tr>
</form>
</table>
";


// ------------------------------------------------------
// 1st Line of Table
// possibility to create account first
echo "
<div class='tableDiv'>
<table>
<caption> General Ledger </caption>
<tr><th rowspan='2' class='stickLeft'>
<form id='createAccount' action='$root_DB_add_HTML' method='GET'>
  <input form='createAccount' type='hidden' name='table' value='accounts'>
  <input form='createAccount' type='text' name='name' required>
  <br>
  <input form='createAccount' type='submit' value='create Account'>
</form>
</th>
";

// then echo all accounts
foreach($_SESSION['accounts'] as $acc) {
	echo "<th colspan='2'>".$acc['name'];


	// Remove Button
	echo "
</td>
<form id='rmAccount{$acc['id']}' action='$root_DB_rm_HTML' method='get'>
<input form='rmAccount{$acc['id']}' type='hidden' name='table' value='accounts'>
<input form='rmAccount{$acc['id']}' type='hidden' name='id' value='{$acc['id']}'>
<button form='rmAccount{$acc['id']}' type='submit' class='rmButton'> remove Account</button> 
</form>
	";

		echo "</th>";
}

echo "
<th rowspan='2' class='stickRigth'> Note </th>
</tr>";



// ------------------------------------------------------
// 2nd Line of Table
echo "
<tr>
";

// writing the headers of the second line of table:
foreach($_SESSION['accounts'] as $acc) {
	echo "<th> Entrate </th><th> Uscite </th>";
}

echo "
</tr>
";


// ------------------------------------------------------
// 3rd->(end-1) Line of Table
// then echo all transactions of each account
// (I'll also do the sum of each account, in order to not do the same for loop later)
foreach($_SESSION['transactions'] as $tr) {

	// Date &
	// & Remove Button
	echo "
<tr>
<td class='stickLeft'> 
<br>
	";
	echo date('d/m/Y H:i', strtotime($tr['timestamp']));
	echo "
<br>
<form id='rmTransaction{$tr['id']}' action='$root_DB_rm_HTML' method='get'>
<input form='rmTransaction{$tr['id']}' type='hidden' name='table' value='transactions'>
<input form='rmTransaction{$tr['id']}' type='hidden' name='id' value='{$tr['id']}'>
<button form='rmTransaction{$tr['id']}' type='submit' class='rmButton'> remove transaction</button> 
</form>
</td>
	";


//	echo "<tr><td class='createNewAccount'></td>";
	foreach($_SESSION['accounts'] as $acc) {
		$accID = $acc['id'];
		if ($tr['accounts.in.id'] == $acc['id']) {
			echo "<td class='entrate'> ".style::toMoney($tr['amount'])."</td> <td class='uscite'></td>";
			$_SESSION['sums'][$accID] += $tr['amount'];
		} 
		elseif ($tr['accounts.out.id'] == $acc['id']) {
			echo "<td class='entrate'></td> <td class='uscite'> ".style::toMoney($tr['amount'])."</td>";
			$_SESSION['sums'][$accID] -= $tr['amount'];
		} 
		else {
			echo "<td class='entrate'> </td> <td class='uscite'> </td>";
		}
	}
	echo "
<td class='note stickRight'> ".$tr['note']."</td>
</tr>";
}



// ------------------------------------------------------
// end Line of Table
// echo totals of each account

echo "
<tr><th class='stickLeft'> SUM: </th>
";

foreach($_SESSION['accounts'] as $acc) {
	$accID = $acc['id'];
	echo "<th colspan='2'> ".style::toMoney($_SESSION['sums'][$accID])."</th>";
}

echo "
<td class='void stickRigth'></td>
</tr>
</table>
</div> <!-- /tableDiv -->
";

echo "</div> <!-- /generalLedger -->";
?>

