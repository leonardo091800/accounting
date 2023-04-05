<?php
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
<div class='table'>
  <div class='tr'>
    <div class='th2'>
    <form id='createAccount' action='$root_DB_add_HTML' method='GET'>
      <input form='createAccount' type='hidden' name='table' value='accounts'>
      <input form='createAccount' type='text' name='name' required>
      <br>
      <input form='createAccount' type='submit' value='create Account'>
    </form>
    </div> <!-- /th2 createAccount -->
";

// then echo all accounts
foreach($_SESSION['accounts'] as $acc) {
	echo "
    <div class='th2 accName'>".$acc['name']
	;


	// Remove Button
	echo "
    <form id='rmAccount{$acc['id']}' action='$root_DB_rm_HTML' method='get'>
      <input form='rmAccount{$acc['id']}' type='hidden' name='table' value='accounts'>
      <input form='rmAccount{$acc['id']}' type='hidden' name='id' value='{$acc['id']}'>
      <button form='rmAccount{$acc['id']}' type='submit' class='rmButton'> remove Account</button> 
    </form>
    </div> <!-- /th2 {$acc['name']} -->
	";

}

echo "
    <div class='th2'> Note </div>
  </div> <!-- /tr -->
";



// ------------------------------------------------------
// 2nd Line of Table
// writing the headers of the second line of table:
echo "
  <div class='tr'>
    <div class='th2'></div> <!-- new account -->
";
foreach($_SESSION['accounts'] as $acc) {
	echo "<div class='th3'> Entrate </div><div class='th3'> Uscite </div>";
}

echo "
    <div class='th2'></div> <!-- note -->
  </div> <!-- /tr -->
";


// ------------------------------------------------------
// 3rd->(end-1) Line of Table
// then echo all transactions of each account
// (I'll also do the sum of each account, in order to not do the same for loop later)
foreach($_SESSION['transactions'] as $tr) {

	// Date &
	// & Remove Button
	echo "
  <div class='tr'>
    <div class='th2'>
	";
	echo date('d/m/Y H:i', strtotime($tr['timestamp']));
	echo "
<form id='rmTransaction{$tr['id']}' action='$root_DB_rm_HTML' method='get'>
<input form='rmTransaction{$tr['id']}' type='hidden' name='table' value='transactions'>
<input form='rmTransaction{$tr['id']}' type='hidden' name='id' value='{$tr['id']}'>
<button form='rmTransaction{$tr['id']}' type='submit' class='rmButton'> remove transaction</button> 
</form>
    </div> <!-- /th2 timestamp -->
	";


//	echo "<tr><td class='createNewAccount'></td>";
	foreach($_SESSION['accounts'] as $acc) {
		$accID = $acc['id'];
		if ($tr['accounts.in.id'] == $acc['id']) {
			echo "<div class='td entrate'> ".style::toMoney($tr['amount'])."</div> <div class='td uscite'></div>";
			$_SESSION['sums'][$accID] += $tr['amount'];
		} 
		elseif ($tr['accounts.out.id'] == $acc['id']) {
			echo "<div class='td entrate'></div> <div class='td uscite'> ".style::toMoney($tr['amount'])."</div>";
			$_SESSION['sums'][$accID] -= $tr['amount'];
		} 
		else {
			echo "<div class='td entrate'> </div> <div class='td uscite'> </div>";
		}
	}
	echo "
    <div class='th2 stickRight'> ".$tr['note']."</div>
  </div> <!-- /tr -->";
}



// ------------------------------------------------------
// end Line of Table
// echo totals of each account

echo "
  <div class='tr'>
    <div class='th2 stickLeft'> SUM: </div>
";

foreach($_SESSION['accounts'] as $acc) {
	$accID = $acc['id'];
	echo "<div class='th2 sum'> ".style::toMoney($_SESSION['sums'][$accID])."</div>";
}

echo "
    <div class='th2 void stickRigth'></div>
  </div> <!-- /tr -->
</div> <!-- /table -->
";

echo "</div> <!-- /generalLedger -->";
?>

