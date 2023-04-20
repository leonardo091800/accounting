<?php
echo "<div id='generalLedger'>";

// - - - DEBUGGING - - -
// echo "<br> accounts: "; print_r($_SESSION['accounts']);
// echo "<br> transactions: "; print_r($_SESSION['transactions']);
// - - - DEBUGGING - - -




// ------------------------------------------------------
// 1st Line of Table
// possibility to create account first
echo "
<div class='table'>
  <div class='caption'> General Ledger </div>
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

// from v.1.1.0 every transaction can change multiple accounts, each transactions is defined by 'id'
$currentTrID = 0;
foreach($_SESSION['transactions'] as $tr) {
	if($currentTrID != $tr['id']) {
		// if trID is different then
		// create new <tr>
		// echo <th> with timestamp and rm button
		$currentTrID = $tr['id'];
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
	
		// also echo all <td> for all the accounts
		foreach($_SESSION['accounts'] as $acc) {
			$accID = $acc['id'];
			echo "
  <div class='td enter' id='t{$currentTrID}acc{$accID}enter'></div>
  <div class='td exit' id='t{$currentTrID}acc{$accID}exit'></div>
			";
		}

		// also echo the <th> note column
		echo "
  <div class='th2 stickRight'> ".$tr['note']."</div>
</div> <!-- /tr -->";
	}

	// now we need to populate the <td> we just created with the correct amounts
	$accID = $tr['accounts.id'];
	if($tr['exit0orenter1'] == 0) {
		// if the amount exited from the account:
		echo '<script> $(document).ready(function() { $("#t'.$currentTrID.'acc'.$accID.'exit").html("'.$tr['amount'].'"); }); </script>';
	} else {
		// if the amount entered the account:
		echo '<script> $(document).ready(function() { $("#t'.$currentTrID.'acc'.$accID.'enter").html("'.$tr['amount'].'"); }); </script>';
	}
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

